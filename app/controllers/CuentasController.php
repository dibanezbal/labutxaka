<?php

require_once __DIR__ . '/../models/CuentasModel.php';

class CuentasController
{
  private CuentasModel $model;

  public function __construct()
  {
    $this->model = new CuentasModel();
  }

  public function index()
  {
    $titulo = "Cuentas";
    $cuentas = $this->model->getAllCuentas();

    require __DIR__ . '/../views/templates/header.php';
    require __DIR__ . '/../views/cuentas/cuentas.php';
    require __DIR__ . '/../views/templates/footer.php';
  }

  // Muestra el formulario "nueva cuenta"
  public function create()
  {
    $title = 'Nueva cuenta';

    require __DIR__ . '/../views/templates/header.php';
    require __DIR__ . '/../views/cuentas/cuentas_form.php';
    require __DIR__ . '/../views/templates/footer.php';
  }

  // Procesa el POST del formulario
  public function save()
  {
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre === '') {
      http_response_code(400);
      echo 'El nombre es obligatorio';
      return;
    }

    $id = $this->model->create($nombre);

    header('Location: index.php?c=cuentas&a=index&created=' . (int) $id);
    exit;
  }
}