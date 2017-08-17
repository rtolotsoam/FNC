<?php
 
  	require_once("/var/www.cache/dgconn.inc") ;
   if(isset($_REQUEST['fnc_id']) && $_REQUEST['fnc_id'] != '')
   {
      $state = 1;
      $txtFaille = $_REQUEST['txtFaille'];
      $txtImpact = $_REQUEST['txtImpact'];
      $txtDateDeb = $_REQUEST['txtDateDeb'];
      $txtAction = $_REQUEST['txtAction'];
      $txtResponsable = $_REQUEST['txtResponsable'];
      $txtDateFin = $_REQUEST['txtDateFin'];
      $txtGeneral = $_REQUEST['txtGeneral'];
      
     
      $iFncId = $_REQUEST['fnc_id'];
      $aFncRef = @pg_fetch_array(@pg_query($conn, "SELECT fnc_ref FROM nc_fiche WHERE fnc_id = '{$iFncId}';"));
     
      $sFaille = pg_escape_string (trim($_REQUEST['txtFaille'])) ;
      $sImpact = pg_escape_string (trim($_REQUEST['txtImpact'])) ;
      $sActionLibelle = pg_escape_string (trim($_REQUEST['txtAction'])) ;
      $sDateDeb = trim($_REQUEST['txtDateDeb']) ;
      $iResponsable = trim($_REQUEST['txtResponsable']) ;
      $sDateFin = $_REQUEST['txtDateFin'] ;
      $sGeneralisation = pg_escape_string (trim($_REQUEST['txtGeneral'])) ;
      $sEtat = pg_escape_string ("en attente") ;
      $sType = "corrective / preventive" ;
      
      $bIndicateurEfficacite = pg_escape_string (trim($_REQUEST['txtEfficacite'])) ;
      $bObjectifEcheance = pg_escape_string (trim($_REQUEST['txtObjectif'])) ;
      
      /**
      * rechercher dans la base si l'action donn�e existe
      */
      $sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";

      $rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn)); 
   
      $iActionNb = @pg_num_rows($rQueryFindAction);
      
      $iResponsable = utf8_decode($iResponsable);
      $sFaille = utf8_decode($sFaille);
      $bObjectifEcheance = utf8_decode($bObjectifEcheance);
      $bIndicateurEfficacite = utf8_decode($bIndicateurEfficacite);
      $sGeneralisation = utf8_decode($sGeneralisation);
      $sImpact = utf8_decode($sImpact);
      $sActionLibelle = utf8_decode($sActionLibelle);
      $sType = utf8_decode($sType);
     
			/**
			 * s'il n'existe pas, faire une insertion
			 */  
      if ($iActionNb == 0)
      {
         $sSqlInsertAction = "INSERT INTO nc_action_liste (libelle, type) VALUES ('{$sActionLibelle}', '{$sType}');";
         $rQueryInsertAction = @pg_query($conn, $sSqlInsertAction) or die(@pg_last_error($conn));
      }
      
      /**
			 * récupérer l'identifiant de l'action
			 */
			$sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
						
			$rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn));
			
			$aResActionId = @pg_fetch_array($rQueryFindAction);
			$iFindActionId = $aResActionId['id'];
						
			/**
			 * sélectionner l'id de l'action donnée dans la base
			 */
			$sSqlSelectActionId = "SELECT * FROM nc_fnc_action WHERE fnc_id = '{$iFncId}' AND action_liste_id = '{$iFindActionId}' ; ";
			
			$rQuerySelectActionId = @pg_query($conn, $sSqlSelectActionId) or die(@pg_last_error($conn));
			$iIdActionNb = @pg_num_rows($rQuerySelectActionId) ;
         
         			/**
			 * faire une insertions dans la tables nc_fnc_action pour la fiche en question
			 */
			
       
			if ($iIdActionNb == 0)
         {
				$sSqlInsertFncInfo = "	INSERT INTO nc_fnc_infos ( date_debut, 
															date_fin, responsable, faille_identifiee, impact, 
															generalisation, etat,indic_efficacite,obj_echeance) 
											VALUES	( '{$sDateDeb}', 
													'{$sDateFin}', '{$iResponsable}', '{$sFaille}', 
													'{$sImpact}', '{$sGeneralisation}', '{$sEtat}', 
                                       '{$bIndicateurEfficacite}','{$bObjectifEcheance}') ; " ;
				
				$rQueryInsertFncInfo = @pg_query($conn, $sSqlInsertFncInfo) or die(@pg_last_error($conn)) ;
				
				$sSqlSelectInfoId = " SELECT last_value as lastval from nc_fnc_infos_id_seq ";
				$rLastId = @pg_query($conn, $sSqlSelectInfoId) or die(@pg_last_error($conn));
            
				$aResLastId= @pg_fetch_array($rLastId);
				$last_Id= $aResLastId['lastval'];
				
				$sSqlInsertFncAction= "	INSERT INTO nc_fnc_action ( action_liste_id, fnc_id, fnc_info_id) 
											VALUES	( '{$iFindActionId}', '{$iFncId}','{$last_Id}') ; " ;
				
				$rQueryInsertFncAction = @pg_query($conn, $sSqlInsertFncAction) or die(@pg_last_error($conn)) ;
				
			}
         else 
         {
         
				$sSqlUpateFncAction = "	UPDATE	nc_fnc_infos 
												SET	fnc_id = '{$iFncId}', 
												date_debut = '{$sDateDeb}', 
												date_fin = '{$sDateFin}', 
												responsable = '{$iResponsable}', 
												faille_identifiee = '{$sFaille}', 
												impact ='{$sImpact}', 
												generalisation = '{$sGeneralisation}', 
												etat = '{$sEtat}',
                                    indic_efficacite = {$bIndicateurEfficacite}',
                                    obj_echeance = {$bObjectifEcheance}'
										WHERE	fnc_id = '{$iFncId}' ; " ;
				
				$rQueryUpdateAction = @pg_query($conn, $sSqlUpateFncAction) ;
			}

      
   }
   else
   {
      $state = 0;
   }
   if($state == 1)
   {
      $checkInsert = "
            select fnc_id from nc_fnc_action nc_act inner join
            nc_fnc_infos nc_infos on nc_infos.id= nc_act.fnc_info_id
            where nc_act.fnc_id::integer = {$iFncId}
      ";
      $rQueryCheckInsert = @pg_query($conn, $checkInsert) or die(@pg_last_error($conn)); 
      $rowsNbr = @pg_num_rows($rQueryCheckInsert);
      if( $rowsNbr >= 1)
      {
         $state = 1;
      }
      else
      {
         $state = 0;
      }
   }
   
   echo  $state;
?>