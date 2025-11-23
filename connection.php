<?php

function connection(){
    $host = "localhost:8889";
    $user = "root";
    $pass = "root";

    $bd = "users_crud_php";

    $connect=mysqli_connect($host, $user, $pass);

    mysqli_select_db($connect, $bd);

    return $connect;

}


?>