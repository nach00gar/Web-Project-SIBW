<?php
    include("queryingdb.php"); 
    
    $palabras = getCensored();
    echo json_encode($palabras);
    
?>