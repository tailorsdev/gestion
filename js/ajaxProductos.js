const { createApp, ref, computed, onMounted } = Vue

createApp({
    setup() {
        const productos = ref([])
        const currentProduct = ref({
            id: null,
            nombre: '',
            descripcion: '',
            precio: 0,
            stock: 0,
            fecha_creacion: new Date(),
            estado: true
        })
        const isEditing = ref(false)
        const showFormModal = ref(false)
        const showDeleteModal = ref(false)
        const searchTerm = ref('')
        const errors = ref({})
        const nextId = ref(1)
        const url = 'index.php?controller=Producto&action='

        const loadData = async () => {
            const data = await fetch(url + 'readAll')
            productos.value = await data.json()
            nextId.value = 4
        }

        onMounted(async () => {
            await loadData()
        })

        const filteredProducts = computed(() => {
            if (!searchTerm.value) return productos.value

            const term = searchTerm.value.toLowerCase()
            return productos.value.filter(producto =>
                producto.nombre.toLowerCase().includes(term) ||
                producto.descripcion.toLowerCase().includes(term)
            )
        })

        const formatDate = (date) => {
            if (!date) return ''
            const d = new Date(date)
            return d.toLocaleDateString()
        }

        const openCreateModal = () => {
            currentProduct.value = {
                id: null,
                nombre: '',
                descripcion: '',
                precio: 0,
                stock: 0,
                fecha_creacion: new Date(),
                estado: true
            }
            isEditing.value = false
            showFormModal.value = true
            errors.value = {}
        }

        const openEditModal = (producto) => {
            currentProduct.value = { ...producto }
            isEditing.value = true
            showFormModal.value = true
            errors.value = {}
        }

        const openDeleteModal = (producto) => {
            currentProduct.value = { ...producto }
            showDeleteModal.value = true
        }

        const closeModal = () => {
            showFormModal.value = false
            showDeleteModal.value = false
            errors.value = {}
        }

        const validateForm = () => {
            const newErrors = {}

            if (!currentProduct.value.nombre.trim()) {
                newErrors.nombre = 'El nombre es obligatorio'
            }

            if (!currentProduct.value.descripcion.trim()) {
                newErrors.descripcion = 'La descripción es obligatoria'
            }

            if (currentProduct.value.precio <= 0) {
                newErrors.precio = 'El precio debe ser mayor que 0'
            }

            if (currentProduct.value.stock < 0) {
                newErrors.stock = 'El stock no puede ser negativo'
            }

            errors.value = newErrors
            return Object.keys(newErrors).length === 0
        }

        const saveProduct = async () => {
            if (!validateForm()) return

            const action = isEditing.value ? 'update' : 'create';
            const formData = new FormData();
            
            for (const key in currentProduct.value) {
                formData.append(key, currentProduct.value[key]);
            }
            const data = await fetch(url + action, {
                method: 'POST',
                body: formData
            })
            console.log(await data.json())
            await loadData()
            closeModal()
        }

        const deleteProduct = async () => {
            const formData = new FormData();
            formData.append('id', currentProduct.value.id);
            const data = await fetch(url + 'delete', {
                method: 'POST',
                body: formData
            })
            console.log(await data.json())
            await loadData()
            closeModal()
        }

        return {
            productos,
            currentProduct,
            isEditing,
            showFormModal,
            showDeleteModal,
            searchTerm,
            errors,
            filteredProducts,
            formatDate,
            openCreateModal,
            openEditModal,
            openDeleteModal,
            closeModal,
            saveProduct,
            deleteProduct
        }
    }
}).mount('#app')