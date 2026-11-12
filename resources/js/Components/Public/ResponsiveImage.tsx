type ResponsiveImageProps = {
    src: string;
    alt: string;
    className?: string;
    width: number;
    height: number;
    loading?: 'eager' | 'lazy';
};

export function ResponsiveImage({
    src,
    alt,
    className,
    width,
    height,
    loading = 'lazy',
}: ResponsiveImageProps) {
    return (
        <img
            src={src}
            alt={alt}
            className={className}
            width={width}
            height={height}
            loading={loading}
            decoding="async"
        />
    );
}
