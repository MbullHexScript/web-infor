import { Locale } from '@/types/homepage';

export type AcademicPageProps = {
    locale: Locale;
    seo?: { title: string; description: string };
};

export type Curriculum = {
    id: number;
    code: string;
    name_id: string;
    name_en: string;
    year: number;
    total_credits: number;
    total_semesters: number;
    description_id?: string;
    description_en?: string;
};

export type SemesterTotal = {
    course_count: number;
    mandatory_credits: number;
    elective_credits: number;
    student_package_credits: number;
};

export type Course = {
    id: number;
    code: string;
    name_id: string;
    name_en?: string | null;
    slug_id: string;
    slug_en: string;
    semester: number;
    credits: number;
    course_type: 'mandatory' | 'elective';
    category?: string | null;
    description_id?: string | null;
    description_en?: string | null;
    learning_materials_id?: string | null;
    software_tools_id?: string | null;
    specialization_name_id?: string | null;
    specialization_name_en?: string | null;
    metadata?: Record<string, unknown>;
    prerequisites?: Course[];
    dependents?: Course[];
    rps?: RpsDocument | null;
};

export type Specialization = {
    id: number;
    code: string;
    name_id: string;
    name_en?: string | null;
    slug_id: string;
    slug_en: string;
    description_id?: string | null;
    description_en?: string | null;
    competencies_id?: string | null;
    career_id?: string | null;
    icon?: string | null;
    course_count?: number;
};

export type GraduateProfile = {
    id: number;
    code: string;
    title_id: string;
    title_en?: string | null;
    description_id?: string | null;
    career_examples_id?: string | null;
    icon?: string | null;
};

export type RpsDocument = {
    id: number;
    title_id: string;
    title_en?: string | null;
    version?: string | null;
    academic_year?: string | null;
    file_size?: number | null;
    rps_id?: number | null;
    rps_updated_at?: string | null;
};
