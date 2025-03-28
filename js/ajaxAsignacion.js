import { ref, computed, onMounted } from 'vue';

// Estado
const grupos = ref([
    { id: 1, nombre: 'Grupo Principal de Usuarios', descripcion: 'Esta es una prueba del grupo guardado' },
    { id: 2, nombre: 'Otro grupo mas para añadir a esta prueba', descripcion: 'Este es mi codigo' }
]);

const productos = ref([
    {
        id: 1,
        nombre: 'Camiseta De Nacional',
        descripcion: 'Esta es una camiseta de Prueba',
        precio: 40000.00,
        stock: 5,
        fechaCreacion: '28/3/2025',
        estado: 'Inactivo'
    },
    {
        id: 2,
        nombre: 'Balon de Futbol Original',
        descripcion: 'Este es un balon de prueba',
        precio: 50000.00,
        stock: 5,
        fechaCreacion: '28/3/2025',
        estado: 'Inactivo'
    },
    {
        id: 3,
        nombre: 'Guantes de Portero',
        descripcion: 'Guantes profesionales para portero',
        precio: 35000.00,
        stock: 10,
        fechaCreacion: '27/3/2025',
        estado: 'Activo'
    },
    {
        id: 4,
        nombre: 'Canilleras Protectoras',
        descripcion: 'Canilleras de alta protección',
        precio: 15000.00,
        stock: 15,
        fechaCreacion: '26/3/2025',
        estado: 'Activo'
    }
]);

// Relaciones entre grupos y productos (simulando una tabla de relación)
const productosGrupos = ref([
    { grupoId: 1, productoId: 1 },
    { grupoId: 1, productoId: 3 },
    { grupoId: 2, productoId: 2 }
]);

const grupoSeleccionado = ref('');
const productosAsignados = ref([]);
const mostrarModalAsignar = ref(false);
const busquedaProducto = ref('');

// Métodos
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
onMounted(() => {
    // Si hay grupos disponibles, seleccionar el primero por defecto
    if (grupos.value.length > 0) {
        grupoSeleccionado.value = grupos.value[0].id;
        cargarProductosGrupo();
    }
});