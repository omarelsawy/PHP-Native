<?php
function lang($phrase){
    static $lang = array(
      //dashboard page
        'home_admin' => 'Home',
        'categories' => 'categories',
        'Items' => 'Items',
        'Members' => 'Members',
        'Comments' => 'Comments',
        'Statistics' => 'Statistics',
        'logs' => 'logs'
    );
    return $lang[$phrase];
}