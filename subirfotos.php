<?php
    require_once "vendor/autoload.php"; 
    include("queryingdb.php"); 
    
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $usuario = array();
    if (isset($_SESSION['user'])) {
        $usuario = getUsuario($_SESSION['user']); 
    }


    

    if( $usuario['tipo'] == "SUPER" or $usuario['tipo'] == "GESTOR" ){ 
        $idEv = -1;
        if (isset($_GET['id'])) { //EDITAR UN EVENTO
            $id = $_GET['id'];
        }
        $archivos = $_FILES;
        uploadPhotos($_REQUEST, $id, $archivos);
        //else { //AÑADIR UN EVENTO

        //}
    }
    

    header("Location: gestorcientificos.php?id=".$id);


?>