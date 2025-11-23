<?php
include('connection.php');

$con = connection();

$sql = "SELECT * FROM users";
$query = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="insert_user.php" method="POST">
            <h1>Crear usuario</h1>
            <input type="text" name="name" placeholder="Nombre">
            <input type="text" name="lastname" placeholder="Apellido">
            <input type="text" name="username" placeholder="Usuario">
            <input type="text" name="password" placeholder="Contraseña">
            <input type="text" name="email" placeholder="Email">
            <input type="submit" value="Crear usuario">
        </form>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
							<?php while($row = mysqli_fetch_array($query)): ?>	
                <tr>

                    <th><?= $row['id']; ?></th>
                    <th><?= $row['name']; ?></th>
                    <th><?= $row['lastname']; ?></th>
                    <th><?= $row['username']; ?></th>
                    <th><?= $row['password']; ?></th>
                    <th><?= $row['email']; ?></th>
                    
                    <th><a href="update.php?id=<?= $row['id']; ?>">Editar</a></th>
                    <th><a href="delete_user.php?id=<?= $row['id']; ?>">Eliminar</a></th>
                </tr>
								<?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>