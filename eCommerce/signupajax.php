<?php

/*when click in field with id username
ajax post request will display this page
and send the value of the field here
we recieve this value to search in database
if this name is already exist if true
send error message and display it in login page*/

include 'admin/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['name'])){
        $name = $_POST['name'];
        global $con;
         $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
         $stmt->execute(array($name));
         $count = $stmt->rowCount();
         if ($count > 0){
             echo '<div class="alert alert-danger"><p>user already exist</p></div>';
         }
    }
}else{
    header('Location:index.php');
}
?>

