<?php

// Connection PROD
// function connection(){
//     $host = "localhost";
//     $user = "dibanezba";
//     $pass = ".Vt59NQiH6cXkRK3";

//     $bd = "dibanezba";

//     $connect=mysqli_connect($host, $user, $pass);

//     mysqli_select_db($connect, $bd);

//     return $connect;

// }

// ==== Connection DEV
function connection(){
    $host = "localhost:8889";
    $user = "root";
    $pass = "root";

    $bd = "labutxaka_DEV";

    $connect=mysqli_connect($host, $user, $pass);

    mysqli_select_db($connect, $bd);

    return $connect;

}

?>