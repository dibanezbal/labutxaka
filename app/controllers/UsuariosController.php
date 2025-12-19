<?php
require_once __DIR__ . '/../models/UsuariosModel.php';

class UsuariosController {
  public function index() {
    require __DIR__ . '/../views/usuarios/login.php';
  }

  public function login() {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $usuario = (new UsuariosModel())->login($usuario);

    $_SESSION['user_id']    = $usuario['id'];
    $_SESSION['user_name']  = ($usuario['usuario'] ?? '');
    $_SESSION['user_email'] = ($usuario['email'] ?? '');
    
    header('Location: index.php');
  }

  public function signup() {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $fecha_registro = date('Y-m-d H:i:s');

    if ($password !== $confirm || strlen($password) < 6) {
      header('Location: index.php?c=usuarios&a=index&error=signup'); return;
    }
    $userId = (new UsuariosModel())->create($usuario, $email, $password, $fecha_registro);

    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $usuario;
    header('Location: resumen.php');
  }

  public function logout() {
    session_destroy();
    header('Location: index.php?c=usuarios&a=index');
  }
}