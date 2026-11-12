import hashlib
import json
import re
import sys
from collections import Counter, defaultdict
from pathlib import Path

import openpyxl


def norm(value):
    if value is None:
        return None
    text = re.sub(r"\s+", " ", str(value).replace("\n", " ")).strip()
    return text or None


def table(workbook, sheet_name, first_header):
    sheet = workbook[sheet_name]
    headers = None
    rows = []
    for row in sheet.iter_rows(values_only=True):
        values = [norm(value) for value in row]
        if headers is None and first_header in values:
            headers = values
            continue
        if headers and any(values):
            rows.append(
                {
                    headers[index]: values[index] if index < len(values) else None
                    for index in range(len(headers))
                    if headers[index]
                }
            )
    return headers or [], rows


def slug(value):
    text = norm(value) or "item"
    text = text.lower()
    text = re.sub(r"[^a-z0-9]+", "-", text)
    return text.strip("-") or "item"


def to_int(value, default=0):
    try:
        return int(float(str(value).replace(",", ".")))
    except Exception:
        return default


def split_codes(value):
    text = norm(value)
    if not text or text == "-":
        return []
    return re.findall(r"[A-Z]\d{8}", text)


def main():
    if len(sys.argv) < 2:
        raise SystemExit("Path argument is required")

    path = Path(sys.argv[1])
    workbook = openpyxl.load_workbook(path, data_only=True, read_only=True)

    course_headers, course_rows = table(workbook, "Daftar MK - Flat", "Kode Mata Kuliah")
    _, profile_rows = table(workbook, "Profil Lulusan", "Kode PL")
    _, specialization_rows = table(workbook, "Peminatan Mahasiswa", "Kode Peminatan")
    _, prerequisite_rows = table(workbook, "Prasyarat", "Kode MK")
    _, semester_summary_rows = table(workbook, "Ringkasan Semester MK", "Semester")

    semester_packages = {}
    for row in semester_summary_rows:
        semester = to_int(row.get("Semester"))
        if semester:
            semester_packages[semester] = to_int(row.get("Paket SKS Mahasiswa"))

    specializations = []
    specialization_by_code = {}
    for index, row in enumerate(specialization_rows, 1):
        code = norm(row.get("Kode Peminatan"))
        name = norm(row.get("Nama Peminatan"))
        if not code or not name:
            continue
        english = {
            "PM01": "Software Engineering and Intelligent Applications",
            "PM02": "Data and Artificial Intelligence",
            "PM03": "Cybersecurity",
        }.get(code)
        record = {
            "code": code,
            "name_id": name,
            "name_en": english,
            "slug_id": slug(name),
            "slug_en": slug(english or name),
            "description_id": norm(row.get("Fokus Pendalaman")),
            "description_en": None,
            "competencies_id": norm(row.get("Fokus Pendalaman")),
            "career_id": norm(row.get("Profil Lulusan Didukung")),
            "icon": {"PM01": "Code2", "PM02": "Database", "PM03": "ShieldCheck"}.get(code, "Network"),
            "sort_order": index,
        }
        specializations.append(record)
        specialization_by_code[code] = record

    courses = []
    codes = Counter()
    names = defaultdict(list)
    semester_totals = defaultdict(
        lambda: {
            "course_count": 0,
            "offered_credits": 0,
            "mandatory_credits": 0,
            "elective_credits": 0,
            "student_package_credits": 0,
        }
    )
    warnings = []
    errors = []

    for index, row in enumerate(course_rows, 1):
        code = norm(row.get("Kode Mata Kuliah"))
        name = norm(row.get("Nama Mata Kuliah"))
        semester = to_int(row.get("Semester"))
        credits = to_int(row.get("SKS"))
        status = norm(row.get("Status")) or ""
        specialization_code = norm(row.get("Peminatan"))
        specialization_code = None if specialization_code == "-" else specialization_code
        course_type = "mandatory" if status.lower().startswith("wajib") else "elective"

        if not code:
            errors.append(f"Row {index}: kode mata kuliah kosong.")
            continue
        if not name:
            errors.append(f"{code}: nama mata kuliah kosong.")
        if semester < 1 or semester > 8:
            errors.append(f"{code}: semester {semester} tidak valid.")
        if credits <= 0:
            errors.append(f"{code}: SKS {credits} tidak valid.")
        if specialization_code and specialization_code not in specialization_by_code:
            warnings.append(f"{code}: peminatan {specialization_code} belum dikenali.")

        codes[code] += 1
        names[name].append(code)
        semester_totals[semester]["course_count"] += 1
        semester_totals[semester]["offered_credits"] += credits
        if course_type == "mandatory":
            semester_totals[semester]["mandatory_credits"] += credits
        else:
            semester_totals[semester]["elective_credits"] += credits

        courses.append(
            {
                "code": code,
                "name_id": name,
                "name_en": None,
                "slug_id": slug(f"{code}-{name}"),
                "slug_en": slug(f"{code}-{name}"),
                "semester": semester,
                "credits": credits,
                "course_type": course_type,
                "category": norm(row.get("Kelompok Mata Kuliah")),
                "specialization_code": specialization_code,
                "description_id": norm(row.get("Catatan Keterkaitan")),
                "description_en": None,
                "learning_materials_id": norm(row.get("Penciri Prodi")),
                "learning_materials_en": None,
                "software_tools_id": None,
                "software_tools_en": None,
                "metadata": {
                    "status_label": status,
                    "learning_mode": norm(row.get("Bentuk Pembelajaran")),
                    "cpl": norm(row.get("CPL Didukung")),
                    "prerequisite_text": norm(row.get("Prasyarat")),
                },
                "prerequisite_codes": split_codes(row.get("Prasyarat")),
                "sort_order": index,
            }
        )

    for semester, package in semester_packages.items():
        semester_totals[semester]["student_package_credits"] = package
    for semester in range(1, 9):
        if not semester_totals[semester]["course_count"]:
            warnings.append(f"Semester {semester}: belum ada mata kuliah.")

    for code, count in codes.items():
        if count > 1:
            errors.append(f"Kode mata kuliah duplikat: {code} ({count} baris).")
    for name, name_codes in names.items():
        if len(set(name_codes)) > 1:
            warnings.append(f"Nama mata kuliah sama dengan kode berbeda: {name}.")

    code_set = set(codes.keys())
    for course in courses:
        for prerequisite_code in course["prerequisite_codes"]:
            if prerequisite_code == course["code"]:
                errors.append(f"{course['code']}: prasyarat menunjuk diri sendiri.")
            if prerequisite_code not in code_set:
                warnings.append(f"{course['code']}: prasyarat {prerequisite_code} belum cocok dengan kode MK.")

    profiles = []
    for index, row in enumerate(profile_rows, 1):
        code = norm(row.get("Kode PL"))
        title = norm(row.get("Profil Lulusan"))
        if not code or not title:
            continue
        profiles.append(
            {
                "code": code,
                "title_id": title,
                "title_en": title,
                "description_id": norm(row.get("Deskripsi")),
                "career_examples_id": norm(row.get("Ruang Lingkup Kerja dan Okupasi Potensial")),
                "career_examples_en": None,
                "icon": ["Code2", "BrainCircuit", "ShieldCheck", "Lightbulb"][min(index - 1, 3)],
                "sort_order": index,
            }
        )

    summary = {
        "path": str(path),
        "sha256": hashlib.sha256(path.read_bytes()).hexdigest(),
        "sheet_count": len(workbook.sheetnames),
        "sheets": workbook.sheetnames,
        "sheets_processed": [
            "Daftar MK - Flat",
            "Ringkasan Semester MK",
            "Peminatan Mahasiswa",
            "Profil Lulusan",
            "Prasyarat",
        ],
        "sheets_ignored": [sheet for sheet in workbook.sheetnames if sheet not in {"Daftar MK - Flat", "Ringkasan Semester MK", "Peminatan Mahasiswa", "Profil Lulusan", "Prasyarat"}],
        "course_headers": course_headers,
        "course_count": len(courses),
        "semester_totals": dict(sorted(semester_totals.items())),
        "student_total_credits": sum(item["student_package_credits"] for item in semester_totals.values()),
        "offered_total_credits": sum(item["offered_credits"] for item in semester_totals.values()),
        "specializations_count": len(specializations),
        "profiles_count": len(profiles),
        "prerequisite_rows": len(prerequisite_rows),
        "duplicate_codes": {code: count for code, count in codes.items() if count > 1},
        "duplicate_names": {name: values for name, values in names.items() if len(set(values)) > 1},
        "warnings": warnings,
        "errors": errors,
    }

    print(
        json.dumps(
            {
                "curriculum": {
                    "code": "OBE-2026-S1-INF",
                    "name_id": "Kurikulum OBE 2026 S1 Informatika",
                    "name_en": "OBE Curriculum 2026 Bachelor of Informatics",
                    "slug_id": "kurikulum-obe-2026",
                    "slug_en": "obe-curriculum-2026",
                    "year": 2026,
                    "total_credits": summary["student_total_credits"],
                    "total_semesters": 8,
                    "description_id": "Kurikulum Outcome-Based Education 2026 Program Studi Informatika UIN Sultan Maulana Hasanuddin Banten.",
                    "description_en": "The 2026 Outcome-Based Education curriculum for the Informatics Study Program at UIN Sultan Maulana Hasanuddin Banten.",
                },
                "specializations": specializations,
                "profiles": profiles,
                "courses": courses,
                "summary": summary,
            },
            ensure_ascii=False,
        )
    )


if __name__ == "__main__":
    main()
