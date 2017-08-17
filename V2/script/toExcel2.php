<?php
	require_once("DBConnect.php");
	$zSelectRef = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC";
	$oQuerySelectRef = @pg_query($zSelectRef);
	$iNbSelectRef = @pg_num_rows($oQuerySelectRef);

	$sFileName = "ActionsCorrectives.xls";

	$sContent = "	<p>
						<table width=\"100%\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
							<tr>
								<td colspan = \"11\" align=\"center\">
									<div style = \"color: #000000; text-align: center; font-weight: bold\">
										ACTIONS CORRECTIVES A L'ETAT NON OK
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
								<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
							</tr>
							<tr>
								<td><span style ='color: #FF0000; font-weight: bold;'>En rouge :</span></td>
								<td colspan = \"6\"> les actions correctives dont la date de fin est inf&eacute;rieure &agrave; la date du jour.</td>
							</tr>
							<tr>
								<td><span style = 'color: #FF9900; font-weight: bold;'>En orange : </span></td>
								<td colspan=\"6\"> les actions correctives dont la date de fin est &eacute;gale &agrave; la date du jour.</td>
							</tr>
							<tr>
								<td><span style = 'color: #009966; font-weight: bold;'>En vert : </span></td>
								<td colspan = \"6\"> les actions correctives dont la date de fin est sup&eacute;rieure &agrave; la date du jour.</td>
							</tr>
							<tr>
								<td><span style = 'color: #000000; font-weight: bold;'>En noir : </span></td>
								<td colspan = \"6\">les actions correctives dont aucune date de fin n'est d&eacute;finie.</td>
							   </tr>
						</table>
					</p>
					<p>
						<table width=\"100%\"  border=\"1\" cellspacing=\"2\" cellpadding=\"3\">
			                <tr >
					            <td colspan=\"4\"><strong>Fiche de non conformit&eacute;</strong></td>
                            <td></td>
                        <td></td>
								
								<td colspan=\"9\" class=\"titre\" align=\"center\"><strong>Actions</strong></td>
								<td colspan='1' style=' border-style: solid 10px; border-color: #ff0000 #0000ff;'></td>
								<td colspan='1' style=' border-style: solid 10px; border-color: #ff0000 #0000ff;'></td>
								
							  </tr>
							  <tr>
								<td><strong>Type</strong></td>
								<td><strong>R&eacute;f&eacute;rence</strong></td>
								<td><strong>Type d'appel</strong></td>
								<td><strong>BU</strong></td>
                       <td ><strong>Faille identifi&eacute;e</strong></td>
								<td ><strong>Impact</strong></td>
								<td align=\"center\"><strong>Description</strong></td>
								<td><strong>Responsable</strong></td>
								<td><strong>Date de d&eacute;but</strong></td>
								<td><strong>Date de fin</strong></td>
								<td><strong>Date suivi</strong></td>
								<td><strong>Indicateur d'&eacute;fficacit&eacute;</strong></td>
								<td><strong>Objectif et &eacute;ch&eacute;ance</strong></td>
								<td><strong>Validation action</strong></td>
								<td><strong>Commentaire</strong></td>
                        <td rowspan=\"1\"><strong>G&eacute;n&eacute;ralisation</strong></td>
								<td rowspan=\"1\"><strong>Criticit&eacute;</strong></td>
								
							  </tr>";

			
			$zSqlId = "	select *from (
            SELECT 
			               nf.fnc_id as fncid,nf.fnc_id,nf.fnc_client,nfa.action_liste_id as id, nfi.date_debut as dated_, nfi.date_fin as datef_, 
			               nfi.etat as etat_,nfi.faille_identifiee as faille_,nfi.impact as imp_,
			               nfi.generalisation as gen_
                     FROM 
                        nc_fiche nf, nc_fnc_action nfa,nc_fnc_infos nfi, nc_action_liste nal
                     WHERE nfi.etat != 'ok'
                     AND nfa.action_liste_id = nal.id
                     AND nf.fnc_id = CAST(nfa.fnc_id as integer)
                     AND nfa.fnc_info_id = nfi.id
                     AND nal.type != 'curative'
                     GROUP BY nf.fnc_id,nf.fnc_client,nfa.action_liste_id , nfi.date_debut, nfi.date_fin, nfi.etat,
                     nfi.faille_identifiee,nfi.impact,nfi.generalisation
                     ORDER BY nfa.action_liste_id  ASC ) as temp
            left join 
            (SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
                        INNER JOIN gu_application a ON 
                        substring(f.fnc_code FROM 1 for 3) = a.code 
                        INNER JOIN  business_unit 
                        b ON b.id_bu = a.id_bu
                        union
                        select lib_bu,fnc_id from nc_fiche ncf 
                        inner join business_unit bu
                        on ncf.fnc_bu = bu.id_bu
                        ) as temp2 
                        on temp2.fnc_id =temp.fnc_id";
			
		    $rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn)) ;
			
		    $clWere = '';
		    $cpt = 0;
		    
			for ($i = 0 ; $i < @pg_num_rows ($rQueryId) ; $i++){
					$reference	= '';
					$type		= '' ;
					$resSelectId = @pg_fetch_array ($rQueryId, $i);	
					$id_	= $resSelectId['id'] ;
					$dated_ = $resSelectId['dated_'] ;
					$datef_ = $resSelectId['datef_'] ;
					$etat_	= $resSelectId['etat_'] ;
					$$idFNC = $resSelectId['fnc_id'].'</br>' ;
					$faille_ = $resSelectId['faille_'] ;
					$imp_	= $resSelectId['imp_'] ;
					$gen_	= $resSelectId['gen_'] ;
					$b_fncid	= $resSelectId['fncid'] ;
               // print_r($resSelectId);
					$libelle = $resSelectId['description'];
				   $bu =  $resSelectId['lib_bu'] ;	
               
               $sqlAppeType = "
                     select libelle_typologie from cc_sr_typologie cc_typo inner join nc_fiche  ncf
                     on ncf.id_cc_sr_typo = cc_typo.id_typologie
                     where fnc_id =".$b_fncid;
                     
                     $resAppeType= pg_query($conn, $sqlAppeType) or die (pg_last_error($conn)) ;
                     $arAppeType = pg_fetch_array($resAppeType) ;
                     $gAppeType = $arAppeType['libelle_typologie'] ;
                     $typeApp = 'Aucun';
                     if($gAppeType == '')
                        {
                           $typeApp = 'Aucun';
                        }
                      else 
                      {
                        $typeApp = $gAppeType;
                      }
					$zSql = "SELECT distinct nc_fiche.fnc_ref as ref, nc_fiche.fnc_type AS type				
					FROM  nc_fnc_action, nc_action_liste, nc_fiche, nc_fnc_infos
					WHERE nc_fiche.fnc_id = CAST (nc_fnc_action.fnc_id as integer)
					AND nc_fnc_infos.id = nc_fnc_action.fnc_info_id
					AND  nc_fnc_action.action_liste_id = $id_ 
					AND nc_fnc_infos.etat ='$etat_'
	 				AND nc_fnc_infos.date_debut ='$dated_'
	 				AND nc_fnc_infos.date_fin ='$datef_' ";
					
					$rQueryId_ = @pg_query($conn, $zSql) or die(@pg_last_error($conn)) ;
					 for ($j = 0 ; $j < @pg_num_rows ($rQueryId_) ; $j++){
					 	$resRefId = @pg_fetch_array ($rQueryId_, $j);	
						if ($reference == '') $reference = $resRefId['ref'] ;
						else $reference .= ', '.$resRefId['ref'] ;
						//$type = $resRefId['type'] ;
						if ($type == '') 
							$type = $resRefId['type'] ;
						else 
							$type .= '-'.$resRefId['type']."<br/>" ;
					 }
				$zSqlInfo = "select *from (";
					$zSqlInfo .= "	SELECT DISTINCT nc_fiche.fnc_client,obj_echeance,indic_efficacite,date_debut as datedeb, date_fin as datefin,responsable,
									etat,nc_fiche.fnc_id,fnc_ref,faille_identifiee, impact, generalisation, date_suivi, commentaire,
									 nc_fnc_infos.id as idinfo, libelle AS description,indice ";
                           
               $zSqlInfo .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id " ;
               
					$zSqlInfo .= " FROM nc_fnc_infos, nc_fnc_action, nc_action_liste, nc_fiche
									WHERE nc_fnc_infos.id = nc_fnc_action.fnc_info_id
									AND nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
									AND nc_action_liste.id = nc_fnc_action.action_liste_id
									AND nc_fnc_action.action_liste_id = $id_
									AND nc_fnc_infos.etat ='$etat_'
									AND nc_fnc_infos.date_debut ='$dated_'
									AND nc_fnc_infos.date_fin ='$datef_'
					 				AND  nc_fnc_infos.etat != 'ok'
									AND nc_action_liste.type != 'curative' ";
						
						if (isset($_REQUEST['slctActionValidationFiltre'])) {
						if (!empty($_REQUEST['slctActionValidationFiltre'])) 
							$zSqlInfo .= " AND nc_fnc_infos.etat = '{$_REQUEST['slctActionValidationFiltre']}' ";
						}
            
            $zSqlInfo .= ") as temp
                        left join (
                        SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
                                    INNER JOIN gu_application a ON 
                                    substring(f.fnc_code FROM 1 for 3) = a.code 
                                    INNER JOIN  business_unit 
                                    b ON b.id_bu = a.id_bu 
                                    union
                                    select lib_bu,fnc_id from nc_fiche ncf 
                                    inner join business_unit bu
                                    on ncf.fnc_bu = bu.id_bu
                                    ) as temp2 on temp.fnc_id = temp2.fnc_id";
				// echo '<pre>';
               // print_r($zSqlInfo);
				// echo '</pre>'; 
				$rQueryInfo = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn)) ;
				$toRes = @pg_fetch_array ($rQueryInfo, 0);
            
            /****************** Modif Fulgence 20150210  ******************/
            // echo $toRes['fnc_grav_cat_id'];exit();
            // Affichage criticit&eacute;
            echo $toRes['fnc_freq_cat_id'];
            if(trim($toRes['fnc_grav_cat_id']) != '' && trim($toRes['fnc_freq_cat_id']) != '')
            {
               //if($toRes['fnc_grav_cat_id'] != '')
                  $cat_id_grav = $toRes['fnc_grav_cat_id'] ;
               /*else
                  $cat_id_grav = 1 ;
               
               if($toRes['fnc_freq_cat_id'] != '')*/
                  $cat_id_freq = $toRes['fnc_freq_cat_id'] ;
               /*else
                  $cat_id_freq = 1 ;*/
               // exit('1');
               // gravit&eacute;
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$cat_id_grav " ;
               //echo $sqlSltGrv ;
               $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
               $arGrv = pg_fetch_array($resGrv) ;
               $grv_ech = $arGrv['echelle_id_grav'] ;
               
               //Fr&eacute;quence
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
               $colr = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'") ;
               $criticite = "" ;
               
            }
            /****************** Fin modif ******************/
            
				// exit('=====');
				$bgColor = ($i % 2 == 1) ? "#ffffff" : "#bcb7f9";
            $obj = $toRes['obj_echeance'];
            $eff = $toRes['indic_efficacite'];
            $libelle = $toRes['description'];
				if(empty($toRes['datefin'])) $color = "#000000";
				else{
					if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
						elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
							else $color = "#008000";
				}
				
           // if(isset($idFNC))
                  // {
                     
                  // }
                  // $typeApp = 'Aucun';
			$sContent .= "	<tr>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$type}</a></td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$reference}</a></td>
								
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$typeApp}</a></td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$bu }</a></td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['faille_identifiee']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['impact']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$libelle}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['responsable']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datedeb']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datefin']}</td>
                        
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['date_suivi']}</td>
                         <td bgcolor = '$bgColor'  style = 'color: $color' >{$eff}</td>
                         <td bgcolor = '$bgColor'  style = 'color: $color' >{$obj}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['etat']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['commentaire']}</td>
								<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['generalisation']}</td>
								<td $colr>{$criticite}</td>
							</tr>";
				}
			
			$sContent .= "</table></p> " ;
			
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