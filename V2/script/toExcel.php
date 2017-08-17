<?php

	require_once("DBConnect.php");

	if($_REQUEST['txtActionType'] == "c"){
		$sActionType = "curative";
		$aAfficher = "curatives";
		$sFileName = "actionsCuratives.xls";
	}
	else{
		$sActionType = "corrective / preventive";
		$aAfficher = "correctives / pr&eacute;ventives";
		$sFileName = "actionsNonCuratives.xls";
	}

	$sContent = "	<p>
                     <table width=\"100%\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                           <td colspan = \"7\">
                              <div style = \"color: #000000; text-align: center; font-weight: bold\">
                                 Liste des actions $aAfficher dont l'&eacute;tat n'est pas encore &quot;OK&quot;
                              </div>
                           </td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                              <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                          </tr>
                          <tr>
                              <td><span style = \"color: #FF0000; font-weight: bold;\">En rouge :</span></td>
                              <td colspan = \"6\">les actions $aAfficher dont la date de fin est inf&eacute;rieure &agrave; la date du jour.</td>
                          </tr>
                          <tr>
                              <td><span style = \"color: #FF9900; font-weight: bold;\">En orange : </span></td>
                              <td colspan=\"6\">les actions $aAfficher dont la date de fin est &eacute;gale &agrave; la date du jour.</td>
                          </tr>
                          <tr>
                              <td><span style = \"color: #009966; font-weight: bold;\">En vert : </span></td>
                              <td colspan = \"6\">les actions $aAfficher dont la date de fin est sup&eacute;rieure &agrave; la date du jour.</td>
                          </tr>
                          <tr>
                              <td><span style = \"color: #000000; font-weight: bold;\">En noir : </span></td>
                              <td colspan = \"6\">les actions $aAfficher dont aucune date de fin n'est d&eacute;finie. </td>
                          </tr>
                     </table>
                  </p>
					<p>
						<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
			                <tr>
					            <td colspan=\"4\"><strong>Fiche de non conformit&eacute;</strong></td>
					            <td colspan='";

                              if ($_REQUEST['txtActionType'] == "nc") $sContent .= "7"; else $sContent .= "4";

                           $sContent .= "'><strong>Actions</strong></td>
                           <td rowspan=\"2\"><strong>Criticit&eacute;</strong></td>
					        </tr>
					        <tr>
					            <td><strong>Nom du client </strong></td>
					            <td><strong>R&eacute;f&eacute;rence</strong></td>
					            <td><strong>Type</strong></td>
                           <td><strong>Type d'appel</strong></td>
                           ";
                           
	if ($_REQUEST['txtActionType'] == "nc") {
		$sContent .= "			<td><strong>Validation action</strong></td>
				                <td><strong>Date suivi</strong></td>
				                <td><strong>Commentaire</strong></td>";
	}

	$sContent .= "	            <td><strong>Description</strong></td>
					            <td><strong>Responsable</strong></td>
					            <td><strong>Date de d&eacute;but </strong></td>
					            <td><strong>Date de fin </strong></td>
					        </tr>";

$sSql = "SELECT  nc_fiche.fnc_frequence_id,nc_fiche.fnc_gravite_id,nc_fiche.fnc_id AS id, nc_fiche.fnc_client AS client,
					nc_fiche.fnc_type AS type, nc_fiche.fnc_ref AS reffnc,
					nc_action.\"action_debDate\" AS datedeb, nc_action.\"action_finDate\" AS datefin,
					nc_action.action_description AS description, nc_action.action_responsable AS responsable,

					nc_action.action_etat AS action_status, nc_action.action_date_suivi AS action_date_suivi,
					nc_action.action_comment AS action_comment

				FROM nc_fiche, nc_action
				WHERE nc_action.action_etat != 'ok'
					AND nc_fiche.fnc_id = nc_action.\"action_fncId\"
					AND nc_action.action_type = '$sActionType'
				ORDER BY nc_fiche.fnc_ref ASC";
 // exit();
	$oQuerySql = @pg_query($conn, $sSql);
	$iNbSql = @pg_num_rows($oQuerySql);

	$sValidationAction = "";

	for($i = 0; $i < $iNbSql; $i ++){
		$toRes = @pg_fetch_array($oQuerySql, $i);

		if ($toRes['action_status'] == "en attente") $sValidationAction .= "non entam&eacute;";
		if ($toRes['action_status'] == "en cours") $sValidationAction .= "en cours;";
		if ($toRes['action_status'] == "ok") $sValidationAction .= "valid&eacute;";
      
      $idFNC = $toRes['id'];
      /****************** Modif Fulgence 20150210  ******************/
      // Affichage criticité
      if($toRes['fnc_gravite_id'] != '' && $toRes['fnc_frequence_id'] != '') 
      {
         //if($toRes['fnc_gravite_id'] != '')
            $igrav = $toRes['fnc_gravite_id'] ;
         /*else
            $igrav = 1 ;
            
         if($toRes['fnc_frequence_id'] != '')*/
            $ifreq = $toRes['fnc_frequence_id'] ;
         /*else
            $ifreq = 1 ;*/
         
         // gravité
         $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$igrav " ;
         //echo $sqlSltGrv ;
         $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
         $arGrv = pg_fetch_array($resGrv) ;
         $grv_ech = $arGrv['echelle_id_grav'] ;
         
         //Fréquence
         $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $ifreq " ;
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
         $colr = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;color:black'") ;
         $criticite = "" ;
      }
      /****************** Fin modif ******************/

		$bgColor = ($i % 2 == 1) ? "#ffffff" : "#bcb7f9";
       if(isset($idFNC))
                  {
                     $sqlAppeType = "
                     select libelle_typologie from cc_sr_typologie cc_typo inner join nc_fiche  ncf
                     on ncf.id_cc_sr_typo = cc_typo.id_typologie
                     where fnc_id = ".$idFNC;
                     
                     $resAppeType= pg_query($conn, $sqlAppeType) or die (pg_last_error($conn)) ;
                     $arAppeType = pg_fetch_array($resAppeType) ;
                     $gAppeType = $arAppeType['libelle_typologie'] ;
                     // $typeApp = 'Aucun';
                     if($gAppeType == '')
                        {
                           $typeApp = 'Aucun';
                        }
                      else 
                      {
                        $typeApp = $gAppeType;
                      }
                  }
		if(empty($toRes['datefin'])) $color = "#000000";
			else{
				if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0000";
					elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9900";
						else $color = "#009966";
			}

		$sContent .= "	<tr>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['client']}</td>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['reffnc']}</td>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['type']}</td>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$typeApp}</td>
                     "
                     ;
		if ($_REQUEST['txtActionType'] == "nc") {
			$sContent .= "	<td bgcolor = '$bgColor'  style = 'color: $color' >{$sValidationAction}</td>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['action_date_suivi']}</td>
							<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['action_comment']}</td>";
		}
		$sContent .= "		<td bgcolor = '$bgColor' style = 'color: $color' >{$toRes['description']}</td>
							<td bgcolor = '$bgColor' style = 'color: $color' >{$toRes['responsable']}</td>
							<td bgcolor = '$bgColor' style = 'color: $color' >{$toRes['datedeb']}</td>
							<td bgcolor = '$bgColor' style = 'color: $color' >{$toRes['datefin']}</td>
                     <td $colr >{$criticite}</td>
						</tr>";
	}

	$sContent .= "</table></p>";

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
