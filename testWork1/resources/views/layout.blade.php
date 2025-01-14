<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        @vite('resources/css/app.css')
        <style>
            #navbar {
                margin: 20px;
                padding: 0;
                color: blue;
                font-size: 18px;
                text-decoration:underline;
            }
            #navbar li { display: inline; }
        </style>
    </head>
    <body>
        <div>
        <ul id="navbar">
           <li><a href="/workers">Работники</a></li>
           <li style="margin-left:20px"><a href="/departments">Департаменты</a></li>
        </ul>
        </div>
        @yield('content')
        @vite('resources/js/app.js')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"
        defer></script>
    </body>

</html>
