import { Locale } from '@/types/homepage';

export type Seo = {
    title: string;
    description: string;
};

export type Paginated<T> = {
    data: T[];
    links?: Array<{ url: string | null; label: string; active: boolean }>;
    current_page?: number;
    last_page?: number;
};

export type NewsItem = {
    id: number;
    title: string;
    slug: string;
    excerpt?: string | null;
    content?: string | null;
    category?: string | null;
    publishedAt?: string | null;
    author?: string | null;
    sourceName?: string | null;
    sourceUrl?: string | null;
    featuredImage?: string | null;
    isFeatured?: boolean;
    readingTime?: number;
    viewCount?: number;
    url: string;
};

export type AnnouncementItem = {
    id: number;
    title: string;
    slug: string;
    excerpt?: string | null;
    content?: string | null;
    priority: 'normal' | 'important' | 'urgent';
    startsAt?: string | null;
    expiresAt?: string | null;
    publishedAt?: string | null;
    expired: boolean;
    attachmentUrl?: string | null;
    externalUrl?: string | null;
    url: string;
};

export type EventItem = {
    id: number;
    title: string;
    slug: string;
    description?: string | null;
    startAt: string;
    endAt: string;
    timezone: string;
    location?: string | null;
    organizer?: string | null;
    poster?: string | null;
    registrationUrl?: string | null;
    meetingUrl?: string | null;
    status: 'upcoming' | 'ongoing' | 'completed' | 'cancelled' | string;
    icsUrl: string;
    googleCalendarUrl: string;
    url: string;
};

export type AlbumItem = {
    id: number;
    title: string;
    slug: string;
    description?: string | null;
    cover: string;
    eventDate?: string | null;
    category?: string | null;
    mediaCount: number;
    url: string;
};

export type MediaItem = {
    id: number;
    type: 'image' | 'video';
    url: string;
    alt?: string | null;
    caption?: string | null;
    provider?: string | null;
};

export type DocumentItem = {
    id: number;
    title: string;
    description?: string | null;
    category?: string | null;
    year?: number | null;
    version?: string | null;
    effectiveDate?: string | null;
    status: 'current' | 'archived' | 'expired' | string;
    fileType?: string | null;
    fileSize?: number | null;
    downloadCount: number;
    downloadUrl: string;
};

export type FaqItem = {
    id: number;
    category?: string | null;
    question: string;
    answer: string;
    featured: boolean;
};

export type SearchResult = {
    type: string;
    title: string;
    excerpt?: string | null;
    url: string;
    metadata?: Record<string, string>;
};

export type InformationPageProps = {
    locale: Locale;
    seo: Seo;
};
