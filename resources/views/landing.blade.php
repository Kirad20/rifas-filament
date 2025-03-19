<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concursos y Sorteos San Miguel - Las mejores rifas de México</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('components.cabecero')
        @include('components.hero')
        @include('components.estadisticas')
        @include('components.como-funciona')
        @include('components.rifas-destacadas', ['rifas' => $rifasDestacadas])
        @include('components.informacion-importante')
        @include('components.preguntas-frecuentes')
        @include('components.pie-pagina')
    </div>
</body>
</html>
