<?php
/**
 * @author
 */

// Contains the constructor for Accounts
include("AccountClass.php");
// Contains the login page
include("login.php");
// Contains the establishment to the database
include ("../includes/database.php");

// Contains the checking of the entered credentials from login.php to the database.
$accounts = [];

$query = "SELECT username, password FROM useradmin";
$result = $database->query($query);

while($row = $result->fetch_assoc()) {
    $accounts[] = new Account($row['username'], $row['password']);
}
if(isset($_POST['buttonLogin'])){
    $username = $_POST['user'];
    $password = $_POST['Password'];

    foreach($accounts as $cred){
        $credUser = $cred->getUser();
        $credPass = $cred->getPassword();

        if($username == $credUser && $password == $credPass){
            session_start();
            header("Location: index.php");
            $_SESSION['username'] = $username;
        }
    }
    echo "<script>alert('Username or Password incorrect!')</script>";
    echo "<script>location.href='login.php'</script>";

}


?>