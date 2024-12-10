<?php

$conn = mysqli_connect('localhost','root','','userDatabase');

if(!$conn){
    die(''. mysqli_connect_error());
}

?>