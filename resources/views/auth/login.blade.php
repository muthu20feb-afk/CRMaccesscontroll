<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Login')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/login.js')
    <style>
        /* 🌄 Parallax background effect */
        .parallax-bg {
            background-image: url('{{ asset('images/2150041867.jpg') }}');
            height: 100vh;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Optional overlay for better text contrast */
        .overlay {
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- Parallax background section -->
    <section class="parallax-bg w-full flex items-center justify-center overlay">
        <div class="w-full bg-white/90 backdrop-blur-sm rounded-lg shadow-md max-w-md mx-4">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl text-center">
                    Sign in to your account
                </h1>

                <form class="space-y-4 md:space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" id="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                      focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="Email" required>
                        <span id="email-status" class="text-sm mt-1 block"></span>
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                   placeholder="Password"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                          focus:ring-blue-500 focus:border-blue-500 block w-full
                                          p-2.5 pr-10" required>

                            <!-- Toggle Eye Button -->
                            <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500">
                                <!-- Eye Open -->
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                           9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                           0-8.268-2.943-9.542-7z" />
                                </svg>

                                <!-- Eye Closed -->
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478
                                           0-8.268-2.943-9.542-7a9.956 9.956
                                           0 012.373-3.992m2.047-1.683A9.956
                                           9.956 0 0112 5c4.477 0 8.267 2.943
                                           9.541 7a9.956 9.956 0 01-1.249 2.592M15
                                           12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 21" />
                                </svg>
                            </button>
                        </div>
                        <span id="password-status" class="text-sm mt-1 block"></span>
                    </div>

                    <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                                   focus:outline-none focus:ring-blue-300 font-medium rounded-lg
                                   text-sm px-5 py-2.5 text-center">
                        Login to your account
                    </button>
                </form>

                <div class="text-sm font-medium text-gray-500 text-center">
                    Not registered?
                    <a href="{{ route('register') }}" class="text-blue-700 hover:underline">Create account</a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const Login = "{{ route('check.email') }}";
        const Password = "{{ route('check.password') }}";
    </script>

</body>
</html>
