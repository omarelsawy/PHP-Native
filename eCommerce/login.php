<?php
session_start();
$pagetitle = 'login';

if (isset($_SESSION['user'])){
    header('Location: index.php');
}
include "init.php";

//check if user coming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $pass = $_POST['password'];
        $hashedpass = sha1($pass);
        //check if user exist in database
        $stmt = $con->prepare("SELECT username , userid , password FROM users WHERE username = ? AND password = ?");
        $stmt->execute(array($username, $hashedpass));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        //if count > 0 this mean database contain record about this username
        if ($count > 0) {
            $_SESSION['user'] = $username; //register session name
            $_SESSION['id'] = $row['userid'];
            header('Location: index.php'); //redirect to dashboard page
            exit();
        }
    }else{

        $formErrors = array();
        $username = $_POST['username'];
        $pass = $_POST['password'];
        $hashpass = sha1($pass);
        $email = $_POST['email'];

        if (isset($username)){
            $filteruser = filter_var($username, FILTER_SANITIZE_STRING);
            if (strlen($filteruser) < 4){
                $formErrors[] = 'user name canot be less than 4 char';
            }
        }

        if (isset($pass) && isset($_POST['password2'])){
          $pass1 = sha1($pass);
          $pass2 = sha1($_POST['password2']);
          if ($pass1 !== $pass2){
              $formErrors[] = 'password donot match';
          }
          if (empty($pass)){
              $formErrors[] = 'pass 1 canot be empty';
          }
        }

        if (isset($_POST['email'])){
            $filterEmail = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL);
            if (filter_var($filterEmail , FILTER_VALIDATE_EMAIL) != true){
                $formErrors[] = 'email no valid';
            }
        }

        //check if there is no errors proceed insert operation
        if (empty($formerrors)){
            //check if user exist in database
            $check = checkItem('username' , 'users' , $username);
            if ($check == 1){
                $formErrors[] = 'User already exist';
            }else{
                //insert into database with this info
                $stmt = $con->prepare("INSERT INTO users(username , password , email , regstatus , date) 
                                                     VALUES(:username , :password , :email , 0 , now())");
                $stmt->execute(array(
                    'username' => $username,
                    'password' => $hashpass,
                    'email' => $email
                ));
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted </div>';
                redirectHome($themsg , 'back');
            }
        }

    }
}

?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="login3 selected">Login</span> | <span class="signup">Signup</span>
    </h1>

    <!-- login form -->
    <form class="frm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="name">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="pass">
        <input class="btn btn-primary btn-block" name="login" type="submit" value="login">
    </form>

    <!--sign up form -->
    <form class="sign" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="cont">
            <input id="username" pattern=".{4,10}" title="name canot bet 4,10 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="type & click to see if user exist" required>
        </div>
        <div class="cont">
            <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="pass" required>
        </div>
        <div class="cont">
            <input minlength="4" class="form-control" type="password" name="password2" placeholder="type pass again" autocomplete="new-password" required>
        </div>
        <div class="cont">
            <input class="form-control" type="email" name="email" placeholder="email" required>
        </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp">
    </form>
  <div class="errors text-center">
      <?php if (!empty($formErrors)){
        foreach ($formErrors as $error){
            echo $error . '<br>';
        }
      }
      ?>
  </div>
    <!-- if user sign up with name exist in database
     this will call ajax request in signupajax.php page
      and error eill appear hear -->
    <div id="ajax" class="text-center"></div>
</div>

<?php include $tpl."footer.php"; ?>

