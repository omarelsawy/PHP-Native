    <?php
    include 'connect.php';
    //routes
    $tpl = 'includes/templates/';
    $lang = 'includes/languages/';
    $func = 'includes/functions/';
    $css = 'layout/css/';
    $js = 'layout/js/';


    //include the important files
    include $func."functions.php";
    include $lang.'english.php';
    include $tpl."/header.php";
    //include navbar on all pages exept the one with $nonavbar variable
    if (!isset($nonavbar)){
        include $tpl."/navbar.php";
    }

