<?php
  //Conexión a la BBDD
  function conectar() {
    $mysqli = new mysqli("database", "docker", "docker", "docker", $port=3306);
    if ($mysqli->connect_errno) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }
    return $mysqli;
  }
  //Consultamos los datos para la portada
  function getAssets() {
    $mysqli = conectar();
    $resultado = $mysqli->query("SELECT * FROM SCIENTIST");

    $scientists = array();

    while($res = $resultado->fetch_assoc()) {
        $scientists[] = $res;
    }
    return $scientists;
  }

  function getAllUsuarios() {
    $mysqli = conectar();
    $resultado = $mysqli->query("SELECT * FROM USUARIO");
    $usuarios = array();

    while($res = $resultado->fetch_assoc()) {
        $usuarios[] = $res;
    }
    return $usuarios;
  }

  //Consultamos los datos para un científico
  function getScientist($id, $mysqli) {

    //Impedir inyeccion de codigo
    $stmt = $mysqli->prepare("SELECT * FROM SCIENTIST WHERE idScientist=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $res = $stmt->get_result();
    $scientist = array('nombre' => 'ERROR 404', 'contenido' => 'Contenido no encontrado');
    
    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $scientist = array('name' => $row['name'], 'content' => $row['content'], 'fechasnm' => $row['fechas'], 'idScientist' => $row['idScientist'], 'fotoPortada' => $row['fotoPortada']);
    }
    
    return $scientist;
  }
  //Consulta de direcciones para las fotos de la galería
  function getFotos($id, $mysqli) {

    //Impedir inyeccion de codigo
    $stmt = $mysqli->prepare("SELECT * FROM FOTO WHERE idScientist=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $fotos = array();
    
    while($res = $resultado->fetch_assoc()) {
      $fotos[] = $res;
    }
    
    return $fotos;
  }

  //Consulta de comentarios de cada científico
  function getComentarios($id, $mysqli) {
    $stmt = $mysqli->prepare("SELECT * FROM COMENTARIO WHERE idScientist=? ORDER BY fecha");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $comentarios = array();

    while($res = $resultado->fetch_assoc()) {
      $comentarios[] = $res;
    }
    return $comentarios;
  }

  //Agregar cmentario a la BBDD
  function addComment($id, $comentario, $mysqli, $user) {
    $user_msg  = mysqli_real_escape_string($mysqli, $comentario);
    if( !is_numeric($id)){
      $id = -1;
    }
    $fecha = date("Y-m-d H:i:s");
    $idUsuario = $user['idUsuario'];

    //Ejecutamos la peticion de inserción
    $sql = "INSERT INTO COMENTARIO (content, fecha, idUsuario, idScientist) VALUES ('$user_msg', '$fecha', '$idUsuario', $id)";
    $mysqli->query($sql);
  }
  //Consulta de palabras censuradas
  function getCensored() {
    $mysqli = conectar();
    $resultado = $mysqli->query("SELECT palabra FROM CENSURA");

    $palabras = array();

    while($res = $resultado->fetch_assoc()) {
        $palabras[] = $res['palabra'];
    }
    return $palabras;
  }

  function getUsuario($user) {
    $mysqli = conectar();
    $stmt = $mysqli->prepare("SELECT * from USUARIO where username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario="NULL";
    while($res = $resultado->fetch_assoc()) {
      $usuario = $res;
    }
    return $usuario;
  }

  function checkLogin($user, $pass){
    $usuario = getUsuario($user);
    if($usuario === "NULL"){
      return false;
    }
    if (password_verify($pass, $usuario['pass'])){
      return true;
    }
    return false;
  }


  function addUser($username, $pass, $email) {
    $mysqli = conectar();
    $username = mysqli_real_escape_string($mysqli, $username);
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $mail  = mysqli_real_escape_string($mysqli, $email);
    
    $sql = "INSERT INTO USUARIO (username, pass, email, tipo) VALUES ('$username', '$pass', '$mail', 'USER')";
    $mysqli->query($sql);
  }

  function modifyUser($olduser, $newuser, $email) {
    $mysqli = conectar();
    //Ponemos las variables del usuario de forma segura
    $olduser = mysqli_real_escape_string($mysqli, $olduser);
    $email  = mysqli_real_escape_string($mysqli, $email);
    $newuser  = mysqli_real_escape_string($mysqli, $newuser);
    

    //Ejecutamos la peticion de inserción
    $sql = "UPDATE USUARIO SET username = '$newuser', email='$email' WHERE username = '$olduser'";
    $mysqli->query($sql);
  }

  function grantPermission($id, $tipo) {
    $mysqli = conectar();

    $sql = "UPDATE USUARIO SET tipo = '$tipo' WHERE idUsuario = '$id'";
    $mysqli->query($sql);
  }

  function deleteComment($id) {
    $mysqli = conectar();
    if( !is_numeric($id)){
      $id = -1;
    }
    
    $sql = "DELETE FROM COMENTARIO WHERE idComentario='$id'";
    $mysqli->query($sql);
  }


  function editComment($id, $msg) {
    $mysqli = conectar();
    $msg_  = mysqli_real_escape_string($mysqli, $msg);
    if( !is_numeric($id)){
      $id = -1;
    }
    
    $sql = "UPDATE COMENTARIO SET content='$msg_', editado=true WHERE idComentario='$id'";
    $mysqli->query($sql);
  }


    
  function uploadPhotos($peticion, $id, $archivos){
    $mysqli = conectar();
    $targetDir = "img/"; 
    $allowTypes = array('jpg','png','jpeg');

    $fileNames = array_filter($archivos['files']['name']);
    
    if(!empty($fileNames)){
     
      foreach($archivos['files']['name'] as $key=>$val){
        $fileName = date("Y-m-d-H-i-s") . basename($_FILES['files']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        //Comprobar si el fichero es valido
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if(in_array($fileType, $allowTypes)){
          //Moverlo a la carpeta
          //print "SI, " . $targetFilePath . "\n";
          if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){
            echo "SI, " . $targetFilePath . "\n";
            $sql = "INSERT INTO FOTO (direccion, idScientist) VALUES ('$targetFilePath', '$id')";
            $mysqli->query($sql);
          }
        }
      }
    }
  }

  function deletePhoto($id) {
    $mysqli = conectar();
    if( !is_numeric($id)){
      $id = -1;
    }
    
    $sql = "DELETE FROM FOTO WHERE idFoto='$id'";
    $mysqli->query($sql);
  }
  
  function editScientist($id, $hashtags) {
    $mysqli = conectar();
    $content  = mysqli_real_escape_string($mysqli, $_POST['content']);
    $nombre = mysqli_real_escape_string($mysqli, $_POST['name']);
    if( !is_numeric($id)){
      $id = -1;
    }
    
    $sql = "UPDATE SCIENTIST SET content='$content', name='$nombre' WHERE idScientist='$id'";
    $mysqli->query($sql);

    //Añadir las etiquetas
    $sql = "DELETE FROM HASHTAG WHERE idScientist='$id'";
    $mysqli->query($sql);
    foreach ($etiquetas as &$etiqueta){
      $sql = "INSERT INTO HASHTAG (hashtag, idScientist) VALUES ('$etiqueta', '$id')";
      $mysqli->query($sql);
    }
  }

  function deleteScientist($id) {
    $mysqli = conectar();
    if( !is_numeric($id)){
      $id = -1;
    }

    $sql = "DELETE FROM  FOTO WHERE idScientist='$id'";
    $mysqli->query($sql);
    $sql = "DELETE FROM  COMENTARIO WHERE idScientist='$id'";
    $mysqli->query($sql);
    $sql = "DELETE FROM  HASHTAG WHERE idScientist='$id'";
    $mysqli->query($sql);
    $sql = "DELETE FROM  SCIENTIST WHERE idScientist='$id'";
    $mysqli->query($sql);
  }


  function uploadPrincipalPhoto(){
    $targetDir = "img/"; 
    $fileName = date("Y-m-d-H-i-s") . basename($_FILES['fileToUpload']['name']);
    $targetFilePath = $targetDir . $fileName;

    $allowTypes = array('jpg','png','jpeg');
    $imageFileType = strtolower(pathinfo($targetFilePath,PATHINFO_EXTENSION));
    if(in_array($imageFileType, $allowTypes)){
    
      if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)){

      }
      else{
        echo "Hay algun error";
      }
    }
    return $targetFilePath;
    
    
  }

  function addScientist($etiquetas) {
    $mysqli = conectar();
    $contenido  = mysqli_real_escape_string($mysqli, $_POST['content']);
    $nombre = mysqli_real_escape_string($mysqli, $_POST['name']);
    $fotoPortada = uploadPrincipalPhoto();


    $sql= "INSERT INTO SCIENTIST (name, content, fotoPortada) VALUES ('$nombre', '$content', '$fotoPortada')";
    $mysqli->query($sql);

    $sql= "SELECT MAX(idScientist) AS idScientist FROM SCIENTIST";
    $resultado = $mysqli->query($sql);
    $res = $resultado->fetch_assoc();

    $id = $res['idScientist'];

    //Añadir las etiquetas
    $sql = "DELETE FROM HASHTAG WHERE idScientist='$id'";
    $mysqli->query($sql);
    foreach ($etiquetas as &$etiqueta){
      $sql = "INSERT INTO HASHTAG (hashtag, idScientist) VALUES ('$etiqueta', '$id')";
      $mysqli->query($sql);
    }

    return $id;
  }

  function getTags($id) {
    $mysqli = conectar();
    $resultado = $mysqli->query("SELECT * FROM HASHTAG WHERE idScientist='$id'");
    $etiquetas = array();

    while($res = $resultado->fetch_assoc()) {
        $etiquetas[] = $res;
    }
    return $etiquetas;
  }

?>