<?php
require_once "models/Grupo.php";

class AsignacionController {

    public function asignar() {
        $grupo_id    = isset($_POST['grupo_id']) ? $_POST['grupo_id'] : 0;
        $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : 0;

        if(!$grupo_id || !$producto_id) {
            echo json_encode(["error" => "Faltan datos para la asignación"]);
            return;
        }

        $grupo = new Grupo();
        $grupo->id = $grupo_id;
        if($grupo->asignarProducto($producto_id)) {
            echo json_encode(["success" => true, "message" => "Producto asignado al grupo"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al asignar producto"]);
        }
    }

    public function remover() {
        $grupo_id    = isset($_POST['grupo_id']) ? $_POST['grupo_id'] : 0;
        $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : 0;

        if(!$grupo_id || !$producto_id) {
            echo json_encode(["error" => "Faltan datos para la remoción"]);
            return;
        }

        $grupo = new Grupo();
        $grupo->id = $grupo_id;
        if($grupo->removerProducto($producto_id)) {
            echo json_encode(["success" => true, "message" => "Producto removido del grupo"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al remover producto"]);
        }
    }

    public function obtenerProductosAsignados() {
        if(!isset($_GET['grupo_id'])) {
            echo json_encode(["error" => "No se especificó el ID del grupo"]);
            return;
        }

        $grupo_id = $_GET['grupo_id'];
        $grupo = new Grupo();
        $grupo->id = $grupo_id;
        $stmt = $grupo->obtenerProductosAsignados();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    }
}
