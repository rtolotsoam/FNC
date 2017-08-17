<?php
   require_once ("DBConnect.php");
  
  $id_liste = $_REQUEST['list_id'];
  echo $sql ="DELETE from nc_action where id action_id in (".$id_liste."))";
  $queryS = @pg_query($sql);
 
   
?>