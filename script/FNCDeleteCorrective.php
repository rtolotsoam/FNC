<?php
   require_once ("DBConnect.php");
  
  print_r($_REQUEST);
  $id_liste = $_REQUEST['list_id'];
  $sql ="DELETE from nc_action_liste where id in (select action_liste_id from nc_fnc_action where id in (".$id_liste."))";
  $queryS = @pg_query($sql);
  $sql ="DELETE from nc_fnc_action where id in (".$id_liste.")";
  $queryS = @pg_query($sql);
   
?>