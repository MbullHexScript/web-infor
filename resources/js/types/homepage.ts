export type Locale = 'id' | 'en';

export type LocalizedRecord = {
    [key: string]: unknown;
};

export type SectionSetting = {
    key: string;
    title: string;
    subtitle?: string | null;
    backgroundStyle: string;
    sortOrder: number;
};

export type TopLink = {
    label: string;
    url: string;
    external: boolean;
};

export type QuickLink = {
    key: string;
    label_id: string;
    label_en: string;
    url: string;
    icon?: string | null;
    isExternal: boolean;
    sortOrder: number;
};

export type Announcement = {
    title_id: string;
    title_en?: string | null;
    date: string;
    priority: 'urgent' | 'important' | 'normal';
    featured: boolean;
    url: string;
};

export type Statistic = {
    key: string;
    label_id: string;
    label_en?: string | null;
    value: string | null;
    suffix?: string | null;
};

export type StudyField = {
    key: string;
    name_id: string;
    name_en: string;
    description_id: string;
    description_en: string;
    icon: string;
    is_active: boolean;
    sort_order: number;
};

export type Journal = {
    name: string;
    slug: string;
    fullName?: string | null;
    tagline?: string | null;
    scope_id?: string | null;
    scope_en?: string | null;
    statusLabel: string;
    ojsUrl?: string | null;
};

export type Lecturer = {
    name: string;
    degree: string;
    expertise_id: string;
    expertise_en: string;
    position_id?: string | null;
    position_en?: string | null;
    photo?: string | null;
    slug?: string | null;
};

export type HomeContent = {
    headMessage: {
        name: string;
        degree: string;
        position_id: string;
        position_en: string;
        photo?: string | null;
        quote_id: string;
        quote_en: string;
    };
    news: Array<LocalizedRecord>;
    events: Array<LocalizedRecord>;
    lecturers: Lecturer[];
    research: Array<LocalizedRecord>;
    achievements: Array<LocalizedRecord>;
    facilities: Array<LocalizedRecord>;
};

export type HomePageProps = {
    locale: Locale;
    identity: Record<string, string>;
    links: Record<string, string>;
    contact: Record<string, string>;
    hero: Record<string, string>;
    sections: SectionSetting[];
    topLinks: TopLink[];
    quickLinks: QuickLink[];
    announcements: Announcement[];
    statistics: Statistic[];
    studyFields: StudyField[];
    curriculum: {
        total_sks: number;
        semesters: number;
        source: string;
        specializations: Array<{
            name_id: string;
            name_en: string;
        }>;
    };
    journals: Journal[];
    content: HomeContent;
    seo: {
        title: string;
        description: string;
        canonical: string;
        alternateId: string;
        alternateEn: string;
        ogImage: string;
    };
};
