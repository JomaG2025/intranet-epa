<?php

    defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    Actividad::activo($_GET['id']);
    
    setcookie("treeTable","",0);
    
    header("Location: index.php?com=actividad");
  
?>