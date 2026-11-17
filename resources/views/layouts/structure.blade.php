<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

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
    </body>
</html>