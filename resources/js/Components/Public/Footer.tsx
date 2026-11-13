import { NavbarBrand } from '@/Components/Layout/NavbarBrand';
import { Locale, QuickLink } from '@/types/homepage';
import { ExternalLink } from 'lucide-react';
import { ReactNode } from 'react';

export function Footer({
    locale,
    quickLinks,
    links,
    contact,
}: {
    locale: Locale;
    quickLinks: QuickLink[];
    links: Record<string, string>;
    contact: Record<string, string>;
}) {
    const year = new Date().getFullYear();
    const isEnglish = locale === 'en';

    return (
        <footer className="public-footer border-t border-brand-silver bg-white py-12 dark:border-slate-700 dark:bg-[#1c2128]">
            <div className="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.4fr_.8fr_.8fr_1fr]">
                <div>
                    <NavbarBrand />
                    <p className="mt-4 max-w-sm text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {isEnglish
                            ? 'Informatics Study Program, Faculty of Science and Technology, Sultan Maulana Hasanuddin State Islamic University Banten.'
                            : 'Program Studi Informatika, Fakultas Sains dan Teknologi, UIN Sultan Maulana Hasanuddin Banten.'}
                    </p>
                </div>
                <FooterColumn title={isEnglish ? 'Quick Navigation' : 'Navigasi Cepat'}>
                    {quickLinks.slice(0, 4).map((link) => (
                        <FooterLink
                            key={link.key}
                            href={localizeUrl(link.url, locale)}
                            external={link.isExternal}
                        >
                            {isEnglish ? link.label_en : link.label_id}
                        </FooterLink>
                    ))}
                </FooterColumn>
                <FooterColumn title={isEnglish ? 'Services' : 'Layanan'}>
                    <FooterLink href={links.uin ?? '#'} external>
                        UIN
                    </FooterLink>
                    <FooterLink href={links.faculty ?? '#'} external>
                        FST
                    </FooterLink>
                    <FooterLink href={links.hmps ?? '#'} external>
                        HMPS
                    </FooterLink>
                    <FooterLink href={`/${locale}/privacy`}>Privacy</FooterLink>
                    <FooterLink href={`/${locale}/terms`}>Terms</FooterLink>
                </FooterColumn>
                <FooterColumn title={isEnglish ? 'Contact' : 'Kontak'}>
                    <FooterLink href="mailto:informatika@uinbanten.ac.id">
                        informatika@uinbanten.ac.id
                    </FooterLink>
                    <p className="mt-3 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {isEnglish ? contact.address_en : contact.address_id}
                    </p>
                    <p className="mt-2 text-sm leading-6 text-brand-graphite dark:text-slate-300">
                        {isEnglish ? contact.service_hours_en : contact.service_hours_id}
                    </p>
                </FooterColumn>
            </div>
            <div className="mx-auto mt-10 max-w-7xl border-t border-brand-silver px-4 pt-6 text-sm text-brand-graphite dark:border-slate-700 dark:text-slate-300 sm:px-6">
                © {year}{' '}
                {isEnglish
                    ? 'Informatics Study Program. All rights reserved.'
                    : 'Program Studi Informatika. Seluruh hak dilindungi.'}
            </div>
        </footer>
    );
}

function FooterColumn({ title, children }: { title: string; children: ReactNode }) {
    return (
        <div>
            <h2 className="text-sm font-bold text-brand-charcoal dark:text-white">{title}</h2>
            <div className="mt-4 grid gap-2">{children}</div>
        </div>
    );
}

function FooterLink({
    href,
    external = false,
    children,
}: {
    href: string;
    external?: boolean;
    children: ReactNode;
}) {
    return (
        <a
            href={href}
            target={external ? '_blank' : undefined}
            rel={external ? 'noopener noreferrer' : undefined}
            className="inline-flex items-center gap-1 text-sm text-brand-graphite hover:text-brand-deep dark:text-slate-300 dark:hover:text-white"
        >
            {children}
            {external && <ExternalLink size={12} aria-hidden />}
        </a>
    );
}

function localizeUrl(url: string, locale: Locale) {
    if (url.startsWith('/id/')) return locale === 'en' ? url.replace('/id/', '/en/') : url;
    return url;
}
