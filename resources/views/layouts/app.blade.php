<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ ('/css/theme.min.css') }}">
        <link rel="stylesheet" href="{{ ('/css/dataTables.bootstrap4.min.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ ('/js/jquery.min.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ ('/js/theme.min.js') }}" defer></script>
        <script src="{{ ('/js/jquery.dataTables.min.js') }}" defer></script>
        <script src="{{ ('/js/dataTables.bootstrap4.min.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-light overflow-auto">
        <x-jet-banner />
        <div class="dashboard-main-wrapper">
        <div class="min-h-screen bg-gray-100">


            @livewire('navigation-menu')

            <!-- Page Heading
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif-->

            @include('leftmenu')

            <!-- Page Content -->
            <div class="dashboard-wrapper">
      <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <main>
                {{ $slot }}
            </main>

            </div>
        </div>

        @include('footer')

      </div>
        </div>
        </div>


        @stack('modals')

        @livewireScripts
    </body>
</html>
