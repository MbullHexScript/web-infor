import { useEffect, useState } from 'react';

export function LighthouseBeam() {
    const [show, setShow] = useState(false);

    useEffect(() => {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2) return;
        if (sessionStorage.getItem('lighthouse-beam-seen') === 'true') return;

        sessionStorage.setItem('lighthouse-beam-seen', 'true');
        const start = window.setTimeout(() => setShow(true), 0);
        const timeout = window.setTimeout(() => setShow(false), 1200);

        return () => {
            window.clearTimeout(start);
            window.clearTimeout(timeout);
        };
    }, []);

    if (!show) return null;

    return (
        <div
            className="pointer-events-none absolute inset-0 z-10 animate-[lighthouse-sweep_1.05s_ease-out_forwards] bg-[conic-gradient(from_220deg_at_72%_62%,transparent_0deg,rgba(251,195,31,.34)_18deg,rgba(201,184,230,.26)_34deg,transparent_54deg)] mix-blend-screen motion-reduce:hidden"
            aria-hidden
        />
    );
}
