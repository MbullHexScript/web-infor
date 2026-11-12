import { Locale } from '@/types/homepage';
import { Paginated, Seo } from '@/types/information';

export type ResearchBase = {
    id: number;
    title: string;
    slug?: string;
    summary?: string | null;
    year?: number | null;
    status?: string | null;
    lead?: string | null;
    url?: string;
};

export type ResearchProject = ResearchBase & {
    team?: Array<{ name: string; role?: string }>;
    group?: string | null;
    output?: string | null;
    externalUrl?: string | null;
};

export type CommunityService = ResearchBase & {
    location?: string | null;
    beneficiaries?: string | null;
    outputs?: string | null;
};

export type Publication = {
    id: number;
    title: string;
    authors?: string | null;
    type: string;
    venue?: string | null;
    publisher?: string | null;
    year?: number | null;
    doi?: string | null;
    url?: string | null;
    bibtexUrl: string;
    risUrl: string;
};

export type Product = ResearchBase & {
    type: string;
    technologies: string[];
    demoUrl?: string | null;
    repositoryUrl?: string | null;
    documentationUrl?: string | null;
};

export type IntellectualProperty = {
    id: number;
    title: string;
    type: string;
    registrationNumber?: string | null;
    holder?: string | null;
    inventors?: string | null;
    year?: string | null;
    status?: string | null;
    description?: string | null;
};

export type ExpertiseGroup = {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    focus?: string | null;
    icon?: string | null;
    members?: Array<{ name: string; role?: string | null }>;
    url: string;
};

export type Journal = {
    id: number;
    code: string;
    name: string;
    fullName?: string | null;
    slug: string;
    tagline?: string | null;
    description?: string | null;
    scope: string[];
    publisher?: string | null;
    status: string;
    verificationStatus: string;
    printIssn?: string | null;
    electronicIssn?: string | null;
    editor?: string | null;
    ojsUrl?: string | null;
    authorGuidelinesUrl?: string | null;
    articleTemplateUrl?: string | null;
    editorialEmail?: string | null;
    accentStyle: string;
    url: string;
};

export type ResearchPageProps = {
    locale: Locale;
    seo: Seo;
};

export type ResearchPaginatedProps<T> = ResearchPageProps & {
    filters: Record<string, string>;
    items: Paginated<T>;
};
