<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Asignacion</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<body class="bg-gray-100">
    <div id="app" class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-2">
                <a href="index.php" class="text-sm text-gray-600">Inicio</a>
            </div>
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Asignación de Productos a Grupos</h1>
            </div>
        </div>

        <div class="mb-6">
            <label for="grupo" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Grupo</label>
            <div class="relative">
                <select
                    id="grupo"
                    v-model="grupoSeleccionado"
                    @change="cargarProductosGrupo"
                    class="block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base focus:border-green-500 focus:outline-none focus:ring-green-500">
                    <option value="" disabled>Seleccione un grupo</option>
                    <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">{{ grupo.nombre }}</option>
                </select>
            </div>
        </div>

        <div v-if="grupoSeleccionado" class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Productos en este grupo</h2>
                <button
                    @click="mostrarModalAsignar = true"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                    <span class="mr-1">Asignar Producto</span>
                    <span class="text-xl">+</span>
                </button>
            </div>

            <!-- Tabla de productos asignados -->
            <div class="bg-gray-100 rounded-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descripción</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Precio</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="productosAsignados.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay productos asignados a este grupo
                                </td>
                            </tr>
                            <tr v-for="producto in productosAsignados" :key="producto.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ producto.descripcion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ formatearPrecio(producto.precio) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button @click="removerProducto(producto.id)" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="mostrarModalAsignar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-3xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Asignar Productos al Grupo</h3>
                    <button @click="mostrarModalAsignar = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Buscador de productos -->
                <div class="mb-4">
                    <div class="relative">
                        <input
                            type="text"
                            v-model="busquedaProducto"
                            placeholder="Buscar productos..."
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tabla de productos disponibles -->
                <div class="bg-gray-100 rounded-md overflow-hidden mb-4">
                    <div class="overflow-x-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-200 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descripción</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Precio</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Stock</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="productosFiltrados.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No se encontraron productos
                                    </td>
                                </tr>
                                <tr v-for="producto in productosFiltrados" :key="producto.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ producto.descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ formatearPrecio(producto.precio) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <button
                                            @click="asignarProducto(producto.id)"
                                            :disabled="estaProductoAsignado(producto.id)"
                                            :class="[
                        'px-3 py-1 rounded-md text-white text-sm',
                        estaProductoAsignado(producto.id) 
                          ? 'bg-gray-400 cursor-not-allowed' 
                          : 'bg-green-500 hover:bg-green-600'
                      ]">
                                            {{ estaProductoAsignado(producto.id) ? 'Asignado' : 'Asignar' }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        @click="mostrarModalAsignar = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/ajaxAsignacion.js"></script>
</body>

</html>