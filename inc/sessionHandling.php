<?php
    session_start();

    if(isset($_SESSION['User']))
    {
        switch ($_SESSION['user_type']) {
            case "AD": 
                $destination = "../admin/index.php";
                break;

            case "FA": 
                $destination = "../faculty/index.php";
                break;

            case "ST": 
                $destination = "../admin/index.php";
                break;
        }
        header("location: $destination");
    }
    else
    {
        header("location: ../login.php");
    }

?>