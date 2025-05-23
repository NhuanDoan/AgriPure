<?php 
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ob_start();
    include_once('views/layouts/header.php');
    include_once("views/layouts/nav.php");

    if(isset($_GET['page']))
    {
        $page = $_GET['page'];
    } else {
        $page = 'home';
    }

    if(file_exists('views/pages/'.$page.'/index.php'))
    {
        include_once('views/pages/'.$page.'/index.php');
    } else {
        include_once('views/pages/404/index.php');
    }
    
    include_once("views/layouts/footer.php");
?>
