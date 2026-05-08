import { Head } from '@inertiajs/react';

export default function GuruLayout({ auth, title, children, header }) {
    return (
        <div className="min-h-screen bg-gray-100">
            <Head title={title} />
            <nav className="bg-white shadow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <div className="flex-shrink-0 flex items-center">
                                <span className="text-xl font-bold text-gray-900">Sistem Informasi Bimbel</span>
                            </div>
                            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <a href={route('guru.dashboard')} className="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href={route('guru.cbt-bank-soal.index')} className="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Bank Soal
                                </a>
                            </div>
                        </div>
                        <div className="hidden sm:ml-6 sm:flex sm:items-center">
                            <div className="ml-4 relative flex-shrink-0">
                                <button
                                    type="button"
                                    className="flex rounded-full bg-gray-800 text-sm focus:outline-none"
                                >
                                    <span className="sr-only">Open user menu</span>
                                    <span className="text-white text-sm font-medium">{auth.user.name}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {header}
                </div>
            </header>

            <main>
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {children}
                </div>
            </main>
        </div>
    );
}