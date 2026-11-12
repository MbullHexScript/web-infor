import { Locale } from '@/types/homepage';

export type ProfilePageProps = {
    locale: Locale;
    seo: { title: string; description: string };
};

export type ProfileContent = {
    key: string;
    title_id: string;
    title_en?: string | null;
    excerpt_id?: string | null;
    excerpt_en?: string | null;
    content_id?: string | null;
    content_en?: string | null;
    verification_status?: string;
};

export type LecturerProfile = {
    id: number;
    full_name: string;
    academic_title?: string | null;
    slug: string;
    academic_position_id?: string | null;
    academic_position_en?: string | null;
    expertise_id?: string | null;
    expertise_en?: string | null;
    biography_id?: string | null;
    biography_en?: string | null;
    education_id?: string | null;
    email?: string | null;
    photo_id?: string | null;
    photo_alt?: string | null;
    google_scholar_url?: string | null;
    sinta_url?: string | null;
    orcid_url?: string | null;
    scopus_url?: string | null;
    personal_website_url?: string | null;
    github_url?: string | null;
    verification_status?: string;
};

export type ManagementPosition = {
    id: number;
    position_id: string;
    position_en?: string | null;
    person_name?: string | null;
    full_name?: string | null;
    academic_title?: string | null;
    lecturer_slug?: string | null;
    photo_id?: string | null;
};

export type Partner = {
    id: number;
    name: string;
    partner_type?: string | null;
    scope_id?: string | null;
    scope_en?: string | null;
    website_url?: string | null;
    verification_status?: string;
};
