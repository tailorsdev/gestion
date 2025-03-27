<?php
require_once "models/Producto.php";

class ProductoController
{

    public function create()
    {
        $nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        $precio      = isset($_POST['precio']) ? $_POST['precio'] : 0;
        $stock       = isset($_POST['stock']) ? $_POST['stock'] : 0;
        $estado      = isset($_POST['estado']) ? $_POST['estado'] : 1;

        if (empty($nombre) || !is_numeric($precio)) {
            echo json_encode(["error" => "Datos inválidos o incompletos."]);
            return;
        }

        $producto = new Producto();
        $producto->nombre      = $nombre;
        $producto->descripcion = $descripcion;
        $producto->precio      = $precio;
        $producto->stock       = $stock;
        $producto->estado      = $estado;

        if ($producto->create()) {
            echo json_encode(["success" => true, "message" => "Producto creado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al crear producto"]);
        }
    }

    public function readAll()
    {
        $producto = new Producto();
        $stmt = $producto->readAll();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    }

    public function readOne()
    {
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "No se especificó el ID"]);
            return;
        }
        $producto = new Producto();
        $producto->id = $_GET['id'];
        $result = $producto->readOne();
        echo json_encode($result);
    }

    public function update()
    {
        $id          = isset($_POST['id']) ? $_POST['id'] : 0;
        $nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        $precio      = isset($_POST['precio']) ? $_POST['precio'] : 0;
        $stock       = isset($_POST['stock']) ? $_POST['stock'] : 0;
        $estado      = isset($_POST['estado']) ? $_POST['estado'] : 1;

        if (empty($nombre) || !is_numeric($precio)) {
            echo json_encode(["error" => "Datos inválidos o incompletos."]);
            return;
        }

        $producto = new Producto();
        $producto->id          = $id;
        $producto->nombre      = $nombre;
        $producto->descripcion = $descripcion;
        $producto->precio      = $precio;
        $producto->stock       = $stock;
        $producto->estado      = $estado;

        if ($producto->update()) {
            echo json_encode(["success" => true, "message" => "Producto actualizado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar producto"]);
        }
    }

    public function delete()
    {
        if (!isset($_POST['id'])) {
            echo json_encode(["error" => "No se especificó el ID"]);
            return;
        }
        $producto = new Producto();
        $producto->id = $_POST['id'];

        if ($producto->delete()) {
            echo json_encode(["success" => true, "message" => "Producto eliminado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar producto"]);
        }
    }
}
