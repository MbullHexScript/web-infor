export type Locale = 'id' | 'en';

export type Seo = {
    title: string;
    description: string;
};

export type Paginator<T> = {
    data: T[];
    links: { url: string | null; label: string; active: boolean }[];
};

export type Achievement = {
    id: number;
    title: string;
    slug: string;
    type: string;
    recipient: string | null;
    level: string | null;
    organizer: string | null;
    year: number | null;
    eventDate: string | null;
    location: string | null;
    award: string | null;
    description: string | null;
    certificateUrl?: string | null;
    sourceUrl?: string | null;
    url: string;
};

export type Scholarship = {
    id: number;
    name: string;
    provider: string | null;
    description: string | null;
    requirements?: string | null;
    benefits?: string | null;
    status: string;
    startsAt: string | null;
    endsAt: string | null;
    registrationUrl: string | null;
    contactInfo?: string | null;
    url: string;
};

export type Competition = {
    id: number;
    title: string;
    description: string | null;
    organizer: string | null;
    level: string | null;
    type: string | null;
    status: string;
    startsAt: string | null;
    endsAt: string | null;
    eventDate: string | null;
    location: string | null;
    registrationUrl: string | null;
    poster?: string | null;
    url: string;
};

export type Alumni = {
    id: number;
    name: string;
    slug: string;
    graduationYear: number | null;
    position: string | null;
    company: string | null;
    industry: string | null;
    story?: string | null;
    quote: string | null;
    linkedinUrl: string | null;
    personalWebsiteUrl: string | null;
    url: string;
};

export type Facility = {
    id: number;
    name: string;
    category: string | null;
    description: string | null;
    location: string | null;
    capacity: number | null;
    features: string[];
    url: string;
};

export type AdmissionPath = {
    id: number;
    name: string;
    description: string | null;
    requirements: string | null;
    status: string;
    registrationUrl: string | null;
    startsAt: string | null;
    endsAt: string | null;
};

export type Faq = {
    question: string;
    answer: string;
};
