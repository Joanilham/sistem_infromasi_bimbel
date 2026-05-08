// resources/js/Components/Ui/Input.jsx

export default function Input({ className = '', ...props }) {
    return (
        <input
            className={`block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ${className}`}
            {...props}
        />
    );
}