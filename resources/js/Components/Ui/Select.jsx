// resources/js/Components/Ui/Select.jsx

export default function Select({ className = '', ...props }) {
    return (
        <select
            className={`block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${className}`}
            {...props}
        />
    );
}