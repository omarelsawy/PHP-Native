<?php
ob_start(); //output buffering start
session_start();
if (isset($_SESSION['username'])){
    $pagetitle = 'dashboard';
    include "init.php";
    ?>

      <div class="container home-state text-center">
          <h1>Dashboard</h1>
          <br><br>
          <div class="row">
              <div class="col-md-3">
                  <div class="stat" style="background-color: #b8daff">
                      Members<br><br>
                      <span><a href="members.php"><?php echo countItems('userid' , 'users') ?></a></span>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="stat" style="background-color: #b8daff">
                      Pending members<br><br>
                      <span><a href="members.php?do=manage&page=pending">
                              <?php echo checkItem("regstatus" , "users" , "0")?>
                          </a></span>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="stat" style="background-color: #b8daff">
                      Items<br><br>
                      <span><a href="items.php"><?php echo countItems('item_id' , 'items') ?></a></span>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="stat" style="background-color: #b8daff">
                      Comments<br><br>
                      <span><a href="comments.php"><?php echo countItems('c_id' , 'comments') ?></a></span>
                  </div>
              </div>
          </div>
      </div>
    <br>
    <hr>
    <br>
    <div class="container latest">
       <div class="row">
         <div class="col-sm-4" style="margin-right: 20px; background-color: #efeaea">
             <div>
               <div class="par-span">
                   <span class="toggle-info pull-right">
                       <i class="fa fa-plus fa-lg"></i>
                   </span>
                   <i class="fa fa-users"></i>
                   Latest registerd users
               </div>
                 <div class="panel-body">
                     <ul class="list-unstyled">
                     <?php
                         $thelatest = getLatest("*" , "users" , "userid" ,"4");
                         foreach ($thelatest as $latest){
                             if ($latest['regstatus'] == 0){
                                 echo "<a href='members.php?do=active&userid=" . $latest['userid'] ."' class='btn btn-info pull-right' style='margin-left: 10px'> Active </a>";
                             }
                         echo '<i>' . $latest['username'].'<span class="btn btn-success pull-right">
                          <a href="members.php?do=edit&userid='. $latest['userid'] .'"> Edit </a>
                         </span></i><br><br>';
                         }
                     ?>
                     </ul>
                 </div>
             </div>
         </div>

           <div class="col-sm-4" style="background-color: #efeaea">
               <div>
                   <div>
                       <i class="fa fa-tag"></i>Latest items
                   </div>
                   <br>
                   <div>
                       <ul class="list-unstyled">
                           <?php
                           $thelatest = getLatest("*" , "items" , "item_id" ,"3");
                           foreach ($thelatest as $latest){
                               if ($latest['approve'] == 0){
                                   echo "<a href='items.php?do=approve&itemid=" . $latest['item_id'] ."' class='btn btn-info pull-right' style='margin-left: 10px'> Approve </a>";
                               }
                               echo '<i>' . $latest['name'].'<span class="btn btn-success pull-right">
                          <a href="items.php?do=edit&itemid='. $latest['item_id'] .'"> Edit </a>
                         </span></i><br><br>';
                           }
                           ?>
                       </ul>
                   </div>
               </div>
           </div>

       </div>
    </div>

   <?php include $tpl."/footer.php";
}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();






