<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Kimeru Learn: learn everyday Kimeru (Kĩmĩĩrũ), numbers, greetings and foods.">

        <title>@hasSection('title')@yield('title') · @endif{{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col bg-cream text-ink font-sans antialiased">
        @include('partials.header')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </body>
</html>
