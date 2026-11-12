<?php

declare(strict_types=1);

/**
 * One-time data transfer after the PostgreSQL schema has been created with
 * `php artisan migrate`. Target credentials are supplied through environment
 * variables so privileged import credentials never enter the repository.
 */

function requiredEnvironment(string $key): string
{
    $value = trim((string) getenv($key));
    if ($value === '') {
        throw new RuntimeException("Environment variable {$key} is required.");
    }

    return $value;
}

function quoteIdentifier(string $identifier): string
{
    return '"'.str_replace('"', '""', $identifier).'"';
}

$sourcePath = getenv('SOURCE_SQLITE_PATH') ?: dirname(__DIR__).'/database/database.sqlite';
if (! is_file($sourcePath)) {
    throw new RuntimeException("SQLite source not found: {$sourcePath}");
}

$source = new PDO('sqlite:'.$sourcePath, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$target = new PDO(
    requiredEnvironment('TARGET_PG_DSN'),
    requiredEnvironment('TARGET_PG_USER'),
    (string) getenv('TARGET_PG_PASSWORD'),
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
);

$sourceTables = $source->query(
    "SELECT name FROM sqlite_master
     WHERE type = 'table' AND name NOT LIKE 'sqlite_%'
     ORDER BY name"
)->fetchAll(PDO::FETCH_COLUMN);
$targetTables = $target->query(
    "SELECT table_name FROM information_schema.tables
     WHERE table_schema = 'public' AND table_type = 'BASE TABLE'"
)->fetchAll(PDO::FETCH_COLUMN);
$tables = array_values(array_intersect($sourceTables, $targetTables));

if ($tables === []) {
    throw new RuntimeException('No matching tables were found. Run PostgreSQL migrations first.');
}

$target->beginTransaction();

try {
    // A privileged, one-time import may bypass FK ordering. Normal application
    // connections remain constrained before and after this transaction.
    $target->exec("SET LOCAL session_replication_role = 'replica'");

    $truncateTables = implode(', ', array_map('quoteIdentifier', $tables));
    $target->exec("TRUNCATE TABLE {$truncateTables} RESTART IDENTITY CASCADE");

    $summary = [];
    foreach ($tables as $table) {
        $quotedTable = quoteIdentifier($table);
        $sourceColumns = $source->query("PRAGMA table_info({$quotedTable})")->fetchAll();
        $targetColumnStatement = $target->prepare(
            "SELECT column_name, data_type
             FROM information_schema.columns
             WHERE table_schema = 'public' AND table_name = ?
             ORDER BY ordinal_position"
        );
        $targetColumnStatement->execute([$table]);
        $targetColumnTypes = array_column($targetColumnStatement->fetchAll(), 'data_type', 'column_name');
        $columns = array_values(array_filter(
            array_column($sourceColumns, 'name'),
            static fn (string $column): bool => array_key_exists($column, $targetColumnTypes),
        ));

        if ($columns === []) {
            continue;
        }

        $rows = $source->query("SELECT * FROM {$quotedTable}");
        $quotedColumns = implode(', ', array_map('quoteIdentifier', $columns));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $insert = $target->prepare("INSERT INTO {$quotedTable} ({$quotedColumns}) VALUES ({$placeholders})");
        $count = 0;

        while ($row = $rows->fetch()) {
            $values = [];
            foreach ($columns as $column) {
                $value = $row[$column];
                if ($value !== null && $targetColumnTypes[$column] === 'boolean') {
                    $value = ((int) $value) === 1 ? 'true' : 'false';
                }
                $values[] = $value;
            }
            $insert->execute($values);
            $count++;
        }

        $summary[$table] = $count;
    }

    $sequences = $target->query(
        "SELECT table_name, column_name,
                pg_get_serial_sequence(format('%I.%I', table_schema, table_name), column_name) AS sequence_name
         FROM information_schema.columns
         WHERE table_schema = 'public'
           AND pg_get_serial_sequence(format('%I.%I', table_schema, table_name), column_name) IS NOT NULL"
    )->fetchAll();
    foreach ($sequences as $sequence) {
        $table = quoteIdentifier($sequence['table_name']);
        $column = quoteIdentifier($sequence['column_name']);
        $sequenceName = $target->quote($sequence['sequence_name']);
        $target->exec(
            "SELECT setval({$sequenceName}, COALESCE((SELECT MAX({$column}) FROM {$table}), 1),
                EXISTS(SELECT 1 FROM {$table}))"
        );
    }

    $target->exec("SET LOCAL session_replication_role = 'origin'");
    $target->commit();

    foreach ($summary as $table => $sourceCount) {
        $targetCount = (int) $target->query('SELECT COUNT(*) FROM '.quoteIdentifier($table))->fetchColumn();
        if ($targetCount !== $sourceCount) {
            throw new RuntimeException("Row count mismatch for {$table}: {$sourceCount} != {$targetCount}");
        }
    }

    printf(
        "Transferred %d tables and %d rows from SQLite to PostgreSQL.\n",
        count($summary),
        array_sum($summary),
    );
} catch (Throwable $exception) {
    if ($target->inTransaction()) {
        $target->rollBack();
    }
    throw $exception;
}
