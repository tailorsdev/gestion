# 🛍️ Aplicación Web para Gestión de Productos y Asignación a Grupos

Este repositorio contiene una aplicación web desarrollada en **PHP 8** que permite administrar productos y su asignación a grupos. La solución utiliza un enfoque basado en el patrón **MVC (Modelo-Vista-Controlador)** y AJAX para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) de manera dinámica sin recargar la página.

---

## 🚀 Funcionalidades Principales

✅ CRUD de Productos:
- **Crear:** Insertar nuevos productos validando los datos (nombre no vacío y precio numérico).
- **Leer:** Listar productos con paginación y filtros sencillos.
- **Actualizar:** Editar información de productos existentes.
- **Eliminar:** Borrar productos (con confirmación previa).

✅ CRUD de Grupos:
- **Crear:** Crear nuevos grupos.
- **Leer:** Listar grupos creados.
- **Actualizar:** Modificar información de grupos.
- **Eliminar:** Eliminar grupos existentes.

✅ Asignación de Productos a Grupos:
- **Asignar y Remover:** Permite asignar y remover productos de grupos fácilmente.
- **Visualización:** Muestra los productos asignados a cada grupo y facilita su edición.

---

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8 (OOP - Programación Orientada a Objetos)
- **Frontend:** Vue.js + Tailwind CSS
- **Arquitectura:** Patrón de Diseño MVC (Modelo-Vista-Controlador)
- **Comunicación Asíncrona:** AJAX para operaciones CRUD sin recargar la página.

---

## 📂 Estructura del Proyecto

/app
├── /controllers
│ └── ProductoController.php
│ └── GrupoController.php
│ └── AsignacionController.php
├── /models
│ └── Producto.php
│ └── Grupo.php
├── /views
│ ├── /productos
│ │ ├── index.php
│ └── index.php
└── /config
    └── Database.php