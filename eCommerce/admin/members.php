<?php
/*manage members here
*you can add | edit | delete members from here*/
session_start();
$pagetitle = 'Members';
if (isset($_SESSION['username'])){
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    //manage page
    if ($do == 'manage') {
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'pending'){
            $query = 'AND regstatus = 0';
        }
        $stmt = $con->prepare("SELECT * FROM users WHERE groupid != 1 $query");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage members</h1>
           <div class="container">
               <div class="table-responsive">
                   <table class="main-table text-center table table-bordered">
                       <tr>
                           <td>#id</td>
                           <td>Avatar</td>
                           <td>Username</td>
                           <td>Email</td>
                           <td>Fullname</td>
                           <td>Registered date</td>
                           <td>Control</td>
                       </tr>

                       <?php
                         foreach ($rows as $row){
                             echo '<tr>';
                               echo '<td>' . $row['userid'] . '</td>';
                               echo "<td>";
                               if (!empty($row['image'])){
                                   echo "<img src=" .'uploads/images/' .$row['image']." alt=''>";
                               }else{
                                 echo 'no img';
                               }
                               echo "</td>";
                               echo '<td>' . $row['username'] . '</td>';
                               echo '<td>' . $row['email'] . '</td>';
                               echo '<td>' . $row['fullname'] . '</td>';
                               echo '<td>' . $row['date'] . '</td>';
                               echo "<td>
                                       <a href='?do=edit&userid=" . $row['userid'] ."' class='btn btn-success'>Edit</a>
                                       <a href='?do=delete&userid=" . $row['userid'] ."' class='btn btn-danger confirm'>Delete </a>";
                                       if ($row['regstatus'] == 0){
                                         echo "<a href='members.php?do=active&userid=" . $row['userid'] ."' class='btn btn-info' style='margin-left: 10px'>Active</a>";
                                       }

                                 echo "</td>";
                             echo '</tr>';
                         }
                       ?>

                   </table>
               </div>
               <a href="?do=add" class="btn btn-primary">Add member</a>
           </div>
    <?php
    }
    //add page
    elseif ($do == 'add'){ ?>

        <h1 class="text-center">Add member</h1>
            <div class="container">
              <!-- we use file in form we must add attr enctype="multipart/form-data" -->
              <form action="?do=insert" method="post" enctype="multipart/form-data">
                 <div class="form-group row">
                   <label class="col-sm-2">Username</label>
                     <div class="col-sm-6">
                       <input type="text" name="username" class="form-control" autocomplete="off" required="required">
                     </div>
                 </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Password</label>
                      <div class="col-sm-6">
                          <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required">
                           <i class="show-pass fa fa-eye fa-2x"></i>
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Email</label>
                      <div class="col-sm-6">
                          <input type="email" name="email" class="form-control" required="required">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Fullname</label>
                      <div class="col-sm-6">
                          <input type="text" name="fullname" class="form-control" required="required">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Image</label>
                      <div class="col-sm-6">
                          <input type="file" name="image" class="form-control">
                      </div>
                  </div>

                      <div class="col-sm-10">
                          <input type="submit" value="Add" class="btn btn-primary">
                      </div>

              </form>
            </div>
    <?php
    }

    //insert page
    elseif ($do == 'insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<h1 class="text-center">Add member</h1>';
            echo '<div class="container">';
            //get the variables from the form

            //upload variables
            $image = $_FILES['image'];
            $imagename = $image['name'];
            $imagesize = $image['size'];
            $imagetmp = $image['tmp_name'];
            $imagetype = $image['type'];

            //allowed types
            $allowedExtension = array('jpg' , 'jpeg' , 'png' , 'gif');

            //get avatar extension
            $namearr = explode('.' , $imagename);
            $lastindex = end($namearr);
            $avatarExtension = strtolower($lastindex);


            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $hashpass = sha1($_POST['password']);

            //validate the form
            $formerrors = array();
            if (strlen($username) < 3){
                $formerrors[] = 'username canot be less than 4 character';
            }
            if (strlen($username) > 20){
                $formerrors[] = 'username canot be more than 20 character';
            }
            if (empty($username)){
                $formerrors[] = 'username canot be empty';
            }
            if (empty($password)){
                $formerrors[] = 'password canot be empty';
            }
            if (empty($fullname)){
                $formerrors[] = 'full name canot be empty';
            }
            if (empty($email)){
                $formerrors[] = 'email canot be empty';
            }
            if (!empty($imagename) && !in_array($avatarExtension , $allowedExtension)){
                $formerrors[] = 'extension not allowed';
            }
            if (empty($imagename)){
                $formerrors[] = 'image canot be empty';
            }
            if ($imagesize > 4194304){
                $formerrors[] = 'image to large';
            }
            foreach ($formerrors as $error){
                echo '<div class="alert alert-danger">'.$error . '</div>';
            }


            //check if there is no errors proceed insert operation
            if (empty($formerrors)){
                $image = rand(0,100000) . '_' . $imagename;
                move_uploaded_file($imagetmp , "uploads\images\\$image");
               //check if user exist in database
               $check = checkItem('username' , 'users' , $username);
               if ($check == 1){
                   $themsg = '<div class="alert alert-danger">conat add user exist</div>';
                   redirectHome($themsg);
               }else{
                   //insert into database with this info
                    $stmt = $con->prepare("INSERT INTO users(username , password , email , fullname , regstatus , date , image)
                                                     VALUES(:username , :password , :email , :fullname , 1 , now() , :img)");
                    $stmt->execute(array(
                        'username' => $username,
                        'password' => $hashpass,
                        'email' => $email,
                        'fullname' => $fullname,
                        'img'  => $image
                    ));
                   $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted </div>';
                   redirectHome($themsg , 'back');
                }
            }
        }else{
            $themsg = "<div class='alert alert-danger'>you can't acces this page</div>";
            redirectHome($themsg);
        }
        echo '</div>';

    }

    //edit page
     elseif ($do == 'edit'){
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0){ ?>

            <h1 class="text-center">Edit member</h1>
            <div class="container">
              <form action="?do=update" method="post">
                  <input type="hidden" name="userid" value="<?php echo $userid ?>">
                 <div class="form-group row">
                   <label class="col-sm-2">Username</label>
                     <div class="col-sm-6">
                       <input type="text" name="username" class="form-control" value="<?php echo $row['username']?>" autocomplete="off" required="required">
                     </div>
                 </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Password</label>
                      <div class="col-sm-6">
                          <input type="hidden" name="oldpassword" value="<?php echo $row['password']?>">
                          <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave blank if u dont want change">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Email</label>
                      <div class="col-sm-6">
                          <input type="email" name="email" class="form-control" value="<?php echo $row['email']?>" required="required">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-sm-2">Fullname</label>
                      <div class="col-sm-6">
                          <input type="text" name="fullname" class="form-control" value="<?php echo $row['fullname']?>" required="required">
                      </div>
                  </div>

                      <div class="col-sm-10">
                          <input type="submit" value="Save" class="btn btn-primary">
                      </div>

              </form>
            </div>
   <?php
        }else{
            $themsg = '<div class="alert alert-danger">id not found</div>';
            redirectHome($themsg);
        }

    //update page
    }elseif ($do == 'update'){
        echo '<h1 class="text-center">Update member</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
          //get the variables from the form
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            //password trick
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            //validate the form
            $formerrors = array();
            if (strlen($username) < 3){
                $formerrors[] = 'username canot be less than 4 character';
            }
            if (strlen($username) > 20){
                $formerrors[] = 'username canot be more than 20 character';
            }
            if (empty($username)){
                $formerrors[] = 'username canot be empty';
            }
            if (empty($fullname)){
                $formerrors[] = 'full name canot be empty';
            }
            if (empty($email)){
                $formerrors[] = 'email canot be empty';
            }
            if (!empty($formerrors)){
                foreach ($formerrors as $error){
                    echo '<div class="alert alert-danger">'.$error . '</div>';
                }

            }
            //if there is no errors proceed update operation
             else {
                    //the old value before update
                    $old = $con->prepare("SELECT username FROM users WHERE userid = $id");
                    $old->execute();
                    $old = $old->fetch();
                    $oldname = $old['username'];

                    $check = checkItem('username' , 'users' , $username);
                    //username is the new value of the user
                    //if username not change will cause error solved by this check
                     if ($username != $oldname){
                         //this check for solve unique name constraint error
                         if ($check == 0){
                             //update database with this info
                             $stmt = $con->prepare("UPDATE users SET username = ? , email = ? , fullname = ? , password = ? WHERE userid = ?");
                             $stmt->execute(array($username, $email, $fullname, $pass, $id));
                             $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated </div>';
                              redirectHome($themsg, 'back');
                         }else{
                             $themsg = '<div class="alert alert-danger"> user already exist </div>';
                             redirectHome($themsg, 'back');
                         }
                         //if user name changed
                     }else{
                           //update database with this info
                   $stmt = $con->prepare("UPDATE users SET username = ? , email = ? , fullname = ? , password = ? WHERE userid = ?");
                   $stmt->execute(array($username, $email, $fullname, $pass, $id));
                   $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated </div>';
                   redirectHome($themsg, 'back');
                     }
            }
          /* another idea we can make query that search in database with username of the form
             and id not equal to our edited user id if our query return row echo user exist error msg
             else we can update user
             so we can avoid editing user with name exist already in db*/
        }else{
            $themsg = '<div class="alert alert-danger">you canot acces this page </div>';
            redirectHome($themsg);
        }
        echo '</div>';
    }

    //delete page
    elseif ($do == 'delete'){
        echo '<h1 class="text-center">Delete member</h1>';
        echo '<div class="container">';
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $check = checkItem('userid' , 'users' , $userid);
            if ($check > 0) {
              $stmt = $con->prepare('DELETE FROM users WHERE userid = :userid');
              $stmt->bindParam(":userid" , $userid);
              $stmt->execute();
              $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record deleted </div>';
              redirectHome($themsg , 'back');
            }
            else{
                $themsg = '<div class="alert alert-danger">No such id</div>';
                redirectHome($themsg);
            }
        echo '</div>';
    }

    //active page
    elseif ($do == 'active'){
        echo '<h1 class="text-center">Active member</h1>';
        echo '<div class="container">';
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid' , 'users' , $userid);
        if ($check > 0) {
            $stmt = $con->prepare('UPDATE users SET regstatus = 1 WHERE userid = ?');
            $stmt->execute(array($userid));
            $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record active </div>';
            redirectHome($themsg , 'back');
        }
        else{
            $themsg = '<div class="alert alert-danger">No such id</div>';
            redirectHome($themsg);
        }
        echo '</div>';
    }
    include $tpl."/footer.php";
}else{
    header('Location: index.php');
    exit();
}








