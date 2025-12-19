<?php 

require_once __DIR__ . '/../models/CategoriasModel.php';

class CategoriasController {
    private CategoriasModel $model;

    public function __construct() {
        $this->model = new CategoriasModel();
    }
    
    public function index()
    {
        $titulo = "Categorías";
        $categorias = $this->model->getAllCategorias();
        
        require __DIR__ . '/../views/templates/header.php';
        require __DIR__ . '/../views/categorias/categorias.php';
        require __DIR__ . '/../views/templates/footer.php';
    }

    public function create() {
        $title = 'Nueva categoría';
        require __DIR__ . '/../views/templates/header.php';
        require __DIR__ . '/../views/categorias_create.php'; // crea esta vista
        require __DIR__ . '/../views/templates/footer.php';
    }

    public function save() {
        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre === '') {
            http_response_code(400);
            echo 'El nombre es obligatorio';
            return;
        }
        $id = $this->model->create($nombre);
        header('Location: index.php?c=categorias&a=index&created=' . (int)$id);
        exit;
    }
}


?>