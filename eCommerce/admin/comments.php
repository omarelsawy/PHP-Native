<?php
/*manage members here
*you can add | edit | delete members from here*/
session_start();
$pagetitle = 'Comments';
if (isset($_SESSION['username'])){
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    //manage page
    if ($do == 'manage') {

        $stmt = $con->prepare("SELECT comments.* , items.name AS item_name , users.username FROM comments
                                         INNER JOIN items ON items.item_id = comments.item_id
                                         INNER JOIN users ON users.userid = comments.user_id
                                         ORDER BY c_id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#id</td>
                        <td>Comment</td>
                        <td>Item name</td>
                        <td>User name</td>
                        <td>Add date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($rows as $row){
                        echo '<tr>';
                        echo '<td>' . $row['c_id'] . '</td>';
                        echo '<td>' . $row['comment'] . '</td>';
                        echo '<td>' . $row['item_name'] . '</td>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td>' . $row['comment_date'] . '</td>';
                        echo "<td>
                                       <a href='?do=edit&commentid=" . $row['c_id'] ."' class='btn btn-success'>Edit</a>
                                       <a href='?do=delete&commentid=" . $row['c_id'] ."' class='btn btn-danger confirm'>Delete </a>";
                        if ($row['status'] == 0){
                            echo "<a href='comments.php?do=approve&commentid=" . $row['c_id'] ."' class='btn btn-info' style='margin-left: 10px'>Approve</a>";
                        }

                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>

                </table>
            </div>
        </div>
        <?php
    }

      //edit page
    elseif ($do == 'edit'){
        $commid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;

        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
        $stmt->execute(array($commid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0){ ?>

            <h1 class="text-center">Edit comment</h1>
            <div class="container">
                <form action="?do=update" method="post">
                    <input type="hidden" name="commid" value="<?php echo $commid ?>">
                    <div class="form-group row">
                        <label class="col-sm-2">Comment</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="comment">
                                <?php echo $row['comment'] ?>
                            </textarea>
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
        echo '<h1 class="text-center">Update comment</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //get the variables from the form
            $id = $_POST['commid'];
            $username = trim($_POST['comment']);

                //update database with this info
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($username , $id));
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated </div>';
                redirectHome($themsg , 'back');
        }else{
            $themsg = '<div class="alert alert-danger">you canot acces this page </div>';
            redirectHome($themsg);
        }
        echo '</div>';
    }

    //delete page
    elseif ($do == 'delete'){
        echo '<h1 class="text-center">Delete comment</h1>';
        echo '<div class="container">';
        $commid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        $check = checkItem('c_id' , 'comments' , $commid);
        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM comments WHERE c_id = :commid');
            $stmt->bindParam(":commid" , $commid);
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
    elseif ($do == 'approve'){
        echo '<h1 class="text-center">Approve comment</h1>';
        echo '<div class="container">';
        $commid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
        $check = checkItem('c_id' , 'comments' , $commid);
        if ($check > 0) {
            $stmt = $con->prepare('UPDATE comments SET status = 1 WHERE c_id = ?');
            $stmt->execute(array($commid));
            $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record approve </div>';
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








