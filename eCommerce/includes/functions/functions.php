<?php

//get records function and could return on cat
function getCats($where = '' , $id = '' , $val = ''){
    $equal = '';
    if ($where && $id) {
      $where = 'WHERE';
      $id = 'id';
      $equal = '=';
    }
    global $con;
    $getstmt = $con->prepare("SELECT * FROM categories $where $id $equal $val ORDER BY id ASC");
    $getstmt->execute();
    $rows = $getstmt->fetchAll();
    return $rows;
}

function getItems($where , $value){
    global $con;
    $getstmt = $con->prepare("SELECT * FROM items WHERE $where = $value ORDER BY item_id DESC");
    $getstmt->execute();
    $rows = $getstmt->fetchAll();
    return $rows;
}

//check if user not active
function checkUserStatus($user){
    global $con;
    $stmt = $con->prepare("SELECT username
                                     FROM users 
                                     WHERE username = ?
                                     AND regstatus = 0");
    $stmt->execute(array($user));
    $count = $stmt->rowCount();
    return $count;
}

function getUser($val){
    global $con;
        $stmt = $con->prepare("SELECT username FROM users WHERE userid = $val");
        $stmt->execute();
        $user = $stmt->fetch();
        return $user;
}









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






























