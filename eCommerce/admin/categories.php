<?php
ob_start();
session_start();
$pagetitle = 'Categories';
if (isset($_SESSION['username'])) {
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if ($do == 'manage'){
        $sort = 'ASC';
        $sort_array = array('ASC' , 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
          $sort = $_GET['sort'];
        }
        $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll();
     ?>

        <h1 class="text-center">Manage members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#id</td>
                        <td>name</td>
                        <td>description</td>
                        <td>visible</td>
                        <td>comments</td>
                        <td>ads</td>
                        <td>Control</td>
                        <td>
                            <div class="option">
                            ordering
                            <a href="?sort=ASC" class="<?php if ($sort == 'ASC'){echo 'active';} ?>">ASC</a>
                            <a href="?sort=DESC"class="<?php if ($sort == 'DESC'){echo 'active';} ?>">DESC</a>
                            View:
                             <!-- classic will hide elements with view2 class and full will appear them-->
                            <span class="active" data-view="full" style="cursor: pointer;color: #005cbf">Full</span> |
                            <span data-view="classic" style="cursor: pointer;color: #005cbf">Classic</span>
                            </div>
                        </td>
                    </tr>

                    <?php
                    foreach ($cats as $cat){
                        echo '<tr>';
                        echo '<td>' . $cat['id'] . '</td>';
                        echo '<td>' . $cat['name'] . '</td>';
                        echo '<td class="view2 classic">' . $cat['description'] . '</td>';
                        echo '<td class="view2 classic">' . $cat['visibility'] . '</td>';
                        echo '<td class="view2 classic">' . $cat['allow_comments'] . '</td>';
                        echo '<td class="view2 classic">' . $cat['allow_ads'] . '</td>';
                        echo "<td class='view2 classic'>
                                       <a href='?do=edit&id=" . $cat['id'] ."' class='btn btn-success'>Edit</a>
                                       <a href='?do=delete&id=" . $cat['id'] ."' class='btn btn-danger confirm'>Delete </a>";
                        //this btn will make it visible
                        if ($cat['visibility'] == 1){
                            echo "<a href='categories.php?do=visible&id=" . $cat['id'] ."' class='btn btn-sm btn-info' style='margin-left: 10px'>Enable Vis</a>";
                        }
                        //this btn will allow comment
                        if ($cat['allow_comments'] == 1){
                            echo "<a href='categories.php?do=comment&id=" . $cat['id'] ."' class='btn btn-sm btn-info' style='margin-left: 10px; margin-top: 5px'>Enable Comm</a>";
                        }

                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>

                </table>
            </div>
            <a href="?do=add" class="btn btn-primary">Add cat</a>
        </div>

     <?php
     }
    elseif ($do == 'add'){
       ?>

        <h1 class="text-center">Add category</h1>
        <div class="container">
            <form action="?do=insert" method="post">
                <div class="form-group row">
                    <label class="col-sm-2">Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Description</label>
                    <div class="col-sm-6">
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Ordering</label>
                    <div class="col-sm-6">
                        <input type="text" name="ordering" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Visible</label>
                    <div class="col-sm-6">
                       <div>
                           <input id="vis-yes" type="radio" name="visible" value="0" checked>
                           <label for="vis-yes">Yes</label>
                       </div>
                        <div>
                            <input id="vis-no" type="radio" name="visible" value="1">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Comment</label>
                    <div class="col-sm-6">
                        <div>
                            <input id="com-yes" type="radio" name="comment" value="0" checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="comment" value="1">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Ads</label>
                    <div class="col-sm-6">
                        <div>
                            <input id="ad-yes" type="radio" name="ads" value="0" checked>
                            <label for="ad-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ad-no" type="radio" name="ads" value="1">
                            <label for="ad-no">No</label>
                        </div>
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
            echo '<h1 class="text-center">Add category</h1>';
            echo '<div class="container">';
            //get the variables from the form
            $name = $_POST['name'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'];
            $visible = $_POST['visible'];
            $comment = $_POST['comment'];
            $ads = $_POST['ads'];

                //check if cat exist in database
                $check = checkItem('name' , 'categories' , $name);
                if ($check == 1){
                    $themsg = '<div class="alert alert-danger">conat add cat exist</div>';
                    redirectHome($themsg , 'back');
                }else{
                    //insert into database with this info
                    $stmt = $con->prepare("INSERT INTO categories(name , description , ordering , visibility , allow_comments , allow_ads) 
                                                     VALUES(:name , :desc , :order , :vis , :com , :ads)");
                    $stmt->execute(array(
                        'name' => $name,
                        'desc' => $description,
                        'order' => $ordering,
                        'vis' => $visible,
                        'com' => $comment,
                        'ads' => $ads
                    ));
                    $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record inserted </div>';
                    redirectHome($themsg , 'back');
                }
        }else{
            $themsg = "<div class='alert alert-danger'>you can't acces this page</div>";
            redirectHome($themsg , 'back');
        }
        echo '</div>';

    }

    elseif ($do == 'edit'){

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

        $stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0){ ?>

            <h1 class="text-center">Edit cat</h1>
            <div class="container">
                <form action="?do=update" method="post">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="form-group row">
                        <label class="col-sm-2">Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="name" class="form-control" value="<?php echo $row['name']?>" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">desc</label>
                        <div class="col-sm-6">
                            <input type="text" name="description" class="form-control" value="<?php echo $row['description'] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Ordering</label>
                        <div class="col-sm-6">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $row['ordering'] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Visible</label>
                        <div class="col-sm-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visible" value="0" <?php if ($row['visibility'] == 0) {echo 'checked';} ?>>
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visb-no" type="radio" name="visible" value="1" <?php if ($row['visibility'] == 1) {echo 'checked';} ?>>
                                <label for="visb-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Comment</label>
                        <div class="col-sm-6">
                            <div>
                                <input id="comm-yes" type="radio" name="comment" value="0" <?php if ($row['allow_comments'] == 0) {echo 'checked';} ?>>
                                <label for="comm-yes">Yes</label>
                            </div>
                            <div>
                                <input id="comm-no" type="radio" name="comment" value="1" <?php if ($row['allow_comments'] == 1) {echo 'checked';} ?>>
                                <label for="comm-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2">Ads</label>
                        <div class="col-sm-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($row['allow_ads'] == 0) {echo 'checked';} ?>>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if ($row['allow_ads'] == 1) {echo 'checked';} ?>>
                                <label for="ads-no">No</label>
                            </div>
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


    }

    elseif ($do == 'update'){

        echo '<h1 class="text-center">Update cat</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //get the variables from the form
            $id2 = $_POST['id'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visible2 = $_POST['visible'];
            $comment2 = $_POST['comment'];
            $ads2 = $_POST['ads'];

                //update database with this info
                $stmt = $con->prepare("UPDATE categories SET name = ? , description = ? , ordering = ? , visibility = ? , allow_comments = ? , allow_ads = ? WHERE id = ?");
                $stmt->execute(array($name , $desc , $order , $visible2 , $comment2 , $ads2 , $id2));
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record updated </div>';
                redirectHome($themsg , 'back');

        }else{
            $themsg = '<div class="alert alert-danger">you canot acces this page </div>';
            redirectHome($themsg);
        }
        echo '</div>';

    }

    elseif ($do == 'delete'){

        echo '<h1 class="text-center">Delete cat</h1>';
        echo '<div class="container">';
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $check = checkItem('id' , 'categories' , $id);
        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM categories WHERE id = :id');
            $stmt->bindParam(":id" , $id);
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

    //allow visibility
    elseif ($do == 'visible'){

        echo '<h1 class="text-center">visible cat</h1>';
        echo '<div class="container">';
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $check = checkItem('id' , 'categories' , $id);
        if ($check > 0) {
            $stmt = $con->prepare('UPDATE categories SET visibility = 0 WHERE id = ?');
            $stmt->execute(array($id));
            $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' record visible </div>';
            redirectHome($themsg , 'back');
        }
        else{
            $themsg = '<div class="alert alert-danger">No such id</div>';
            redirectHome($themsg);
        }
        echo '</div>';

    }

    //allow comments
    elseif ($do == 'comment'){

        echo '<h1 class="text-center">Allow comment</h1>';
        echo '<div class="container">';
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $check = checkItem('id' , 'categories' , $id);
        if ($check > 0) {
            $stmt = $con->prepare('UPDATE categories SET allow_comments = 0 WHERE id = ?');
            $stmt->execute(array($id));
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
ob_end_flush();