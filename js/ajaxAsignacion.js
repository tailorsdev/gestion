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
        const productosGrupos = ref([]);
        const grupoSeleccionado = ref('');
        const productosAsignados = ref([]);
        const showModalAsignar = ref(false);
        const busquedaProducto = ref('');
        const showDeleteModal = ref(false)
        const currentProduct = ref()

        const loadDataGrupos = async () => {
            const url = 'index.php?controller=Grupo&action='
            const data = await fetch(url + 'readAll')
            grupos.value = await data.json()
        }

        const loadDataProductos = async () => {
            const url = 'index.php?controller=Producto&action='
            const data = await fetch(url + 'readAll')
            productos.value = await data.json()
        }

        const openDeleteModal = (producto) => {
            currentProduct.value = { ...producto }
            showDeleteModal.value = true
        }

        const cargarProductosGrupo = async () => {
            if (!grupoSeleccionado.value) return;

            const url = 'index.php?controller=Asignacion&action=obtenerProductosAsignados&'
            const data = await fetch(url + 'grupo_id=' + grupoSeleccionado.value)
            productosAsignados.value = await data.json()
        };

        const asignarProducto = async (productoId) => {
            if (estaProductoAsignado(productoId)) return;

            const formData = new FormData()
            formData.append('grupo_id', grupoSeleccionado.value)
            formData.append('producto_id', productoId)
            const url = 'index.php?controller=Asignacion&action=asignar'
            const data = await fetch(url, {
                method: 'POST',
                body: formData
            })

            console.log(await data.json())
            cargarProductosGrupo();
        };

        const removerProducto = async (productoId) => {
            const formData = new FormData()
            formData.append('grupo_id', grupoSeleccionado.value)
            formData.append('producto_id', productoId)
            const url = 'index.php?controller=Asignacion&action=remover'
            const data = await fetch(url, {
                method: 'POST',
                body: formData
            })
            console.log(await data.json())
            showDeleteModal.value = false
            cargarProductosGrupo();
        };

        const estaProductoAsignado = (productoId) => {
            return productosAsignados.value.some(
                pg => pg.grupo_id === grupoSeleccionado.value && pg.id === productoId
            );
        };

        const formatearPrecio = (precio) => {
            return precio.toLocaleString('es-CO');
        };

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

        onMounted(async () => {
            await loadDataGrupos();
            await loadDataProductos();
            if (grupos.value.length > 0) {
                grupoSeleccionado.value = grupos.value[0].id;
                await cargarProductosGrupo();
            }
        });

        return {
            grupos,
            productos,
            productosGrupos,
            grupoSeleccionado,
            currentProduct,
            showDeleteModal,
            productosAsignados,
            showModalAsignar,
            busquedaProducto,
            openDeleteModal,
            cargarProductosGrupo,
            asignarProducto,
            removerProducto,
            estaProductoAsignado,
            formatearPrecio,
            productosFiltrados
        };

    }
}).mount('#app')