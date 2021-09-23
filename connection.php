<?php

    $con=mysqli_connect('localhost','root','','login',3308);

    if(!$con)
    {
        die(' Please Check Your Connection'.mysqli_error());
    }
?>