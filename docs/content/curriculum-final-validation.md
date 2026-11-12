# Curriculum Final Validation

Date: 2026-07-12

Command:

```bash
php artisan curriculum:validate
```

Expected checks:

- OBE Curriculum 2026 is active.
- Total package credits are 144 SKS.
- Semester 1-8 are available.
- Semester 1 is 20 SKS.
- Semester 2 is 20 SKS.
- Course codes are unique.
- No duplicate course records.
- No invalid semester or credit value.
- Prerequisites are valid.
- No self-prerequisite.
- Three specializations are available.
- Graduate profiles match the OBE 2026 workbook.

Current known limitation:

- English course names and descriptions are still incomplete and must be verified before production.
