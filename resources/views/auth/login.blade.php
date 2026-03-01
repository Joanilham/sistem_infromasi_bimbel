<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genius Education - Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-sm w-full space-y-8 bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Top border accent -->
        <div class="h-2 w-full bg-blue-600"></div>

        <div class="px-8 pt-8 pb-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Genius Education
                </h1>
                <p class="mt-3 text-sm text-gray-600">
                    Sign in to start your session
                </p>
                <div class="mt-4 border-b border-gray-200"></div>
            </div>

            <!-- Form -->
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Email Input -->
                    <div>
                        <div class="relative rounded-md shadow-sm">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                                class="appearance-none block w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:text-sm text-gray-900"
                                placeholder="Email">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none border-l border-gray-200 bg-gray-50 rounded-r-md px-3">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="relative rounded-md shadow-sm">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:text-sm text-gray-900"
                                placeholder="Password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none border-l border-gray-200 bg-gray-50 rounded-r-md px-3">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show Password Checkbox -->
                <div class="flex items-center">
                    <input id="show_password" name="show_password" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer transition duration-150 ease-in-out"
                        onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'">
                    <label for="show_password" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                        Tampilkan Password
                    </label>
                </div>

                <!-- Select Inputs -->
                <div class="space-y-4 pt-2">
                    <div>
                        <label for="kantor" class="block text-sm font-medium text-gray-700 mb-1">Kantor:</label>
                        <select id="kantor" name="kantor"
                            class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md transition-colors duration-200 cursor-pointer shadow-sm border">
                            <option value="">Pilih Kantor...</option>
                            @foreach($kantors as $kantor)
                            <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }} {{ $kantor->alamat ? '(' . $kantor->alamat . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Periode:</label>
                        <select id="periode" name="periode"
                            class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md transition-colors duration-200 cursor-pointer shadow-sm border">
                            <option value="">Pilih Periode...</option>
                            @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}" {{ $periode->is_active ? 'selected' : '' }}>{{ $periode->tahun_periode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <!-- Heroicon name: solid/login -->
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>