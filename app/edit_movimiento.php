<?php
include('connection.php');
$con = connection();

$id = $_POST['id'];
$categoria = $_POST['categoria'];
$cuenta = $_POST['cuenta'];
$tipo_movimiento = $_POST['tipo_movimiento'];
$tipo_registro = $_POST['tipo_registro'];
$cantidad = $_POST['cantidad'];
$fecha = $_POST['fecha_registro'];
$comentario = $_POST['comentario'];

$sql = "UPDATE `movimientos` SET usuario_id = 1, categoria_id = $categoria, cuenta_id = $cuenta, tipo_registro = '$tipo_registro', tipo_movimiento = '$tipo_movimiento', cantidad = $cantidad, fecha_registro = '$fecha', comentario = '$comentario' WHERE id = $id";
$query = mysqli_query($con, $sql);

if($query){
    Header("Location: ../index.php");
};


?>