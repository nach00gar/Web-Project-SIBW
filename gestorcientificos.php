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
        $id = -1;
        if (isset($_GET['id'])) { //Editar un evento
            $id = $_GET['id'];
            $mysqli = conectar();
            //Evento
            $scientist = getScientist($id, $mysqli);
            //Fotos
            $fotos = getFotos($id, $mysqli);
            var_dump($fotos);
            $action = "editar";
            //Etiquetas
            $etiquetas = getTags($id);

            echo $twig->render('gestion_cientificos.twig', ['act' => $action, 'usuario' => $usuario, 'scientist' => $scientist, 'fotos' => $fotos, 'hashtags' => $etiquetas ]);
        }
        else { //Crear un nuevo evento

            echo $twig->render('gestion_cientificos.twig', ['act' => $action, 'usuario' => $usuario]);
        }

        #echo "Esta funcionando " . $id;
    }
    

    //header("Location: evento.php?ev=".$idEv);


?>