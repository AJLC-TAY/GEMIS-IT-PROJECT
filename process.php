<?php 
require_once('connection.php');
session_start();
    if(isset($_POST['loginBtn']))
    {
       if(empty($_POST['UName']) || empty($_POST['Password']))
       {
            header("location:login.php?Empty= Please Fill in the Blanks");
       }
       else
       {
            $query = "select idno from gemis where idno='".$_POST['UName']."' and password='".$_POST['Password']."'";
            $result=mysqli_query($con,$query);
    
            if ($row = mysqli_fetch_assoc($result))
            {
                $_SESSION['User'] = $row['UName'];
                header("location:index.php");
            }
            else
            {
                header("location:login.php?Invalid= Please Enter Correct User Name and Password ");
            }
       }
    }

?>