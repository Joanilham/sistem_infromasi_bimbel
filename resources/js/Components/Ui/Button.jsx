export default function Button({ children, variant = 'primary', size = 'md', className = '', ...props }) {
    const variants = {
        primary: 'bg-blue-600 hover:bg-blue-700 text-white',
        outline: 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50',
        secondary: 'bg-gray-200 text-gray-800 hover:bg-gray-300',
        destructive: 'bg-red-600 hover:bg-red-700 text-white',
        success: 'bg-green-600 hover:bg-green-700 text-white',
        warning: 'bg-yellow-500 hover:bg-yellow-600 text-white',
    };

    const sizes = {
        sm: 'text-xs px-2 py-1',
        md: 'text-sm px-4 py-2',
        lg: 'text-base px-6 py-3',
    };

    return (
        <button
            className={`inline-flex items-center rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 ${variants[variant] || variants.primary} ${sizes[size] || sizes.md} ${className}`}
            {...props}
        >
            {children}
        </button>
    );
}