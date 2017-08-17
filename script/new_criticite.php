<?php
   //session_start() ;
	//require_once("script/DBConnect.php") ;
   include ("/var/www.cache/dgconn.inc") ;
   
   if($_REQUEST['grav_id'] != '')
      $slctGravite   = $_REQUEST['grav_id'] ;
   else
      $slctGravite = 1 ;
      
   if($_REQUEST['freq_id'] != '')
      $slctFrequence = $_REQUEST['freq_id'] ;
   else
      $slctFrequence = 1 ;
  
   // Selection d'id pour la gravité
  $sqlGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav = '$slctGravite' " ;
   
   $resGrv = @pg_query ($conn,$sqlGrv) or die (pg_last_error($conn));
   $arGrv = @pg_fetch_array($resGrv) ;
   $id_grav = $arGrv['echelle_id_grav'] ;
   
   // Selection d'id pour la fréquence
   $sqlFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = '$slctFrequence' " ;
   $resFrq = @pg_query ($conn,$sqlFrq) or die (pg_last_error($conn)) ;
   $arFrq = @pg_fetch_array($resFrq) ;
   $id_freq = $arFrq['echelle_id_freq'] ;
   
   //echo $id_grav."-".$id_freq ;
   
   
   // Affichage criticité
   if ($id_grav == 1)
      $criticite = "m" ;
   elseif ($id_grav == 2 && $id_freq <= 2)
      $criticite = "m" ;
   elseif ($id_grav == 2 && $id_freq >= 3)
      $criticite = "M" ;
   elseif ($id_grav == 3 && $id_freq < 4)
      $criticite = "M" ;
   elseif ($id_grav == 3 && $id_freq >= 4)   
      $criticite = "C" ;
   elseif($id_grav >= 4)
      $criticite = "C" ;
   
      
   // test de couleur
   if ($criticite == "m")
   {
      $color = "style='backgroundColor:#FCF03F;font-weight:bold;width:60px;'" ;
      $criticite = "mineure" ;
   }
   elseif ($criticite == "M")
   {
      $color = "style='backgroundColor:#F28810;font-weight:bold;width:60px;'" ;
      $criticite = "Majeure" ;
   }
   elseif ($criticite == "C")
   {
      $color = "style='backgroundColor:#E71D07;color:#FFFFFF;font-weight:bold;width:60px;'" ;
      $criticite = "Critique" ;
   }   

   echo $criticite ;
   
?>