<?php 
class CategoriasModel {
    
    private mysqli $db;
    private array $categorias = [];
    
    public function __construct()
    {
        $this->db = Connect::connection();
    }

    public function getAllCategorias() {
        $result = $this->db->query("SELECT id, nombre FROM categorias ORDER BY nombre");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllCategoriasByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT id, nombre FROM categorias WHERE usuario_id = ? ORDER BY nombre");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create(string $nombre): int
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getCategoriaById($id)
    {
        $sql = "SELECT * FROM categorias WHERE id='$id' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    public function editCategoria($id, $nombre)
    {   
        $result = $this->db->query("UPDATE `categorias` SET id=$id, nombre = '$nombre' WHERE id = $id");         
    }

    public function deleteCategoria($id){
        $result = $this->db->query("DELETE FROM categorias WHERE id = '$id'");
    }
    
}
?>