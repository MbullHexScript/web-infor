<?php

return [
    'curriculum_import_path' => env(
        'CURRICULUM_IMPORT_PATH',
        storage_path('app/imports/curriculum/Draf_Kurikulum_OBE_2026_S1_Informatika_UIN_SMH_Banten Rev.1.xlsx'),
    ),
    'python_binary' => env('CURRICULUM_PYTHON_BINARY'),
];
