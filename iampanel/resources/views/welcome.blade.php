<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IAMPANEL - Welcome</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/css/tailwind.output.css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="/js/init-alpine.js"></script>
</head>

<body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div class="flex-1 h-full max-w-6xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <div class="h-32 md:h-auto md:w-2/3 relative">
                    <img aria-hidden="true" class="absolute h-auto w-32 md:w-auto mt-5 ml-5 h-7"
                        src="/images/e3750ec37f6901aa3dd39.png" alt="Office" />
                    <img aria-hidden="true" class="absolute h-auto w-32 md:w-auto mt-0 ml-auto mr-0 right-0"
                        src="/images/0e7e610e2f550ef6e35f0.png" alt="Office" />
                    <img aria-hidden="true" class="object-cover w-full h-full dark:hidden"
                        src="/images/8781e4e93c152c9ea95477.jpg" alt="Office" />
                    <img aria-hidden="true" class="hidden object-cover w-full h-full dark:block"
                        src="/images/8781e4e93c152c9ea95477.jpg" alt="Office" />
                </div>
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/3">
                    <div class="w-full">
                        <img src="" alt="" class="h-20 mx-auto mb-6">

                        <!-- You should use a button here, as the anchor is only used for the example  -->
                        <a class="flex items-center justify-center w-full px-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-blue"
                            href="{{route('sso.web.login')}}">
                            <svg class="w-8 h-8 mt-2" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                <path d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Sign In
                        </a>
                        <a class="flex items-center justify-center w-full px-4 mt-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
                            href="{{route('privacy_policy')}}">
                            <svg fill="currentColor" class="bi bi-info-circle w-8 h-8 mt-2 mb-1" viewBox="0 0 24 24">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                            Kebijakan Privasi
                        </a>

                        <hr class="my-8" />
                        <div class="text-gray-700 dark:text-gray-200">
                            <h1 class="mb-2 text-l font-semibold">
                                Bantuan
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
