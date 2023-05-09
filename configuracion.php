<?php
    require_once "vendor/autoload.php"; 
    include("queryingdb.php"); 
    
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $action = "misDatos";
    
    if (isset($_GET['act'])){
        $action = $_GET['act'];
    }
    session_start();
    $usuario = array();
    if (isset($_SESSION['user'])) {
        $usuario = getUsuario($_SESSION['user']);
        
    }

    switch ($action) {
        case "permisos":
            $usuarios = getAllUsuarios();
            echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'users' => $usuarios]);
            break;

        case "comentarios":
            $scientists = getAssets();
            $comentarios = getAllComentarios();
            echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'scientists' => $scientists, 'comentarios' => $comentarios]);
            break;

        case "gestorcientificos":
            $scientists = getAssets();
            echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'scientists' => $scientists]);
            break;

        default:
            echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario]);
            break;
        
    }
    
    
?>