<!DOCTYPE html>{{-- <h1>{{$carrera}}</h1> --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js'])
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    
        body {
            min-height: 100vh;
        }

        /* Centrado vertical y horizontal del contenido principal */
        .center-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .center-text {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        footer {
            background-color: #e6e6e6;
            text-align: center;
            padding: 10px;
            margin-top: auto;
            width: 100%;
        }
    </style>
    <title>Document</title>
</head>
<body>

    @include("menu")
    
    @yield("contenido1")
        @empty($__env->yieldContent('contenido1'))
        <p class="center-text">BIENVENIDOS A MI PAGINA</p>
        @endempty
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text">
                <a href="https://www.php.net/" target="_blank">PHP</a>
            </span>
        </div>
    
        <div class="container text-center">
            <span class="text">
                <a href="https://www.laravel.com/" target="_blank">LARAVEL</a>
            </span>
        </div>
    
        <div class="container text-center">
            <span class="text">
                <a href="https://lenguajehtml.com/html/" target="_blank">HTML</a>
            </span>
        </div>
    
        <div class="container text-center">
            <span class="text">
                <a href="http://javascript.com" target="_blank">JAVASCRIPT</a>
            </span>
        </div>
    
        <div class="container text-center">
            <span class="text">
                <a href="https://developer.mozilla.org/es/docs/Learn/Getting_started_with_the_web/CSS_basics" target="_blank">CSS</a>
            </span>
        </div>
    
    </footer>
    
        
    
</body>
</html>