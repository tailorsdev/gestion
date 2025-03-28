const {
    createApp,
    ref,
    computed,
    onMounted
} = Vue

createApp({
    setup() {

        const grupos = ref([]);
        const productos = ref([]);

        const productosGrupos = ref([
            { grupoId: 1, productoId: 1 },
            { grupoId: 1, productoId: 3 },
            { grupoId: 2, productoId: 2 }
        ]);

        const grupoSeleccionado = ref('');
        const productosAsignados = ref([]);
        const mostrarModalAsignar = ref(false);
        const busquedaProducto = ref('');
        const url = 'index.php?controller=Grupo&action='

        // Métodos

        const loadDatasGrupos = async () => {
            const data = await fetch(url + 'readAll')
            grupos.value = await data.json()
        }

        const cargarProductosGrupo = () => {
            if (!grupoSeleccionado.value) return;

            // Obtener IDs de productos asignados al grupo seleccionado
            const idsProductosAsignados = productosGrupos.value
                .filter(pg => pg.grupoId === grupoSeleccionado.value)
                .map(pg => pg.productoId);

            // Obtener los productos completos
            productosAsignados.value = productos.value.filter(p => idsProductosAsignados.includes(p.id));
        };

        const asignarProducto = (productoId) => {
            // Verificar si ya está asignado
            if (estaProductoAsignado(productoId)) return;

            // Agregar la relación
            productosGrupos.value.push({
                grupoId: grupoSeleccionado.value,
                productoId: productoId
            });

            // Actualizar la lista de productos asignados
            cargarProductosGrupo();
        };

        const removerProducto = (productoId) => {
            // Encontrar el índice de la relación a eliminar
            const indice = productosGrupos.value.findIndex(
                pg => pg.grupoId === grupoSeleccionado.value && pg.productoId === productoId
            );

            if (indice !== -1) {
                // Eliminar la relación
                productosGrupos.value.splice(indice, 1);

                // Actualizar la lista de productos asignados
                cargarProductosGrupo();
            }
        };

        const estaProductoAsignado = (productoId) => {
            return productosGrupos.value.some(
                pg => pg.grupoId === grupoSeleccionado.value && pg.productoId === productoId
            );
        };

        const formatearPrecio = (precio) => {
            return precio.toLocaleString('es-CO');
        };

        // Productos filtrados para el modal
        const productosFiltrados = computed(() => {
            if (!busquedaProducto.value) {
                return productos.value;
            }

            const busqueda = busquedaProducto.value.toLowerCase();
            return productos.value.filter(producto =>
                producto.nombre.toLowerCase().includes(busqueda) ||
                producto.descripcion.toLowerCase().includes(busqueda) ||
                producto.id.toString().includes(busqueda)
            );
        });

        // Inicialización
        onMounted(async () => {
            await loadDatasGrupos();
            // Si hay grupos disponibles, seleccionar el primero por defecto
            if (grupos.value.length > 0) {
                grupoSeleccionado.value = grupos.value[0].id;
                cargarProductosGrupo();
            }
        });

        return {
            grupos,
            productos,
            productosGrupos,
            grupoSeleccionado,
            productosAsignados,
            mostrarModalAsignar,
            busquedaProducto,
            cargarProductosGrupo,
            asignarProducto,
            removerProducto,
            estaProductoAsignado,
            formatearPrecio,
            productosFiltrados
        };

    }
}).mount('#app')