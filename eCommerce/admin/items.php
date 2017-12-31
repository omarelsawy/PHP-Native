<?php

ob_start();
session_start();
$pagetitle = 'Items';
if (isset($_SESSION['username'])) {
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    if ($do == 'manage'){
        $stmt = $con->prepare("SELECT items.* , categories.name AS cat_name , users.username FROM items
                                         INNER JOIN categories ON categories.id = items.cat_id
                                         INNER JOIN users ON users.userid = items.member_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#id</td>
                        <td>Name</td>
                        <td>Desc</td>
                        <td>Price</td>
                        <td>Add date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($rows as $row){
                        echo '<tr>';
                        echo '<td>' . $row['item_id'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['add_date'] . '</td>';
                        echo '<td>' . $row['cat_name'] . '</td>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo "<td>
                               <a href='?do=edit&itemid=" . $row['item_id'] ."' class='btn btn-success'>Edit</a>
                               <a href='?do=delete&itemid=" . $row['item_id'] ."' class='btn btn-danger confirm'>Delete </a>";
                        if ($row['approve'] == 0){
                            echo "<a href='items.php?do=approve&itemid=" . $row['item_id'] ."' class='btn btn-info' style='margin-left: 10px'>Approve</a>";
                        }
                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>

                </table>
            </div>
            <a href="?do=add" class="btn btn-primary">Add Item</a>
        </div>
        <?php

    }

    elseif ($do == 'add'){
        ?>
        <h1 class="text-center">Add Item</h1>
        <div class="container">
            <form action="?do=insert" method="post">

                <div class="form-group row">
                    <label class="col-sm-2">Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Description</label>
                    <div class="col-sm-6">
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Price</label>
                    <div class="col-sm-6">
                        <input type="text" name="price" class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Country</label>
                    <div class="col-sm-6">
                        <input type="text" name="country" class="form-control" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="status">
                            <option value="0">...</option>
                            <option value="1">new</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">old</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Member</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="member">
                            <option value="0">...</option>
                            <?php
                              $stmt = $con->prepare("SELECT * FROM users");
                              $stmt->execute();
                              $users = $stmt->fetchAll();
                              foreach ($users as $user){
                                  echo "<option value='".$user['userid']."'>".$user['username']."</option>";
                              }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Category</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="category">
                            <option value="0">...</option>
                            <?php
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach ($cats as $cat){
                                echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-10">
                    <input type="submit" value="Add" class="btn btn-primary">
                </div>

            </form>
        </div>
        <?php
    }

    elseif ($do == 'insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<h1 class="text-center">Add item</h1>';
            echo '<div class="container">';
            //get the variables from the form
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];

            //validate the form
            $formerrors = array();
            if (empty($name)){
                $formerrors[] = 'name canot be empty';
            }
            if (empty($price)){
                $formerrors[] = 'price canot be empty';
            }
            if (empty($country)){
                $formerrors[] = 'country canot be empty';
            }
            if ($status == 0){
                $formerrors[] = 'you must choose status';
            }
            if ($member == 0){
                $formerrors[] = 'you must choose member';
            }
            if ($cat == 0){
                $formerrors[] = 'you must choose category';
            }
            foreach ($formerrors as $error){
                echo '<div class="alert alert-danger">'.$error . '</div>';
            }

            //check if there is no errors proceed update operation
            if (empty($formerrors)){

                    //insert into database with this info
                    $stmt = $con->prepare("INSERT INTO items(name , description , price , country_made , status , cat_id , member_id , add_date) 
                                                     VALUES(:name , :desc , :price , :country , :status , :cat , :mem , now())");
                    $stmt->execute(array(
                        'name' => $name,
                        'desc' => $desc,
                        'price' => $price,
                        'country' => $country,
                        'status' => $status,
                        'cat' => $cat,
                        'mem' => $member
                    ));
                    $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted </div>';
                    redirectHome($themsg , 'back');
            }
        }else{
            $themsg = "<div class='alert alert-danger'>you can't acces this page</div>";
            redirectHome($themsg);
        }
        echo '</div>';

    }

    elseif ($do == 'edit'){

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ?");
        $stmt->execute(array($itemid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0){ ?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="?do=update" method="post">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">

                    <div class="form-group row">
                        <label class="col-sm-2">Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="name" class="form-control" required="required"
                                   value="<?php echo $row['name'] ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Description</label>
                        <div class="col-sm-6">
                            <input type="text" name="description" class="form-control"
                                   value="<?php echo $row['description'] ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Price</label>
                        <div class="col-sm-6">
                            <input type="text" name="price" class="form-control" required="required"
                                   value="<?php echo $row['price'] ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Country</label>
                        <div class="col-sm-6">
                            <input type="text" name="country" class="form-control" required="required"
                                   value="<?php echo $row['country_made'] ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Status</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="status">
                                <option value="1" <?php if ($row['status'] == 1){echo 'selected';} ?>>new</option>
                                <option value="2" <?php if ($row['status'] == 2){echo 'selected';} ?>>like new</option>
                                <option value="3" <?php if ($row['status'] == 3){echo 'selected';} ?>>used</option>
                                <option value="4" <?php if ($row['status'] == 4){echo 'selected';} ?>>old</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Member</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="member">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user){
                                    echo "<option value='".$user['userid']."'";
                                    if ($row['member_id'] == $user['userid']){echo 'selected';}
                                    echo ">" . $user['username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Category</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="category">
                                <?php
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat){
                                    echo "<option value='".$cat['id']."'";
                                    if ($row['cat_id'] == $cat['id']){echo 'selected';}
                                    echo ">".$cat['name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-10">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>

                </form>

          <?php
            $stmt = $con->prepare("SELECT comments.* , users.username
                                             FROM comments
                                             INNER JOIN users ON users.userid = comments.user_id
                                             WHERE item_id = ?");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
            if (!empty($rows)){
          ?>
            <h1 class="text-center">Manage [<?php echo $row['name'] ?>] comments</h1>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>User name</td>
                            <td>Add date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['comment'] . '</td>';
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
                <?php
                 }
                ?>
            </div>
            <?php
        }else{
            $themsg = '<div class="alert alert-danger">id not found</div>';
            redirectHome($themsg);
        }

    }

    elseif ($do == 'update'){

        echo '<h1 class="text-center">Update Item</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //get the variables from the form
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];

            //validate the form
            $formerrors = array();
            if (empty($name)){
                $formerrors[] = 'name canot be empty';
            }
            if (empty($price)){
                $formerrors[] = 'price canot be empty';
            }
            if (empty($country)){
                $formerrors[] = 'country canot be empty';
            }
            foreach ($formerrors as $error){
                echo '<div class="alert alert-danger">'.$error . '</div>';
            }

            //check if there is no errors proceed update operation
            if (empty($formerrors)){
                //update database with this info
                $stmt = $con->prepare("UPDATE items 
                                                 SET name = ? ,
                                                     description = ? ,
                                                     price = ? ,
                                                     country_made = ? ,
                                                     status = ? ,
                                                     member_id = ? ,
                                                     cat_id =?
                                                 WHERE item_id = ?");
                $stmt->execute(array($name , $desc , $price , $country , $status , $member , $cat , $id));
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated </div>';
                redirectHome($themsg , 'back');
            }
        }else{
            $themsg = '<div class="alert alert-danger">you canot acces this page </div>';
            redirectHome($themsg);
        }
        echo '</div>';

    }

    elseif ($do == 'delete'){

        echo '<h1 class="text-center">Delete Item</h1>';
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('item_id' , 'items' , $itemid);
        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM items WHERE item_id = :itemid');
            $stmt->bindParam(":itemid" , $itemid);
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

    elseif ($do == 'approve'){

        echo '<h1 class="text-center">Approve Item</h1>';
        echo '<div class="container">';
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('item_id' , 'items' , $itemid);
        if ($check > 0) {
            $stmt = $con->prepare('UPDATE items SET approve = 1 WHERE item_id = ?');
            $stmt->execute(array($itemid));
            $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record approved </div>';
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
ob_end_flush();












