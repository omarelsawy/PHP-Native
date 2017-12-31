<?php
session_start();
$pagetitle = 'show';
include "init.php";

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

$stmt = $con->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if ($count > 0){
    $row = $stmt->fetch();
  ?>
    <h1 class="text-center"><?php echo $row['name']; ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
               <h2><?php echo $row['name'] ?></h2>
                <p><?php echo $row['description'] ?></p>
                <div><?php echo $row['price']?></div>
                <div><?php echo $row['country_made']?></div>
                <div>category:
                    <?php
                       $cat = getCats('where' , 'id' , $row['cat_id']);
                       //$cat will return 2 dimentional array
                       $name = $cat[0]['name'];
                       echo $name;

                       /*using json array
                         $strjson = json_encode($cat);
                          $arrjson = json_decode($strjson , true);
                          echo $arrjson[0]['name'];*/

                        /*//using json object
                        $strjson = json_encode($cat);
                        $arrjson = json_decode($strjson);
                        //echo $arrjson[0]->name;*/

                    ?>
                </div>
                <div>Added by:
                    <?php
                    $user = getUser($row['member_id']);
                    echo $user['username'];
                    ?>
                </div>
            </div>
            <div class="col-md-3">
               <img class="img-thumbnail" src="screenshot-png-jpg.png">
            </div>
        </div>
        <hr>
        <div>
            <?php if (isset($_SESSION['user'])){ ?>
            <h3>Add comment</h3>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <textarea name="comment" class="form-control"></textarea>
                <input type="submit" value="comment">
            </form>
        </div>
        <?php
         if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             echo $_POST['comment'];
         }
        }else{
            echo 'must login to add comment';
        }
        ?>
        <hr>
        <div class="row">
            <div class="col-md-3">
                image
            </div>
            <div class="col-md-9">
                comment
            </div>
        </div>

        <!-- if you add tag column in input field each tag separated by comma
              explode function convert string separated with char to array
              $str = 'omar,ali,ngm';
              $arr = explode( ',' , $str);
              foreach ($arr as $tag){
              $tag = str_replace(' ' , '' , $tag);
              if (!empty($tag)) {
                 echo "<a href='?$tag'>$tag</a>";
               }
               $ar represent the tag we pass it through url and search in db with same tag
                SELECT column from table WHERE tag LIKE '%$tag%' -->

    </div>

    <?php

    }else {
        echo '<div class="alert alert-dark">there is no Ad with this id</div>';
      }
?>

<?php
include $tpl."footer.php";

