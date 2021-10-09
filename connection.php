<?php

    $con = mysqli_connect('localhost','root','','gemis',3308);

    if(!$con)
    {
        die(' Please Check Your Connection'.mysqli_error());
    }
?>