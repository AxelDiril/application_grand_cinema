<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @yield('titre')
        </title>
    </head>
    <body>
        <header>
            @include('includes.header')
        </header>

        <main>
            @yield('contenu')
        </main>

        <footer>
            @include('includes.footer')
        </footer>
    </body>
</html>