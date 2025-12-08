<?php

require_once __DIR__ . '/../models/MovimientosModel.php';

class MovimientosController
{
    private $model;

    public function __construct() {
		
        $this->model = new MovimientosModel();
    }

    public function index(){

        $data['titulo'] = "Listado de Movimientos";
        $movimientos = $this->model->getAllMovimientos();
		$cuentas = $this->model->getCuentas();
		$categorias = $this->model->getCategorias();

        $viewFile = __DIR__ . '/../views/movimientos/movimientos.php';
        if (file_exists($viewFile)) {
            extract($data);
            require $viewFile;
        } else {
            echo "Error: Vista no encontrada en " . $viewFile;
        }
    }

	public function create(){
		
		$data['titulo'] = "Crear Nuevo Movimiento";
		$data['categorias'] = $this->model->getCategorias(); 
		$data['cuentas'] = $this->model->getCuentas();     
		
		require __DIR__ . '/../views/movimientos/movimientos_create.php';
	}
	
	public function save(){
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: index.php?c=movimientos&a=index'); exit;
		}

		$data['titulo'] = "Añadir Movimiento";

		$data['categorias'] = $this->model->getCategorias();
		$data['cuentas'] = $this->model->getCuentas();
		
		$categoria_id = $_POST['categoria_id'];
		$cuenta_id = $_POST['cuenta_id'];
		$tipo_movimiento = $_POST['tipo_movimiento'];
		$tipo_registro = $_POST['tipo_registro'];
		$cantidad = $_POST['cantidad'];
		$fecha = $_POST['fecha_registro'];
		$comentario = $_POST['comentario'];		
		
		$movimientos = new MovimientosModel();
		
		$this->model->insertMovimiento($categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario);

		$data["titulo"] = "Movimientos";
		
		$this->index();
	}
	

	public function edit($id)
	{
		if (empty($id)) {
			echo "Error: Se requiere un ID para editar.";
			exit;
		}

		$movimiento = $this->model->getMovimientoById($id);

		if ($movimiento) {
			$categorias = $this->model->getCategorias();
			$cuentas = $this->model->getCuentas();
			
			$titulo = "Editar Movimiento";

			require __DIR__ . '/../views/movimientos/movimientos_edit.php';
		} else {
			echo "Error: Movimiento con ID '$id' no encontrado.";
			exit;
		}
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        	header('Location: index.php?c=movimientos&a=index'); exit;
    	}

		$id = $_POST['id']; 
		$categoria_id = $_POST['categoria_id'];
		$cuenta_id = $_POST['cuenta_id'];
		$tipo_movimiento = $_POST['tipo_movimiento'];
		$tipo_registro = $_POST['tipo_registro'];  
		$cantidad = $_POST['cantidad'];
		$fecha = $_POST['fecha_registro'];
		$comentario = $_POST['comentario'];
		
		$this->model->editMovimiento($id, $categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario);
		
		header('Location: index.php?c=movimientos&a=index');
		exit;
	}
	
	public function delete($id){
		
		$movimientos = new MovimientosModel();
		$movimientos->deleteMovimiento($id);
		$data["titulo"] = "Movimientos";
		$this->index();
	}	

}
?>