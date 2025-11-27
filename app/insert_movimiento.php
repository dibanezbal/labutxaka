<?php
include('connection.php');
$con = connection();

$categoria = $_POST['categoria'];
$cuenta = $_POST['cuenta'];
$tipo_movimiento = $_POST['tipo_movimiento'];
$tipo_registro = $_POST['tipo_registro'];
$cantidad = $_POST['cantidad'];
$fecha = $_POST['fecha_registro'];
$comentario = $_POST['comentario'];

$sql = "INSERT INTO `movimientos` (usuario_id, categoria_id, cuenta_id, tipo_registro, tipo_movimiento, cantidad, fecha_registro, comentario) VALUES ( 1, $categoria, $cuenta, '$tipo_registro', '$tipo_movimiento', $cantidad, '$fecha', '$comentario')";
$query = mysqli_query($con, $sql);

if($query){
    Header("Location: ../index.php");
};

?>