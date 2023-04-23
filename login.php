<?php
    require_once "vendor/autoload.php"; 
    include("queryingdb.php"); 

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $action = "login";
  
    if (isset($_GET['act'])){
        $action = $_GET['act'];
    }
    session_start();
    $usuario = array();

    if (isset($_SESSION['user'])) {
        $usuario = getUsuario($_SESSION['user']);
        
    }
    

    echo $twig->render('loginpage.twig', ['act' => $action, 'usuario' => $usuario]);
    
    
?>