<?php
require_once __DIR__ . '/../models/UsuariosModel.php';

class UsuariosController {

  // Método para mostrar el formulario de inicio de sesión
  public function index() {
    require __DIR__ . '/../views/usuarios/login.php';
  }

  public function login() {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($usuario === '' || $password === '') {
      header('Location: index.php?c=usuarios&a=index&error=login');
      exit;
    }
    $user = (new UsuariosModel())->login($usuario, $password);
    if (!$user) {
      header('Location: index.php?c=usuarios&a=index&error=login');
      exit;
    }

    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = ($user['usuario'] ?? '');
    $_SESSION['user_email'] = ($user['email'] ?? '');

    header('Location: index.php?c=movimientos&a=resumen');
    exit;
  }

  // Método para mostrar el formulario de registro.
  
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
    if (!$userId) {
      header('Location: index.php?c=usuarios&a=index&error=signup');
      exit;
    }

    $_SESSION['user_id']    = $userId;
    $_SESSION['user_name']  = $usuario;
    $_SESSION['user_email'] = $email;

    header('Location: index.php?c=movimientos&a=resumen');
    exit;
  }

  // Método para cerrar sesión. 
  public function logout() {
    session_destroy();
    header('Location: index.php?c=usuarios&a=index');
    exit;
  }
}