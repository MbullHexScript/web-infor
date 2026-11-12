import {
    AdmissionFaq,
    ContactForm,
    ContactInfoCard,
    MapEmbed,
    SectionBand,
    StudentLifeHero,
    StudentLifeShell,
} from '@/Components/StudentLife/StudentLifeComponents';
import { Faq, Locale, Seo } from '@/types/student-life';

export default function ContactPage({
    locale,
    seo,
    contact,
    faqs,
}: {
    locale: Locale;
    seo: Seo;
    contact: Record<string, string | null>;
    faqs: Faq[];
}) {
    return (
        <StudentLifeShell locale={locale} seo={seo}>
            <StudentLifeHero
                locale={locale}
                title={locale === 'en' ? 'Contact' : 'Kontak'}
                description={
                    locale === 'en'
                        ? 'Reach the Informatics Study Program through verified public channels.'
                        : 'Hubungi Program Studi Informatika melalui kanal publik yang terverifikasi.'
                }
            />
            <SectionBand>
                <div className="grid gap-6 lg:grid-cols-[.85fr_1.15fr]">
                    <div className="grid gap-5">
                        <ContactInfoCard contact={contact} locale={locale} />
                        <MapEmbed contact={contact} locale={locale} />
                    </div>
                    <ContactForm locale={locale} />
                </div>
            </SectionBand>
            <SectionBand>
                <h2 className="text-2xl font-black">FAQ</h2>
                <div className="mt-5">
                    <AdmissionFaq items={faqs} />
                </div>
            </SectionBand>
        </StudentLifeShell>
    );
}
