// resources/js/Components/Ui/Table.jsx

export default function Table({ children, className = '', ...props }) {
    return (
        <div className="min-w-full overflow-hidden rounded-md border border-gray-200 bg-white">
            <table className={`min-w-full divide-y divide-gray-200 ${className}`} {...props}>
                {children}
            </table>
        </div>
    );
}

export function TableHead({ children, className = '', ...props }) {
    return (
        <thead className={`bg-gray-50 ${className}`} {...props}>
            <tr>{children}</tr>
        </thead>
    );
}

export function TableBody({ children, className = '', ...props }) {
    return (
        <tbody className={`divide-y divide-gray-200 ${className}`} {...props}>
            {children}
        </tbody>
    );
}

export function TableTr({ children, className = '', ...props }) {
    return (
        <tr className={`bg-white hover:bg-gray-50 ${className}`} {...props}>
            {children}
        </tr>
    );
}

export function TableTh({ children, className = '', ...props }) {
    return (
        <th
            scope="col"
            className={`px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 ${className}`}
            {...props}
        >
            {children}
        </th>
    );
}

export function TableTd({ children, className = '', ...props }) {
    return (
        <td className={`px-6 py-4 text-sm text-gray-500 ${className}`} {...props}>
            {children}
        </td>
    );
}