<?php
session_start();
$nonavbar = '';
$pagetitle = 'login';
if (isset($_SESSION['username'])){
    header('Location: dashboard.php'); //redirect to dashboard page
}
include "init.php";

//check if user coming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $username = $_POST['username'];
  $pass = $_POST['password'];
  $hashedpass = sha1($pass);
  //check if user exist in database
    $stmt = $con->prepare("SELECT userid , username , password FROM users WHERE username = ? AND password = ? AND groupid = 1 LIMIT 1");
    $stmt->execute(array($username , $hashedpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    //if count > 0 this mean database contain record about this username
    if ($count > 0){
        $_SESSION['username'] = $username; //register session name
        $_SESSION['id'] = $row['userid']; //register session id
        header('Location: dashboard.php'); //redirect to dashboard page
        exit();
    }
}

?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <h4 class="text-center">Admin login</h4>
    <input class="form-control" type="text" name="username" placeholder="username" autocomplete="off">
    <input class="form-control" type="password" name="password" placeholder="password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="login">
</form>

<?php
include $tpl."/footer.php";
?>
