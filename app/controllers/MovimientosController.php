<?php

require_once __DIR__ . '/../models/MovimientosModel.php';

// Controlador para gestionar los movimientos.
class MovimientosController
{
	private $model;

	public function __construct() {
	
	$this->model = new MovimientosModel(); // Instancia del modelo de movimientos
	$userId = $_SESSION['user_id'] ?? null; // Obtener el ID del usuario de la sesión
	}

	// Muestra el resumen de movimientos, cuentas y categorías -- Página de inicio. 
	public function resumen() {
		$titulo = "Resumen";

		$movimientos = $this->model->getAllMovimientosByUser($_SESSION['user_id'] ?? null);
		$cuentas = $this->model->getCuentas();
		$categorias = $this->model->getCategorias();
		
		require __DIR__ . '/../views/templates/header.php';
		require __DIR__ . '/../views/resumen.php';
		require __DIR__ . '/../views/templates/footer.php';
	}

	// Muestra la lista de movimientos -- Página de movimientos.
	public function index() {
		$titulo = "Movimientos";
		$movimientos 		= $this->model->getAllMovimientosByUser($_SESSION['user_id'] ?? null);
		$cuentas     		= $this->model->getCuentas();
		$categorias  		= $this->model->getCategorias();

		require __DIR__ . '/../views/templates/header.php';
		require __DIR__ . '/../views/movimientos/movimientos.php';
		require __DIR__ . '/../views/templates/footer.php';
	}

	// Muestra la lista de movimientos sin plantilla -- Valorar fusionar ambos métodos index y listaMovimientos.
	public function listaMovimientos() {
		$movimientos = $this->model->getAllMovimientosByUser($_SESSION['user_id'] ?? null);
		$cuentas = $this->model->getCuentas();
		$categorias = $this->model->getCategorias();

		require __DIR__ . '/../views/movimientos/listaMovimientos.php';
	}

	// Muestra el formulario para crear un nuevo movimiento.
	public function create(){
		
		$data['titulo'] 	= "Añadir registro";
		$categorias = $this->model->getCategorias(); 
		$cuentas 	= $this->model->getCuentas();     
		
		require __DIR__ . '/../views/movimientos/movimientos_create.php';
	}

	// Guarda un nuevo movimiento en la base de datos.
	public function save() {
		$userId = (int)($_SESSION['user_id'] ?? 0);
		if (!$userId) { header('Location: index.php?c=usuarios&a=index'); exit; }

		$categoria_id   = (int)($_POST['categoria_id'] ?? 0);
		$cuenta_id      = (int)($_POST['cuenta_id'] ?? 0);
		$tipo_registro  = trim($_POST['tipo_registro'] ?? '');
		$tipo_movimiento= trim($_POST['tipo_movimiento'] ?? '');
		$cantidad       = (float)($_POST['cantidad'] ?? 0);
		$fecha          = $_POST['fecha_registro'] ?? date('Y-m-d');
		$comentario     = $_POST['comentario'] ?? null;

		$ok = (new MovimientosModel())->insertMovimiento(
			$userId, $categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario
		);
		header('Location: index.php?c=movimientos&a=index' . ($ok ? '' : '&error=save')); exit;
	}

	// Muestra el formulario para editar un movimiento existente.
	public function edit() {
		$id = $_GET['id'] ?? null;
		if (!$id) { http_response_code(400); echo "Falta id"; return; }
		$movimiento = $this->model->getMovimientoById($id);
		if (!$movimiento) { http_response_code(404); echo "No encontrado"; return; }
		$cuentas = $this->model->getCuentas();
		$categorias = $this->model->getCategorias();
		require __DIR__ . '/../views/movimientos/movimientos_edit.php';
		}

	// Actualiza un movimiento existente en la base de datos.
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

	// Elimina uno o varios movimientos de la base de datos.
	public function delete() {
		$ids = filter_input(INPUT_POST, 'ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];
		if (!$ids && isset($_GET['id'])) $ids = [$_GET['id']];

		$ids = array_values(array_filter($ids, fn($v) => ctype_digit((string)$v)));
		if (!$ids) { http_response_code(400); echo 'No hay ids'; return; }

		$ok = $this->model->delete($ids);
		echo $ok ? 'ok' : 'error';
	}
}
?>