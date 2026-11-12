export function AnimatedNetwork() {
    return (
        <div className="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden>
            <div className="absolute inset-0 opacity-35 [background-image:linear-gradient(90deg,rgba(143,114,183,.18)_1px,transparent_1px),linear-gradient(rgba(143,114,183,.14)_1px,transparent_1px)] [background-size:42px_42px]" />
            {Array.from({ length: 18 }).map((_, index) => (
                <span
                    key={index}
                    className="bg-brand-cyan/70 absolute h-1.5 w-1.5 animate-[network-float_8s_ease-in-out_infinite] rounded-full shadow-[0_0_18px_rgba(79,209,217,.45)] motion-reduce:animate-none"
                    style={{
                        left: `${8 + ((index * 29) % 84)}%`,
                        top: `${12 + ((index * 17) % 72)}%`,
                        animationDelay: `${index * 180}ms`,
                    }}
                />
            ))}
        </div>
    );
}
