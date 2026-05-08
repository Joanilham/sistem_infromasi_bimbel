// resources/js/Components/Ui/Checkbox.jsx

export default function Checkbox({ className = '', ...props }) {
    return (
        <input
            type="checkbox"
            className={`h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 ${className}`}
            {...props}
        />
    );
}