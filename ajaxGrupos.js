const {
    createApp,
    ref,
    computed,
    onMounted
} = Vue

createApp({
    setup() {
        const grupos = ref([])
        const currentGroup = ref({
            id: null,
            nombre: '',
            descripcion: ''
        })
        
        const isEditing = ref(false)
        const showFormModal = ref(false)
        const showDeleteModal = ref(false)
        const searchTerm = ref('')
        const errors = ref({})
        const nextId = ref(1)
        const url = 'index.php?controller=Grupo&action='

        const loadData = async () => {
            const data = await fetch(url + 'readAll')
            grupos.value = await data.json()
            nextId.value = 5
        }

        onMounted(async () => {
            await loadData()
        })

        const filteredGroups = computed(() => {
            if (!searchTerm.value) return grupos.value

            const term = searchTerm.value.toLowerCase()
            return grupos.value.filter(grupo =>
                grupo.nombre.toLowerCase().includes(term) ||
                grupo.descripcion.toLowerCase().includes(term)
            )
        })

        const openCreateModal = () => {
            currentGroup.value = {
                id: null,
                nombre: '',
                descripcion: ''
            }
            isEditing.value = false
            showFormModal.value = true
            errors.value = {}
        }

        const openEditModal = (grupo) => {
            currentGroup.value = {
                ...grupo
            }
            isEditing.value = true
            showFormModal.value = true
            errors.value = {}
        }

        const openDeleteModal = (grupo) => {
            currentGroup.value = {
                ...grupo
            }
            showDeleteModal.value = true
        }

        const closeModal = () => {
            showFormModal.value = false
            showDeleteModal.value = false
            errors.value = {}
        }

        const validateForm = () => {
            const newErrors = {}

            if (!currentGroup.value.nombre.trim()) {
                newErrors.nombre = 'El nombre es obligatorio'
            }

            if (!currentGroup.value.descripcion.trim()) {
                newErrors.descripcion = 'La descripción es obligatoria'
            }

            errors.value = newErrors
            return Object.keys(newErrors).length === 0
        }

        const saveGroup = async () => {
            if (!validateForm()) return

            const formData = new FormData()
            if (isEditing.value) {
                for (const key in currentGroup.value) {
                    formData.append(key, currentGroup.value[key])
                }
                const data = await fetch(url + 'update', {
                    method: 'POST',
                    body: formData
                })
                console.log(await data.json())
                await loadData()
            } else {
                formData.append('id', nextId.value++)
                for (const key in currentGroup.value) {
                    formData.append(key, currentGroup.value[key])
                }
                const data = await fetch(url + 'create', {
                    method: 'POST',
                    body: formData
                })
                console.log(await data.json())
                await loadData()
            }
            closeModal()
        }

        const deleteGroup = async () => {
            const formData = new FormData()
            formData.append('id', currentGroup.value.id)
            const data = await fetch(url + 'delete', {
                method: 'POST',
                body: formData
            })
            grupos.value = await data.json()
            await loadData()
            closeModal()
        }

        return {
            grupos,
            currentGroup,
            isEditing,
            showFormModal,
            showDeleteModal,
            searchTerm,
            errors,
            filteredGroups,
            openCreateModal,
            openEditModal,
            openDeleteModal,
            closeModal,
            saveGroup,
            deleteGroup
        }
    }
}).mount('#app')
