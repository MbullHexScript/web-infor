import {
    ArticleContent,
    InformationHero,
    InformationShell,
    NewsGrid,
    SectionBand,
} from '@/Components/Information/InformationComponents';
import { InformationPageProps, NewsItem } from '@/types/information';

export default function NewsDetail({
    locale,
    seo,
    article,
    related,
}: InformationPageProps & {
    article: NewsItem;
    related: NewsItem[];
}) {
    return (
        <InformationShell locale={locale} seo={seo}>
            <InformationHero
                locale={locale}
                title={article.title}
                description={article.excerpt ?? undefined}
                eyebrow={article.category ?? (locale === 'en' ? 'News' : 'Berita')}
            />
            <SectionBand title={locale === 'en' ? 'Article' : 'Artikel'}>
                <div className="text-sm font-bold text-brand-graphite dark:text-slate-300">
                    {article.author} | {article.readingTime} min | {article.viewCount} views
                </div>
                <div className="mt-6">
                    <ArticleContent html={article.content} />
                </div>
            </SectionBand>
            <SectionBand title={locale === 'en' ? 'Related News' : 'Berita Terkait'}>
                <NewsGrid items={related} locale={locale} />
            </SectionBand>
        </InformationShell>
    );
}
