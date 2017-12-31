<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title><?php getTitle() ?></title>
      <link rel="stylesheet" href="<?php echo $css?>bootstrap.min.css">
      <link rel="stylesheet" href="<?php echo $css?>font-awesome.min.css">
      <!--<link rel="stylesheet" href=" echo $css jquery-ui.css">-->
      <!--<link rel="stylesheet" href=" echo $css jquery.selectBoxIt.css">-->
      <link rel="stylesheet" href="<?php echo $css?>front.css">
  </head>
<body>

<div class="upper-bar">
    <div class="container">
        <a href="login.php"></a>
        <span class="pull-right login2">
                <?php if (isset($_SESSION['user'])){ ?>
                   <div class="btn-group">
                     <span class="btn dropdown-toggle" data-toggle="dropdown">
                         <?php echo $sessionUser?>
                     </span>
                       <ul class="dropdown-menu">
                           <li><a href="profile.php">Myprofile</a></li>
                           <li><a href="items.php">new item</a></li>
                           <!-- this will go to id with name myads direct -->
                           <li><a href="profile.php#myads">my items</a></li>
                           <li><a href="logout.php">logout</a></li>
                       </ul>
                   </div>
                    <?php
                    $count2 = checkUserStatus($sessionUser);
                    if ($count2 == 1){
                        //user not active
                    }
                }else{
                    echo "<a href='login.php'>Login/Signup</a>";
                } ?>
        </span>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
        <ul class="navbar-nav mr-auto cat-nav">
            <?php
            foreach (getCats() as $cat){
            echo "<li class='cat-item'>
                  <a href='categories.php?catid=".$cat['id']."&catname=".str_replace(' ','-',$cat['name'])."'>
                  ".$cat['name']."</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>




