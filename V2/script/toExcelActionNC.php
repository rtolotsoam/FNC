<?php

	/**
	 * Créé le 13/07/2012 par Fulgence
	 * Modifié le 13/07/2012 par Fulgence
	 * 
	 */

	// ------- Accès à la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
	
	$date_deb = $_REQUEST['date_deb'] ;
	$date_fin = $_REQUEST['date_fin'] ;
	$etat = $_REQUEST['etat'] ;
	
		$sFileName = "listeActionsNonCuratives.xls";
	
		$sContent = " <p>
						<table width=\"100%\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
								<tr>
									<td colspan = \"11\" align=\"center\">
										<div style = \"color: #000000; text-align: center; font-weight: bold\">
											LISTE DE TOUTES LES ACTIONS CORRECTIVES
										</div>
									</td>
								</tr>
						</table>
					</p>
					<p>
						<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td><strong>Type</strong></td>
								<td><strong>R&eacute;f&eacute;rence</strong></td>
								<td><strong>Faille identifi&eacute;e</strong></td>
								<td><strong>Impact</strong></td>
								<td><strong>Description</strong></td>
								<td><strong>Responsable</strong></td>
								<td><strong>Date d&eacute;but</strong></td>
								<td><strong>Date fin</strong></td>
								<td><strong>Date suivi</strong></td>
								<td><strong>Etat</strong></td>
								<td><strong>Commentaire</strong></td>
								<td><strong>G&eacute;n&eacute;ralisation</strong></td>					
								<td><strong>Criticit&eacute;</strong></td>
							</tr> " ;
				
				if ($etat != '*')	$clWhere = " and nfi.etat = '$etat' ";
				$zSqlId = "	SELECT nfa.action_liste_id as id, nfi.date_debut as dated_, 
			nfi.date_fin as datef_, nfi.etat as etat_,nfi.faille_identifiee as faille_,
nfi.impact as imp_,nfi.generalisation as gen_, libelle AS description
				    	FROM nc_fiche nf, nc_fnc_action nfa,nc_fnc_infos nfi, nc_action_liste nal
						WHERE nfa.action_liste_id = nal.id
						AND nf.fnc_id = CAST(nfa.fnc_id as integer)
						AND nfa.fnc_info_id = nfi.id
						AND nal.type != 'curative'
						AND date_debut BETWEEN '$date_deb' AND '$date_fin' 
						$clWhere
						GROUP BY nfa.action_liste_id , nfi.date_debut, nfi.date_fin, nfi.etat,
						nfi.faille_identifiee,nfi.impact,nfi.generalisation,libelle
						ORDER BY nfa.action_liste_id  ASC "; 
            
		    $rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn)) ;
			
		    $clWere = '';
		    $cpt = 0;
		    
			for ($i = 0 ; $i < @pg_num_rows ($rQueryId) ; $i++){
					$reference = '' ;
					$type = '' ;
					$resSelectId = @pg_fetch_array ($rQueryId, $i);	
					$id_ = $resSelectId['id'] ;
					$dated_ = $resSelectId['dated_'] ;
					$datef_ = $resSelectId['datef_'] ;
					$etat_ = $resSelectId['etat_'] ;
					$faille_ = $resSelectId['faille_'] ;
					$imp_ = $resSelectId['imp_'] ;
					$gen_ = $resSelectId['gen_'] ;
					$libelle = $resSelectId['description'];
								   
					$zSql = "SELECT DISTINCT nc_fiche.fnc_ref as ref, nc_fiche.fnc_type AS type
									FROM  nc_fnc_action, nc_action_liste, nc_fiche, nc_fnc_infos
									WHERE nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
									AND nc_fnc_infos.id = nc_fnc_action.fnc_info_id
									AND  nc_fnc_action.action_liste_id = $id_ 
									AND nc_fnc_infos.etat ='$etat_'
									AND nc_fnc_infos.date_debut ='$dated_'
									AND nc_fnc_infos.date_fin ='$datef_'
													
									
					";
					
					$rQueryId_ = @pg_query($conn, $zSql) or die(@pg_last_error($conn)) ;
					 for ($j = 0 ; $j < @pg_num_rows ($rQueryId_) ; $j++){
					 	$resRefId = @pg_fetch_array ($rQueryId_, $j);	
						if ($reference == '') 
							$reference = $resRefId['ref'] ;
						else 
							$reference .= ', '.$resRefId['ref'] ;
						//$type = $resRefId['type'] ;
						if ($type == '') 
							$type = $resRefId['type'] ;
						else 
							$type .= '-'.$resRefId['type'] ;
					 }
				
					$zSqlInfo = "	SELECT DISTINCT date_debut as datedeb,date_fin as datefin,responsable,
									etat, faille_identifiee, impact, generalisation, date_suivi, commentaire,
									nc_fnc_infos.id as idinfo, libelle AS description,indice ";
               
               $zSqlInfo .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id ";
                            
					$zSqlInfo .= "	FROM nc_fnc_infos, nc_fnc_action, nc_action_liste, nc_fiche
													WHERE nc_fnc_infos.id = nc_fnc_action.fnc_info_id
													AND nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
													and nc_action_liste.id = nc_fnc_action.action_liste_id
													AND nc_fnc_action.action_liste_id = $id_
													AND nc_fnc_infos.etat ='$etat_'
													AND nc_fnc_infos.date_debut ='$dated_'
													AND nc_fnc_infos.date_fin ='$datef_'
													AND nc_action_liste.type != 'curative'  ";
				
				$rQueryInfo = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn)) ;
				$toRes = @pg_fetch_array ($rQueryInfo, 0);
            
            /****************** Modif Fulgence 20150210  ******************/
            // Affichage criticité
            if($toRes['fnc_grav_cat_id'] != '' && $toRes['fnc_freq_cat_id'] != '')
            {
               //if($toRes['fnc_grav_cat_id'] != '')
                  $cat_id_grav = $toRes['fnc_grav_cat_id'] ;
               /*else
                  $cat_id_grav = 1 ;
               
               if($toRes['fnc_freq_cat_id'] != '')*/
                  $cat_id_freq = $toRes['fnc_freq_cat_id'] ;
               /*else
                  $cat_id_freq= 1 ;*/
               
               // gravité
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$cat_id_grav " ;
               //echo $sqlSltGrv ;
               $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
               $arGrv = pg_fetch_array($resGrv) ;
               $grv_ech = $arGrv['echelle_id_grav'] ;
               
               //Fréquence
               $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $cat_id_freq " ;
               $resFrq = pg_query($conn, $sqlSltFrq) or die (pg_last_error($conn)) ;
               $arrFrq = pg_fetch_array($resFrq) ;
               $frq_ech = $arrFrq['echelle_id_freq'] ;
               
               if ($grv_ech == 1)
                  $criticite = "m" ;
               elseif ($grv_ech == 2 && $frq_ech <= 2)
                  $criticite = "m" ;
               elseif ($grv_ech == 2 && $frq_ech >= 3)
                  $criticite = "M" ;
               elseif ($grv_ech == 3 && $frq_ech < 4)   
                  $criticite = "M" ;
               elseif ($grv_ech == 3 && $frq_ech == 4)   
                  $criticite = "C" ;
               elseif($frq_ech >= 4)
                  $criticite = "C" ;
               /*else
                  $criticite = "m" ;*/
               
               // test de couleur
               if ($criticite == "m")
               {
                  $colr = "style='background-color:#FCF03F;font-weight:bold;'" ;
                  $criticite = "mineure" ;
               }
               elseif ($criticite == "M")
               {
                  $colr = "style='background-color:#F28810;font-weight:bold;'" ;
                  $criticite = "Majeure" ;
               }
               elseif ($criticite == "C")
               {
                  $colr = "style='background-color:#E71D07;color:#FFFFFF;font-weight:bold;'" ;
                  $criticite = "Critique" ;
               }
            }
            else
            {
               //$color = "style='background-color:#FFFFFF;font-weight:bold;'" ;
               $color = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'") ;
               $criticite = "" ;
            }
            /****************** Fin modif ******************/
            
				
				$bgColor = ($i % 2 == 1) ? "#ffffff" : "#bcb7f9";

				if(empty($toRes['datefin'])) $color = "#000000";
				else{
					if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
						elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
							else $color = "#008000";
				}
				$aDescrition = explode("*#|#*", $toRes['description']);
				
		$sContent .= "<tr>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$type}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$reference}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['faille_identifiee']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['impact']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$aDescrition[0]}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['responsable']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datedeb']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datefin']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['date_suivi']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['etat']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['commentaire']}</td>
						<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['generalisation']}</td>
						<td $colr >{$criticite}</td>
					</tr> " ;
			}
		$sContent .= "</table></p>" ;
		
		ob_clean();
		header("Pragma: cache") ;
		header("Content-type: application/octet-stream;UTF-8") ;
		header("Content-Disposition: attachment; filename = $sFileName") ;
		header("Content-transfer-encoding: binary") ;
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ) ;
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" ) ;
		header("Cache-Control: post-check = 0, pre-check = 0", false ) ;
		header("Content-Length: ".strlen($sContent)) ;

		print $sContent ;

		exit ;

?>