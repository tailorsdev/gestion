<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div id="app" class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <a href="index.php">Inicio</a>
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Productos</h1>
                <button
                    @click="openCreateModal"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <span class="mr-2">Nuevo Producto</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input
                        type="text"
                        v-model="searchTerm"
                        placeholder="Buscar productos..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute right-3 top-2.5 text-gray-400">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="py-2 px-4 text-left">ID</th>
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left hidden md:table-cell">Descripción</th>
                            <th class="py-2 px-4 text-left">Precio</th>
                            <th class="py-2 px-4 text-left hidden sm:table-cell">Stock</th>
                            <th class="py-2 px-4 text-left hidden lg:table-cell">Fecha Creación</th>
                            <th class="py-2 px-4 text-left hidden sm:table-cell">Estado</th>
                            <th class="py-2 px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredProducts.length === 0">
                            <td colspan="8" class="py-4 px-4 text-center text-gray-500">
                                No hay productos disponibles
                            </td>
                        </tr>
                        <tr v-for="producto in filteredProducts" :key="producto.id" class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">{{ producto.id }}</td>
                            <td class="py-2 px-4 font-medium">{{ producto.nombre }}</td>
                            <td class="py-2 px-4 hidden md:table-cell">
                                {{ producto.descripcion.length > 50 ? producto.descripcion.substring(0, 50) + '...' : producto.descripcion }}
                            </td>
                            <td class="py-2 px-4">${{ producto.precio }}</td>
                            <td class="py-2 px-4 hidden sm:table-cell">{{ producto.stock }}</td>
                            <td class="py-2 px-4 hidden lg:table-cell">{{ formatDate(producto.fecha_creacion) }}</td>
                            <td class="py-2 px-4 hidden sm:table-cell">
                                <span :class="producto.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ producto.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button @click="openEditModal(producto)" class="text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                            <path d="m15 5 4 4" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(producto)" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showFormModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ isEditing ? 'Editar Producto' : 'Nuevo Producto' }}</h3>
                    <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form @submit.prevent="saveProduct">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Nombre</label>
                                <input
                                    type="text"
                                    v-model="currentProduct.nombre"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <p v-if="errors.nombre" class="text-red-500 text-sm mt-1">{{ errors.nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Precio</label>
                                <input
                                    type="number"
                                    v-model="currentProduct.precio"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <p v-if="errors.precio" class="text-red-500 text-sm mt-1">{{ errors.precio }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Stock</label>
                                <input
                                    type="number"
                                    v-model="currentProduct.stock"
                                    min="0"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <p v-if="errors.stock" class="text-red-500 text-sm mt-1">{{ errors.stock }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Estado</label>
                                <select
                                    v-model="currentProduct.estado"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option :value="1">Activo</option>
                                    <option :value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 mb-2">Descripción</label>
                                <textarea
                                    v-model="currentProduct.descripcion"
                                    rows="3"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required></textarea>
                                <p v-if="errors.descripcion" class="text-red-500 text-sm mt-1">{{ errors.descripcion }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                {{ isEditing ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-2">Confirmar Eliminación</h3>
                    <p class="text-gray-600 text-center mb-6">
                        ¿Estás seguro de que deseas eliminar el producto <span class="font-semibold">{{ currentProduct.nombre }}</span>?
                        Esta acción no se puede deshacer.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <button
                            @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                            Cancelar
                        </button>
                        <button
                            @click="deleteProduct"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/ajaxProductos.js"></script>
</body>

</html>