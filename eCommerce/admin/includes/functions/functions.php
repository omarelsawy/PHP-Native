<?php
/*title function that echo the page title in case the page
*has the variable $pagetitle and echo default title for other pages*/
function getTitle(){
  global $pagetitle;
  if (isset($pagetitle)){
      echo $pagetitle;
  }else{
      echo 'default';
  }
}

function redirectHome($themsg , $url=null , $seconds = 3){
    if ($url === null){
        $url = 'index.php';
    }else{
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ){
            $url = $_SERVER['HTTP_REFERER'];
        }else{
            $url = 'index.php';
        }
    }
    echo $themsg;
    echo "<div class='alert alert-info'>you will be redirect after $seconds seconds</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

//function to check item in database
function checkItem($select , $from , $value){
    global $con;
    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statment->execute(array($value));
    $count = $statment->rowCount();
    return $count;
}

//count number of item functions
function countItems($item , $table){
    global $con;
    $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}

//get latest records function
function getLatest($select , $table , $order , $limit = 5){
    global $con;
    $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getstmt->execute();
    $rows = $getstmt->fetchAll();
    return $rows;
}






























