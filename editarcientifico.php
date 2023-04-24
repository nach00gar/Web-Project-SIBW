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
    
    
    if( $usuario['tipo'] == "SUPER" or $usuario['tipo'] == "GESTOR" ){ //RESTRINGIR esta opcion solo a usuarios SUPER 
        $id = -1;
        if(isset($_POST['hashtags'])){
            $hashtags = $_POST['hashtags'];
            if($hashtags != ""){
               $hashtags = explode(', ', $hashtags); 
            }
            else{
                $hashtags = array();
            }  
        }

        if (isset($_GET['id'])) { //Se edita 
            $id = $_GET['id'];
            
            editScientist($id, $hashtags);

        }
        else { //Se añade un nuevo
            $id = addScientist($hashtags);
        }
    }
    
    header("Location: gestorcientificos.php?id=".$id);

?>