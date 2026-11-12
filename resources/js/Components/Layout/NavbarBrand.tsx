import { LogoMark } from '@/Components/Layout/LogoMark';
import { Link } from '@inertiajs/react';

export function NavbarBrand({ compact = false }: { compact?: boolean }) {
    return (
        <Link
            href="/id"
            className="flex min-w-0 items-center gap-3"
            aria-label="Beranda Informatika"
        >
            <div className="flex shrink-0 items-center gap-2">
                <LogoMark
                    label="Logo UIN Sultan Maulana Hasanuddin Banten"
                    tone="uin"
                    compact={compact}
                />
                <LogoMark
                    label="Logo Program Studi Informatika"
                    tone="informatika"
                    compact={compact}
                />
            </div>
            <div className="hidden min-w-0 sm:block">
                <div className="font-heading text-sm font-black tracking-normal text-brand-charcoal dark:text-white">
                    INFORMATIKA
                </div>
                <div className="max-w-[17rem] truncate text-[11px] font-semibold text-brand-graphite dark:text-slate-300">
                    Fakultas Sains dan Teknologi
                </div>
                <div className="max-w-[17rem] truncate text-[11px] text-brand-graphite dark:text-slate-300">
                    UIN Sultan Maulana Hasanuddin Banten
                </div>
            </div>
        </Link>
    );
}
