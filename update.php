<?php
include('connection.php');
$con = connection();

$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = '$id'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuarios</title>
</head>
<body>
    <div>
        <form action="edit_user.php" method="POST">
            <h1>Editar usuario</h1>
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="text" name="name" value="<?= $row['name']; ?>" placeholder="Nombre">
            <input type="text" name="lastname" value="<?= $row['lastname']; ?>" placeholder="Apellido">
            <input type="text" name="username" value="<?= $row['username']; ?>" placeholder="Usuario">
            <input type="text" name="password" value="<?= $row['password']; ?>" placeholder="Contraseña">
            <input type="text" name="email" value="<?= $row['email']; ?>" placeholder="Email">
            <input type="submit" value="Actualizar usuario">
        </form>
    </div>
    
</body>
</html>