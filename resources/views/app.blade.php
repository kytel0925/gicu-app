<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.4.95/css/materialdesignicons.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes

        @if(!app()->environment('production'))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/faker.min.js" integrity="sha512-/seDHxVfh1NvFUscAj8GsHQVZJvr2jjAoYsNL7As2FCaFHUHYIarl3sRCvVlFgyouVNiRgHIebyLKjpgd1SLDw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/locales/en/faker.en.min.js" integrity="sha512-mxra1TXulw8G25XhwqZ8XGz69etSxlaa4HCcM+0ZVDbIfMakqnxliiSO9hnsq6hrXTFL9jwNBAUIyh//D2qCPw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/locales/es/faker.es.min.js" integrity="sha512-9HRtTCDYQHJ2l/gSZ09f/hj5wMTrzuvA6VKvPCzX7uvv+3dYwlMM2B4YnTjWVl3ds5eQdflzxUCrRmhYDFeaOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @endif

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
