import { PropsWithChildren } from 'react';

type RevealProps = PropsWithChildren<{
    className?: string;
    delay?: number;
}>;

export function Reveal({ children, className = '', delay = 0 }: RevealProps) {
    return (
        <div
            className={`animate-[fade-up_.65s_ease-out_both] motion-reduce:animate-none ${className}`}
            style={{ animationDelay: `${delay}ms` }}
        >
            {children}
        </div>
    );
}
