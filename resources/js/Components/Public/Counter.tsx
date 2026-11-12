import { useEffect, useRef, useState } from 'react';

export function Counter({ value, suffix = '' }: { value: string | null; suffix?: string | null }) {
    const numeric = Number(value ?? 0);
    const [display, setDisplay] = useState(numeric);
    const ref = useRef<HTMLSpanElement>(null);

    useEffect(() => {
        if (
            !Number.isFinite(numeric) ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        ) {
            return;
        }

        const node = ref.current;
        if (!node) return;

        const observer = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) return;
            const started = performance.now();
            const duration = 900;
            const tick = (time: number) => {
                const progress = Math.min((time - started) / duration, 1);
                setDisplay(Math.round(numeric * progress));
                if (progress < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
            observer.disconnect();
        });

        observer.observe(node);
        return () => observer.disconnect();
    }, [numeric]);

    return (
        <span ref={ref}>
            {new Intl.NumberFormat('id-ID').format(display)}
            {suffix}
        </span>
    );
}
