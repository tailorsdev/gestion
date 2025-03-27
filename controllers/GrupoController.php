<?php
require_once "models/Grupo.php";

class GrupoController {

    public function create() {
        $nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

        if(empty($nombre)) {
            echo json_encode(["error" => "El nombre es obligatorio."]);
            return;
        }

        $grupo = new Grupo();
        $grupo->nombre = $nombre;
        $grupo->descripcion = $descripcion;

        if($grupo->create()) {
            echo json_encode(["success" => true, "message" => "Grupo creado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al crear grupo"]);
        }
    }

    public function readAll() {
        $grupo = new Grupo();
        $stmt = $grupo->readAll();
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($grupos);
    }

    public function readOne() {
        if(!isset($_GET['id'])) {
            echo json_encode(["error" => "ID no especificado"]);
            return;
        }

        $grupo = new Grupo();
        $grupo->id = $_GET['id'];
        $result = $grupo->readOne();
        echo json_encode($result);
    }

    public function update() {
        $id          = isset($_POST['id']) ? $_POST['id'] : 0;
        $nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

        if(empty($nombre)) {
            echo json_encode(["error" => "El nombre es obligatorio."]);
            return;
        }

        $grupo = new Grupo();
        $grupo->id = $id;
        $grupo->nombre = $nombre;
        $grupo->descripcion = $descripcion;

        if($grupo->update()) {
            echo json_encode(["success" => true, "message" => "Grupo actualizado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar grupo"]);
        }
    }

    public function delete() {
        if(!isset($_POST['id'])) {
            echo json_encode(["error" => "ID no especificado"]);
            return;
        }
        $grupo = new Grupo();
        $grupo->id = $_POST['id'];
        
        if($grupo->delete()) {
            echo json_encode(["success" => true, "message" => "Grupo eliminado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar grupo"]);
        }
    }
}
