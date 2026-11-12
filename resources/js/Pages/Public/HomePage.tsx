import { AboutSection } from '@/Components/Public/AboutSection';
import { AdmissionCtaSection } from '@/Components/Public/AdmissionCtaSection';
import { AnnouncementStrip } from '@/Components/Public/AnnouncementStrip';
import { Footer } from '@/Components/Public/Footer';
import { HeadMessageSection } from '@/Components/Public/HeadMessageSection';
import { HomeHero } from '@/Components/Public/HomeHero';
import { Navbar } from '@/Components/Public/Navbar';
import { NewsInformationSection } from '@/Components/Public/NewsInformationSection';
import { QuickLinksGrid } from '@/Components/Public/QuickLinksGrid';
import { Reveal } from '@/Components/Public/Reveal';
import { SectionHeader } from '@/Components/Public/SectionHeader';
import { TopBar } from '@/Components/Public/TopBar';
import { HomePageProps, SectionSetting } from '@/types/homepage';
import { Head } from '@inertiajs/react';
import { ReactNode } from 'react';

export default function HomePage(props: HomePageProps) {
    const sectionMap = new Map(props.sections.map((section) => [section.key, section]));

    return (
        <div className="min-h-screen overflow-x-hidden bg-brand-soft text-brand-charcoal dark:bg-[#11151a] dark:text-white">
            <Head title={props.seo.title}>
                <meta name="description" content={props.seo.description} />
                <link rel="canonical" href={props.seo.canonical} />
                <link rel="alternate" hrefLang="id" href={props.seo.alternateId} />
                <link rel="alternate" hrefLang="en" href={props.seo.alternateEn} />
                <meta property="og:title" content={props.seo.title} />
                <meta property="og:description" content={props.seo.description} />
                <meta property="og:image" content={props.seo.ogImage} />
                <meta name="twitter:card" content="summary_large_image" />
                <script type="application/ld+json">
                    {JSON.stringify({
                        '@context': 'https://schema.org',
                        '@type': 'EducationalOrganization',
                        name:
                            props.locale === 'id' ? props.identity.name_id : props.identity.name_en,
                        url: props.identity.domain,
                        email: props.identity.email,
                    })}
                </script>
            </Head>
            <a
                href="#content"
                className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-brand-deep"
            >
                {props.locale === 'id' ? 'Lewati ke konten utama' : 'Skip to main content'}
            </a>
            <TopBar links={props.topLinks} />
            <Navbar locale={props.locale} />
            <main id="content">
                <HomeHero hero={props.hero} locale={props.locale} />
                {sectionMap.has('announcements') && props.announcements.length > 0 && (
                    <AnnouncementStrip announcements={props.announcements} locale={props.locale} />
                )}
                <PageSection setting={sectionMap.get('quick_links')}>
                    <QuickLinksGrid links={props.quickLinks} locale={props.locale} />
                </PageSection>
                <PageSection setting={sectionMap.get('head_message')}>
                    <HeadMessageSection message={props.content.headMessage} locale={props.locale} />
                </PageSection>
                <PageSection setting={sectionMap.get('about')}>
                    <AboutSection locale={props.locale} />
                </PageSection>
                <PageSection setting={sectionMap.get('news_information')}>
                    <NewsInformationSection
                        locale={props.locale}
                        news={props.content.news}
                        announcements={props.announcements}
                        events={props.content.events}
                    />
                </PageSection>
                <PageSection setting={sectionMap.get('admission_cta')}>
                    <AdmissionCtaSection locale={props.locale} pmbUrl={props.links.pmb ?? '#'} />
                </PageSection>
            </main>
            <Footer
                locale={props.locale}
                quickLinks={props.quickLinks}
                links={props.links}
                contact={props.contact}
            />
        </div>
    );
}

function PageSection({ setting, children }: { setting?: SectionSetting; children: ReactNode }) {
    if (!setting) return null;

    const isQuickLinks = setting.key === 'quick_links';
    const isAbout = setting.key === 'about';

    if (isQuickLinks) {
        return (
            <section className="pb-18 relative overflow-hidden bg-[#f8f9fb] px-4 pt-4 sm:px-6 lg:pb-20 lg:pt-6">
                <div className="bg-brand-lavender/12 absolute inset-x-0 top-0 h-20 blur-3xl" />
                <div className="relative mx-auto max-w-7xl">
                    <Reveal>
                        <div className="mx-auto max-w-3xl text-center">
                            <div className="mx-auto mb-3 flex w-fit items-center gap-3">
                                <span className="h-px w-12 bg-brand-yellow" />
                                <span className="h-2 w-2 rounded-full bg-brand-yellow shadow-[0_0_18px_rgba(251,195,31,.75)] motion-safe:animate-[quick-pulse_2.8s_ease-in-out_infinite]" />
                                <span className="h-px w-12 bg-brand-yellow" />
                            </div>
                            <h2 className="font-heading text-3xl font-bold tracking-normal text-brand-charcoal md:text-4xl">
                                {setting.title}
                            </h2>
                            {setting.subtitle && (
                                <p className="text-brand-muted mx-auto mt-3 max-w-2xl text-base leading-7">
                                    {setting.subtitle}
                                </p>
                            )}
                        </div>
                    </Reveal>
                    <Reveal delay={120}>
                        <div className="mt-6">{children}</div>
                    </Reveal>
                </div>
            </section>
        );
    }

    if (isAbout) {
        return (
            <section className="relative overflow-hidden bg-[linear-gradient(180deg,#f8f9fb_0%,#fbf9ff_100%)] px-4 py-16 sm:px-6 lg:py-20">
                <div className="bg-brand-lavender/20 absolute -left-24 top-20 h-64 w-64 rounded-full blur-3xl" />
                <div className="relative mx-auto max-w-7xl">
                    <Reveal>
                        <SectionHeader title={setting.title} subtitle={setting.subtitle} />
                    </Reveal>
                    <Reveal delay={120}>
                        <div className="mt-8">{children}</div>
                    </Reveal>
                </div>
            </section>
        );
    }

    return (
        <section className="lg:py-18 mx-auto max-w-7xl px-4 py-14 sm:px-6">
            <Reveal>
                <SectionHeader title={setting.title} subtitle={setting.subtitle} />
            </Reveal>
            <Reveal delay={120}>
                <div className="mt-8">{children}</div>
            </Reveal>
        </section>
    );
}
