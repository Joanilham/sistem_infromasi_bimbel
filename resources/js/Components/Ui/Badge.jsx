// resources/js/Components/Ui/Badge.jsx

export default function Badge({ children, variant = 'primary', className = '', ...props }) {
    const variants = {
        primary: 'bg-blue-100 text-blue-800',
        secondary: 'bg-gray-100 text-gray-800',
        success: 'bg-green-100 text-green-800',
        warning: 'bg-yellow-100 text-yellow-800',
        destructive: 'bg-red-100 text-red-800',
        info: 'bg-indigo-100 text-indigo-800',
    };

    return (
        <span
            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${variants[variant] || variants.primary} ${className}`}
            {...props}
        >
            {children}
        </span>
    );
}