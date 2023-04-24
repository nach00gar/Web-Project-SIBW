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
    $idScientist = -1;
    if (isset($_GET['idScientist'])) {
        $idScientist = $_GET['idScientist'];
    }

    if( $usuario['tipo'] == "SUPER" or $usuario['tipo'] == "MODERADOR" ){ //RESTRINGIR esta opcion solo a usuarios SUPER o MOD
        $id = $_GET['id'];
        $msg = $_POST['msg_edit'];

        editComment($id, $msg);
    }
    

    header("Location: cientifico.php?id=".$idScientist);


?>