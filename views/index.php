<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos y Asignación a Grupos</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Gestión de Productos y Asignación a Grupos
                </h1>
            </div>

            <div class="grid w-full grid-cols-3 gap-4">
                <div class="flex items-center justify-center">
                    <a href="?view=productos" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Productos
                    </a>
                </div>
                <div class="flex items-center justify-center">
                    <a href="?view=grupos" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Grupos
                    </a>
                </div>
                <div class="flex items-center justify-center">
                    <a href="?view=asignacion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Asignacion
                    </a>
                </div>
            </div>
        </div>

    </div>
</body>

</html>