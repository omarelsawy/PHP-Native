<?php
session_start();
$pagetitle = 'Profile';
include "init.php";
if (isset($_SESSION['user'])){
   $getUser = $con->prepare("SELECT * FROM users WHERE username = ?");
   $getUser->execute(array($sessionUser));
   $info = $getUser->fetch();
?>

<h1 class="text-center">My Profile</h1>
<div class="information">
    <div class="container">
        <div>
           <div>My information</div>
               <div>
                   name:<?php echo $info['username'] ?><br>
                   Email:<?php echo $info['email'] ?><br>
                   Full:<?php echo $info['fullname'] ?><br>
                   Fav category:<?php echo $info['fullname'] ?><br>
               </div>
        </div>
    </div>
</div><br>

    <div class="ads">
        <div class="container">
            <div>
                <div>My ads</div>
                <div id="myads">

                    <div class="row">
                        <?php $check = getItems('member_id' , $info['userid']);
                        if (!empty($check)){
                            foreach ($check as $item){
                                echo "<div class='col-sm-6 col-md-4'>";
                                echo "<div class='thumbnail itm'>";
                                echo"<span>".$item['price']."</span>";
                                echo "<img src='' alt=''>";
                                echo "<div class='caption'>";
                                echo"<h3><a href='show.php?itemid=".$item['item_id']."'>".$item['name']."</a></h3>";
                                echo "<p>".$item['description']."</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }else{
                            echo 'there is no ads <a href="items.php">add ads</a>';
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div><br>

    <div class="mycomm">
        <div class="container">
            <div>
                <div>Latest comment</div>
                <div>
                 <?php
                    $stmt = $con->prepare("SELECT comment FROM comments
                                                     WHERE user_id = ? LIMIT 5");
                    $stmt->execute(array($info['userid']));
                    $rows = $stmt->fetchAll();
                    if (!empty($rows)){
                        foreach ($rows as $row){
                            echo "<p>".$row['comment']."</p>";
                        }
                    }

                 ?>
                </div>
            </div>
        </div>
    </div>
<?php
    }else{
         header('Location:login.php');
         exit();
     }
include $tpl."footer.php";

