<?php 
    require_once ("DBConnect.php");
    
   $etat = $_REQUEST['etat'];
   $idfnc = $_REQUEST['idfnc'];
   $id_infos = $_REQUEST['id_inf'];
  if(isset($_REQUEST['etat']) && isset($_REQUEST['idfnc']))
  {  
     $sql = "update nc_fnc_infos SET etat = '".$etat."' where id = (select fnc_info_id from nc_fnc_action where fnc_id = '".$idfnc."')";
     $queryS = @pg_query($sql);
  }
  
  $type =   trim($_REQUEST['comment']);
   // $date_fin = ;
   // $date_suivi = ;
   $etat = 0;
  
   switch($type)
   {
    
      case 'comment_only':
         $content_comment = $_REQUEST['data'];
         $content_comment = pg_escape_string(utf8_decode($_REQUEST['data']));
         $sql = "update nc_fnc_infos SET commentaire = '".$content_comment."' where id =".$id_infos;
         $query = @pg_query($sql);
         $etat = 1;
         if (!$query) 
         {
             $etat = 0;
         }
         
         break;
         
      case 'date_fin':
         $date_fin = $_REQUEST['data'];
         $sql = "update nc_fnc_infos SET date_fin = '".$date_fin."' where id =".$id_infos;
         $query = @pg_query($sql);
         $etat = 1;
         if (!$query) 
         {
             $etat = 0;
         }
         
         break;
         
      case 'date_suivi': 
         $date_suivi = $_REQUEST['data'];
         $sql = "update nc_fnc_infos SET date_suivi = '".$date_suivi."' where id =".$id_infos;
         $query = @pg_query($sql);
         $etat = 1;
         if (!$query) 
         {
             $etat = 0;
         }
         break;
   }
   
  echo $etat;
?>