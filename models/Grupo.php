<?php
require_once "config/Database.php";

class Grupo {
    private $conn;
    private $table_name = "grupos";

    public $id;
    public $nombre;
    public $descripcion;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, descripcion)
                  VALUES (:nombre, :descripcion)";
        $stmt = $this->conn->prepare($query);

        $this->nombre      = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, descripcion = :descripcion
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->nombre      = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id          = intval($this->id);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function asignarProducto($producto_id) {
        $query = "INSERT IGNORE INTO producto_grupo (producto_id, grupo_id)
                  VALUES (:producto_id, :grupo_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":producto_id", $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":grupo_id", $this->id, PDO::PARAM_INT);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function removerProducto($producto_id) {
        $query = "DELETE FROM producto_grupo 
                  WHERE producto_id = :producto_id AND grupo_id = :grupo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":producto_id", $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":grupo_id", $this->id, PDO::PARAM_INT);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function obtenerProductosAsignados() {
        $query = "SELECT p.*, pg.grupo_id
                  FROM productos p
                  INNER JOIN producto_grupo pg ON p.id = pg.producto_id
                  WHERE pg.grupo_id = :grupo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
