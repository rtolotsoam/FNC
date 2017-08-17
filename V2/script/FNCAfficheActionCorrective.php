<?php

	/**
	 * Créé le 13/07/2012 par Fulgence
	 * Modifié le 13/07/2012 par Fulgence
	 * 
	 */

	// ------- Accès à la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
	
	// Ajout par Fulgence -------------
   $dateDeb = date('Y-m-d') ;
	$dateFin = date('Y-m-d') ;
	
	if(isset($_REQUEST['txtDateDeb'])){
      $dateDeb  = $_REQUEST['txtDateDeb'] ;   
   }
   
   if(isset($_REQUEST['txtDateFin'])){
      $dateFin  = $_REQUEST['txtDateFin'] ;   
   }
   
     if(isset($_REQUEST['etat'])){
       $etat  = $_REQUEST['etat'] ;   
   }
   $selected = ' selected="selected"';
	// -------- fin ajout ------------

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Admin Actions Correctives</title>
		<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
		<link type="text/css" href="css/theme.blue.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
		<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="../js/jquery-block-ui.js"></script>
		<script type="text/javascript" src="../js/ui.core.js"></script>
		<script type="text/javascript" src="../js/ui.draggable.js"></script>
		<script type="text/javascript" src="../js/ui.dialog.js"></script>
		<script type="text/javascript" src="../js/ui.datepicker.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
		<script type="text/javascript" src="../js/FNCAjout.js"></script>
		<style>
		.wrapper {position: relative;height: 450px;overflow-y: auto;border:1px solid #E6EEEE;width:100%;direction:ltr;}
		</style>
		<script type="text/javascript">
		$(document).ready (function () {
			$("#isset_affiche").val("0");
			
		   $("#tabAction").tablesorter ({
				    theme:'blue',
					  widthFixed : true,
					  
					 widgets: ['zebra', 'stickyHeaders'],
					 headerTemplate : '{content} {icon}', // Add icon for various themes
					 
					 widgetOptions: {
				   // jQuery selector or object to attach sticky header to
				   stickyHeaders_attachTo : '.wrapper' // or $('.wrapper')

				   }
				
//				headers: { 5: { sorter: false} }
			}) ;
			
			
			

   
			
         $("#txtDateDeb").datepicker ({inline: true, changeMonth: true,changeYear: true}) ;
         $("#txtDateFin").datepicker ({inline: true, changeMonth: true,changeYear: true}) ;
		
		});
		
		// fonction affiche action
		
      function searchAction ()
      {
         $("#isset_affiche").val("1");
         document.getElementById('frmAfficheAction').action = "FNCAfficheActionCorrective.php" ;
         document.getElementById('frmAfficheAction').submit () ;
      }

		</script>

	</head>

	<body>
		<form id="frmAfficheAction" name="frmAfficheAction" method="post" action="FNCAfficheActionCorrective.php">
			<fieldset>
				<legend>Liste des actions correctives</legend>
				<center>
				<table>
               <tr>
                  <td>Actions Cr&eacute;&eacute;es entre le :</td>
                  <td>
                     <input type="text" id="txtDateDeb" name="txtDateDeb" value="<?php echo $dateDeb ; ?>" class="txtInput" readonly/>
                  </td>
                  <td>Et le :</td>
                  <td>
                     <input type="text" id="txtDateFin" name="txtDateFin" value="<?php echo $dateFin ; ?>" class="txtInput" readonly />
                     <input type = 'hidden' id = 'isset_affiche' name = 'isset_affiche' value = '0' />
                  </td>
				 <td>
						Etat :</td>
					<td>
					<select id="etat" name="etat" >
							<option value="*" <?=$etat == '*' ? ' selected="selected"' : '';?>>-- tous --</option>
							<option value="en cours" <?=$etat == 'en cours' ? ' selected="selected"' : '';?> >en cours</option>
							<option value="en attente" <?=$etat == 'en attente' ? ' selected="selected"' : '';?>>en attente</option>
							<option value="ok" <?=$etat == 'ok' ? ' selected="selected"' : '';?>>ok</option>
						</select>
					 </td>
               </tr>
               <tr>
                  <td colspan="2">&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
               </tr>
               <tr>
                  <td></td>
                  <td colspan="3" align="center">
                     <input type="button" id="btnSearch" name="btnSearch" value="Rechercher" class = "ui-state-default" onclick="searchAction ()" />
                  </td>
				   <td colspan="2">&nbsp;</td>
               </tr>
            </table>
				</center>
				
				<?php 
					if (isset($_REQUEST['isset_affiche']) && $_REQUEST['isset_affiche'] == 1) {
				?>
				<table style="width:100%;">
					<tr>
						<td align="right">
							<input type="button" id="btnRetour" name="btnRetour" value="Retour" class = "ui-state-default" onclick="javascript: document.location.href = 'FNCSuiviActionNC.php' " />
							<a href="toExcelActionNC.php?date_deb=<?php echo $dateDeb ; ?>&date_fin=<?php echo $dateFin ; ?>&etat=<?php echo $etat ; ?>" style="text-decoration: none">
								<input type="button" id="btnExportToExcel" name="btnExportToExcel" class="ui-state-default" value="Export Excel" style="cursor: pointer" />
							</a>
						</td>
					</tr>
				</table>
				
				
				<div class='wrapper'>
				<table id="tabAction">
					<thead>
						<tr bgcolor="#efefef">
							<br />
							<th>Type</th>
							<th>R&eacute;f&eacute;rence</th>
							<th>Faille identifi&eacute;e</th>
							<th>Impact</th>
							<th>Description</th>
							<th>Responsable</th>
							<th>Date d&eacute;but</th>
							<th>Date fin</th>
							<th>Date suivi</th>
							<th>Etat</th>
							<th>Commentaire</th>
							<th>G&eacute;n&eacute;ralisation</th>					
							<th>Criticit&eacute;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						
							$coul = 0;
							if ($etat != '*')	$clWhere = " and nfi.etat = '$etat' ";
							$zSqlId = "	SELECT nfa.action_liste_id as id, nfi.date_debut as dated_, 
										nfi.date_fin as datef_, nfi.etat as etat_,nfi.faille_identifiee as faille_,
										nfi.impact as imp_,nfi.generalisation as gen_, libelle AS description
                     
										FROM nc_fiche nf, nc_fnc_action nfa,nc_fnc_infos nfi, nc_action_liste nal
										WHERE nfa.action_liste_id = nal.id
										AND nf.fnc_id = CAST(nfa.fnc_id as integer) 
										AND nfa.fnc_info_id = nfi.id
										AND nal.type != 'curative'
										$clWhere
										AND date_debut BETWEEN '$dateDeb' AND '$dateFin'
										GROUP BY nfa.action_liste_id , nfi.date_debut, nfi.date_fin, nfi.etat,
										nfi.faille_identifiee,nfi.impact,nfi.generalisation,libelle
										ORDER BY nfa.action_liste_id  ASC ";
							
						   //echo $zSqlId ;
							$rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn)) ;
							
							for ($i = 0; $i < @pg_num_rows ($rQueryId); $i++){
									$reference = '';
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
								
									
									$zSqlInfo = " SELECT DISTINCT date_debut as datedeb,date_fin as datefin,responsable,
									etat, faille_identifiee, impact, generalisation, date_suivi, commentaire,
									nc_fnc_infos.id as idinfo, libelle AS description,indice ";
                           
                           $zSqlInfo .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id ";
                           
									$zSqlInfo .= " FROM nc_fnc_infos, nc_fnc_action, nc_action_liste, nc_fiche
													WHERE nc_fnc_infos.id = nc_fnc_action.fnc_info_id
													AND nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
													and nc_action_liste.id = nc_fnc_action.action_liste_id
													AND nc_fnc_action.action_liste_id = $id_
													AND nc_fnc_infos.etat ='$etat_'
													AND nc_fnc_infos.date_debut ='$dated_'
													AND nc_fnc_infos.date_fin ='$datef_'
													AND nc_action_liste.type != 'curative' 
													";
								/*print_r("<pre>");
								print_r($zSqlInfo);
								print_r("</pre>");*/
								
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
								
								if(empty($toRes['datefin'])) $color = "#000000";
								else{
									if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
										elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
											else $color = "#008000";
								}
								$aDescrition = explode("*#|#*", $toRes['description']);
								
						?>
						<tr <?php if ($coul%2==0) echo 'class="odd"'; ?>>
							<td><?php echo $type ; ?></td>
							<td><?php echo $reference ; ?></td>
							<td><?php echo $toRes['faille_identifiee'] ; ?></td>
							<td><?php echo $toRes['impact'] ; ?></td>
							<td><?php echo $aDescrition[0] ; ?></td>
							<td><?php echo $toRes['responsable'] ; ?></td>
							<td><?php echo $toRes['datedeb'] ; ?></td>
							<td><?php echo $toRes['datefin'] ; ?></td>
							<td><?php echo $toRes['date_suivi'] ; ?></td>
							<td><?php echo $toRes['etat'] ; ?></td>
							<td><?php echo $toRes['commentaire'] ; ?></td>
							<td><?php echo $toRes['generalisation'] ; ?></td>
							<td <?php echo $colr ; ?>><?php echo $criticite ; ?></td-->
						</tr>
						<?php 
								$coul ++;
							}
						?>
					</tbody>
				</table>
				</div>
				<?php
					}
				?>
			</fieldset>
		</form>


	</body>
</html>
