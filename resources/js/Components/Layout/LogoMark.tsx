import { cn } from '@/Lib/cn';

type LogoMarkProps = {
    label: string;
    tone: 'uin' | 'informatika';
    className?: string;
    compact?: boolean;
};

export function LogoMark({ label, tone, className, compact = false }: LogoMarkProps) {
    const src =
        tone === 'uin'
            ? '/assets/brand/logo-uin-smh-banten-512.webp'
            : '/assets/brand/logo-informatika-official-256.png';

    return (
        <div
            className={cn(
                'flex items-center justify-center overflow-hidden rounded-xl border bg-white shadow-sm transition-[height,width]',
                compact ? 'h-10 w-10' : 'h-12 w-12',
                tone === 'uin'
                    ? 'border-brand-yellow/60 p-1.5'
                    : 'border-brand-red/30 rounded-full p-0.5',
                className,
            )}
            title={label}
        >
            <img
                src={src}
                alt={label}
                className="h-full w-full object-contain"
                width="48"
                height="48"
            />
        </div>
    );
}
