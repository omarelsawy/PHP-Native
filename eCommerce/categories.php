<?php
session_start();
include "init.php"; ?>

<div class="container">
    <h1 class="text-center"><?php echo str_replace('-' , ' ' , $_GET['catname'])?></h1>
    <div class="row">
    <?php foreach (getItems('cat_id' , $_GET['catid']) as $item){
        echo "<div class='col-sm-6 col-md-4'>";
           echo "<div class='thumbnail itm'>";
            echo"<span>".$item['price']."</span>";
             echo "<img src='' alt=''>";
             echo "<div class='caption'>";
               echo"<h3>".$item['name']."</h3>";
               echo "<p>".$item['description']."</p>";
             echo "</div>";
           echo "</div>";
        echo "</div>";
    } ?>
    </div>
</div>

<?php include $tpl."footer.php"; ?>

