<?php

require_once __DIR__ . '/../models/MovimientosModel.php';

class MovimientosController
{
    private $model;

    public function __construct() {
		
        $this->model = new MovimientosModel();
    }

	public function index() {
		$titulo = "Movimientos";
		$movimientos 		= $this->model->getAllMovimientos();
		$cuentas     		= $this->model->getCuentas();
		$categorias  		= $this->model->getCategorias();

		require __DIR__ . '/../views/templates/header.php';
		require __DIR__ . '/../views/movimientos/movimientos.php';
		require __DIR__ . '/../views/templates/footer.php';
	}

	public function listaMovimientos() {
		$sort = $_GET['sort'] ?? 'date_desc';

  		$movimientos = $this->model->getAllMovimientos();
  		$cuentas = $this->model->getCuentas();
  		$categorias = $this->model->getCategorias();
  		
		require __DIR__ . '/../views/movimientos/_grid.php';
	}

	public function create(){
		
		$data['titulo'] 	= "Añadir registro";
		$data['categorias'] = $this->model->getCategorias(); 
		$data['cuentas'] 	= $this->model->getCuentas();     
		
		require __DIR__ . '/../views/movimientos/movimientos_create.php';
	}
	
	public function save(){
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: index.php?c=movimientos&a=index'); exit;
		}

		$data['titulo'] = "Añadir Movimiento";

		$data['categorias'] = $this->model->getCategorias();
		$data['cuentas'] 	= $this->model->getCuentas();
		
		$categoria_id 		= $_POST['categoria_id'] ?? '';
		$cuenta_id 			= $_POST['cuenta_id'] ?? '';
		$tipo_movimiento 	= $_POST['tipo_movimiento'] ?? '';
		$tipo_registro 		= $_POST['tipo_registro'] ?? '';
		$cantidad 			= $_POST['cantidad'] ?? '';
		$fecha 				= $_POST['fecha_registro'] ?? '';
		$comentario			= $_POST['comentario'] ?? '';
		
		if (!$categoria_id || !$cuenta_id || !$tipo_movimiento || !$tipo_registro || !$cantidad || !$fecha) {
			$data['error'] = "Por favor, completa todos los campos obligatorios.";
			require __DIR__ . '/../views/movimientos/movimientos_create.php';
			return;
		}
		
		$movimientos = new MovimientosModel();
		
		$this->model->insertMovimiento($categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario);

		$data["titulo"] = "Movimientos";
		
		$this->index();
	}
	

	public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) { http_response_code(400); echo "Falta id"; return; }
        $movimiento = $this->model->getMovimientoById($id);
        if (!$movimiento) { http_response_code(404); echo "No encontrado"; return; }
        $cuentas = $this->model->getCuentas();
        $categorias = $this->model->getCategorias();
        require __DIR__ . '/../views/movimientos/movimientos_edit.php';
    }

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        	header('Location: index.php?c=movimientos&a=index'); exit;
    	}

		$id 			  	= $_POST['id']; 
		$categoria_id 	  	= $_POST['categoria_id'];
		$cuenta_id 		  	= $_POST['cuenta_id'];
		$tipo_movimiento  	= $_POST['tipo_movimiento'];
		$tipo_registro 	  	= $_POST['tipo_registro'];  
		$cantidad 		  	= $_POST['cantidad'];
		$fecha 			  	= $_POST['fecha_registro'];
		$comentario 	  	= $_POST['comentario'];
		
		$this->model->editMovimiento($id, $categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario);
		
		header('Location: index.php?c=movimientos&a=index');
		exit;
	}
	
	public function delete() {
        $ids = filter_input(INPUT_POST, 'ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];
        if (!$ids && isset($_GET['id'])) $ids = [$_GET['id']];

        $ids = array_values(array_filter($ids, fn($v) => ctype_digit((string)$v)));
        if (!$ids) { http_response_code(400); echo 'Sin ids'; return; }

        $ok = $this->model->delete($ids);
        echo $ok ? 'ok' : 'error';
    }

	// Métodos para obtener cuentas y categorías
	public function cuentas() {
		$data['titulo'] 	= "Cuentas";
		$data['cuentas'] 	= $this->model->getCuentas();

		require __DIR__ . '/../views/templates/header.php';
		require __DIR__ . '/../views/cuentas.php';
		require __DIR__ . '/../views/templates/footer.php';
	}

	public function categorias() {
		$data['titulo'] 	 = "Categorías";
		$data['categorias'] = $this->model->getCategorias();

		require __DIR__ . '/../views/templates/header.php';
		require __DIR__ . '/../views/categorias.php';
		require __DIR__ . '/../views/templates/footer.php';
	}
}
?>