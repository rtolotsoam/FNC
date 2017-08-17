<?php
	require_once("DBConnect.php");

	if(isset($_REQUEST['txtDate1'])){
		$dDate1 = $_REQUEST['txtDate1'];
		$dDate2 = $_REQUEST['txtDate2'];
	}
	else{
		$dDate1 = date("Y-m-01");
		$dDate2 = date("Y-m-d");
	}

	/* *************************************** fnc ********************************** */
	function _sql($_champ, $_name){
		$zSqlFnc = "SELECT 	DISTINCT $_champ AS valeur FROM nc_fiche WHERE ($_champ IS NOT NULL OR $_champ <> '') ORDER BY $_champ";
		$oQueryFnc = @pg_query($zSqlFnc);
		$iNbFnc = @pg_num_rows($oQueryFnc);

		$res = "<option value=\"dp\" style=\"font-weight: normal; font-style: italic; \">dix premiers</option>
				<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";

		for($i = 0; $i < $iNbFnc; $i ++){
			$toFnc = @pg_fetch_array($oQueryFnc, $i);
			if(!empty($toFnc['valeur'])) $res .= "	<option value=\"".$toFnc['valeur']."\" >".$toFnc['valeur']."</option>";
		}
		return $res;
	}

	/* ******************************** recuperation du libelle de la typologie ******************************** */
	$zSqlTypologie = " SELECT typologie_id, typologie_libelle FROM nc_typologie";
	$oQueryTypologie = @pg_query($zSqlTypologie);
	$iNbTypologie = @pg_num_rows($oQueryTypologie);

	/* ********************************** recuperation du libelle de l'imputation ******************************** */
	$zSqlImputation = " SELECT imputation_id, imputation_libelle FROM nc_imputation";
	$oQueryImputation = @pg_query($zSqlImputation);
	$iNbImputation = @pg_num_rows($oQueryImputation);

	/* ****************************************** en tete du tableau ********************************************* */
	$entete = "		<table id=\"tabNC\" name=\"tabNC\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
						<tr class=\"entete\"  bgcolor = \"FF9900\">
							<td colspan=\"6\" align=\"center\"><b>FNC</b></td>
                  <td rowspan=\"2\">Gravit&eacute;/Impact</td>
                  <td rowspan=\"2\">Fr&eacute;quence</td>
                     <td rowspan=\"2\"><b>Criticit&eacute;</b></td>
							<td colspan=\"3\" align=\"center\"><b>Analyse</b></td>
							<td rowspan=\"2\"><b>Typologie</b></td>
							<td rowspan=\"2\"><b>Imputation</b></td>
						</tr>
						<tr class=\"entete\"  bgcolor = \"FF9900\">
							<td><b>Client</b></td>
							<td><b>BU</b></td>
							<td><b>Code</b></td>
							<td><b>Statut</b></td>
							<td><b>Date</b></td>
							<td><b>Type</b></td>

							<td><b>Motif cr&eacute;ation FNC</b></td>
							<td><b>Motif apparition</b></td>
							<td><b>Motif d&eacute;tection</b></td>
							<td><b>Motif organisation</b></td>

							<!--td><b>Process</b></td>
							<td><b>Module</b></td-->
						</tr>" ;

	/* *************************************** recuperation des variables ************************************** */
	$zClient	= $_REQUEST['slctClient'];
	$zCode		= $_REQUEST['slctCode'];
	$zType		= $_REQUEST['slctType'];
	$zStatut	= $_REQUEST['slctStatut'];
	$zTypologie		= $_REQUEST['slctTypologie'];
	$zImputation	= $_REQUEST['slctImputation'];

	/* ***************************************** requete et excecution ***************************************** */
     $zSqlRecap ="select*from(";
	$zSqlRecap .= "	SELECT 	fnc_client, fnc_code, fnc_statut,
							\"fnc_creationDate\", fnc_type, nc_fiche.fnc_id as id_fnc,
							fnc_motif, fnc_cause, fnc_typologie,
							fnc_imputation, fnc_ref, fnc_process,
							fnc_classement, fnc_module, fnc_typo,fnc_autre_cplmnt " ;
   
   $zSqlRecap .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id " ;
                     
	$zSqlRecap .= " FROM 	nc_fiche LEFT JOIN nc_motif ON nc_motif.fnc_id = nc_fiche.fnc_id

					WHERE 	nc_fiche.\"fnc_creationDate\" BETWEEN '$dDate1' AND '$dDate2' ";
	if(!empty($zClient)&&($zClient != "dp")) $zSqlRecap .= "AND fnc_client = '$zClient' ";
	if(!empty($zCode)&&($zCode != "dp")) $zSqlRecap .= "AND fnc_code = '$zCode' ";
	if(!empty($zType)&&($zType != "dp")) $zSqlRecap .= "AND fnc_type = '$zType' ";
	if(!empty($zStatut)&&($zStatut != "dp")) $zSqlRecap .= "AND fnc_statut = '$zStatut' ";
	if(!empty($zTypologie))	$zSqlRecap .= "AND fnc_typologie = '$zTypologie' ";
	if(!empty($zImputation)) $zSqlRecap .= "AND fnc_imputation = '$zImputation' ";
	$zSqlRecap .= "GROUP BY nc_fiche.fnc_id, fnc_type,fnc_client, fnc_code, fnc_statut, \"fnc_creationDate\", fnc_motif,						fnc_cause, fnc_typologie, fnc_imputation, fnc_ref, fnc_process,	fnc_classement, fnc_module,						fnc_typo ORDER BY fnc_client ASC";
	if(($zClient == "dp")||($zCode == "dp")||($zType == "dp")||($zStatut == "dp")||($zImputation == "dp")||($zTypologie == "dp"))
		$zSqlRecap .= " LIMIT 10";
      
   $zSqlRecap .= "
   ) as temp
      left join
      (
         SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
                              INNER JOIN gu_application a ON 
                              (
                           substring(f.fnc_code FROM 1 for 3) = a.code
                           or  (substring(f.fnc_code FROM 2 for 3) = a.code and length(f.fnc_code) = 7 )
                           or  (f.fnc_code = a.code and length(f.fnc_code) = 3 )
                           )
                              INNER JOIN  business_unit 
                              b ON b.id_bu = a.id_bu  and f.fnc_code <> '0VVT001'
                             -- and lib_bu ='RC'
                              union
                              select lib_bu,fnc_id from nc_fiche ncf 
                              inner join business_unit bu
                              on ncf.fnc_bu = bu.id_bu

      ) as temp2 on temp2.fnc_id = temp.id_fnc
      ORDER BY fnc_client ASC 
   ";
	$oQueryRecap = @pg_query($zSqlRecap);
	$iNbRecap = @pg_num_rows($oQueryRecap);

	/* ************************************** contenu de la recherche ***************************************** */
	$corps	= "";
	$aCauses = array();
	for($i = 0; $i < $iNbRecap; $i ++)
	{
		$toResRecap	= @pg_fetch_array($oQueryRecap, $i);
		$idFnc = $toResRecap['id_fnc'] ;
      
      /****************** Modif Fulgence 20150210  ******************/
      // Affichage criticité
      if($toResRecap['fnc_gravite_id'] != '' && $toResRecap['fnc_frequence_id'] != '') 
      {
         //if($toResRecap['fnc_gravite_id'] != '')
            $igrav = $toResRecap['fnc_gravite_id'] ;
         /*else
            $igrav = 1 ;
         
         if($toResRecap['fnc_frequence_id'] != '')*/
            $ifreq = $toResRecap['fnc_frequence_id'] ;
         /*else
            $ifreq = 1 ;*/
         
         // gravité
         $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie WHERE id_categorie_grav=$igrav " ;
         //echo $sqlSltGrv ;
         $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
         $arGrv = pg_fetch_array($resGrv) ;
         $grv_ech = $arGrv['echelle_id_grav'] ;
         $lib_cat    = $arGrv['libelle_gravite'] ;
         
         //Fréquence
         $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq,libelle_frequence FROM nc_frequence_categorie WHERE id_categorie_freq = $ifreq " ;
         $resFrq = pg_query($conn, $sqlSltFrq) or die (pg_last_error($conn)) ;
         $arrFrq = pg_fetch_array($resFrq) ;
         $frq_ech = $arrFrq['echelle_id_freq'] ;
         $frq_libele = $arrFrq['libelle_frequence'] ;
         
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
         elseif($grv_ech >= 4)
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
		
		$sCode3		= "";
		$color=(($i % 2 == 0) ? "#ffffff" : "#efefef");
		$oResTypologie = @pg_fetch_array(@pg_query($conn, "SELECT typologie_libelle FROM nc_typologie WHERE typologie_id = ".$toResRecap['fnc_typologie'].""));
		$oResImputation = @pg_fetch_array(@pg_query($conn, "SELECT imputation_libelle FROM nc_imputation WHERE imputation_id = ".$toResRecap['fnc_imputation'].""));

		$iId			= $toResRecap['fnc_id'];
		$sTypo			= $toResRecap['fnc_typo'];
		
		if (trim($toResRecap['fnc_client']) == "Autres"){
         $sClient = $toResRecap['fnc_autre_cplmnt'] ;
		}
      else 
         $sClient		= $toResRecap['fnc_client'];
         
		$sCode			= $toResRecap['fnc_code'];
		
		$sCode			= $toResRecap['fnc_code'];
		$lib_bu			= trim($toResRecap['lib_bu']);

		for($iSizeOfCode = 0; $iSizeOfCode < 3; $iSizeOfCode ++) $sCode3 .= $sCode[$iSizeOfCode];
		$sSqlProcessClassementModule	= "	SELECT	DISTINCT mtp_naturecmd, mtp_classification, mtp_unitefonction
											FROM	mth_priorite
											WHERE	mtp_clientidreel = '{$sCode3}'";
		$aResProcessClassementModule	= @pg_fetch_array(@pg_query($conn, $sSqlProcessClassementModule));
		if(empty($toResRecap['fnc_process'])) $sProcess	= $aResProcessClassementModule['mtp_naturecmd']; else $sProcess = $toResRecap['fnc_process'];
		if(empty($toResRecap['fnc_classement'])) $sClassement = $aResProcessClassementModule['mtp_classification']; else $sClassement = $toResRecap['fnc_classement'];
		if(empty($toResRecap['fnc_module'])) $sModule	= $aResProcessClassementModule['mtp_unitefonction']; else $sModule = $toResRecap['fnc_module'];

		$sSatut			= $toResRecap['fnc_statut'];
		$dCreationDate	= $toResRecap['fnc_creationDate'];
		$sType			= $toResRecap['fnc_type'];
		$sMotif			= $toResRecap['fnc_motif'];

		/*
		$aCauses = explode("*#|#*", $toResRecap['fnc_cause']);
		$aResLibelleCause1 = @pg_fetch_array(@pg_query($conn, "SELECT libelle FROM nc_cause WHERE id = '{$aCauses[0]}';"));
		//$sCause			= $toResRecap['fnc_cause'];
		*/
		$sTypologie		= $oResTypologie['typologie_libelle'];
		$sImputation	= $oResImputation['imputation_libelle'];
		

		// selection libelle nc_motif apparition
		$sqlSlctAppar = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Apparition' AND nc_fiche.fnc_id = '$idFnc';" ;
		$rQueryAppar = @pg_query ($conn, $sqlSlctAppar) ;
		$iNbAppar = @pg_num_rows ($rQueryAppar) ;
		$appar = '' ;
		for ($j=0; $j < $iNbAppar; $j ++)
		{
			$aResLibelAppar = @pg_fetch_array ($rQueryAppar) ;

			$apparLib = $aResLibelAppar['libelle'] ;
			$virg = (($j == ($iNbAppar-1)) ? '' : ',' ) ;
			$appar = $appar.$apparLib.$virg ;
		}

		// selection libelle nc_motif detection
		$sqlSlctDetect = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Détection' AND nc_fiche.fnc_id = '$idFnc';" ;
		$rQueryDetect = @pg_query ($conn, $sqlSlctDetect) ;
		$iNbDetect = @pg_num_rows ($rQueryDetect) ;
		$detect = '' ;
		for ($j=0; $j < $iNbDetect; $j ++)
		{
			$aResLibelDetect = @pg_fetch_array ($rQueryDetect) ;

			$detectLib = $aResLibelDetect['libelle'] ;
			$virg = (($j == ($iNbDetect-1)) ? '' : ',' ) ;
			$detect = $detect.$detectLib.$virg ;
		}


		// selection libelle nc_motif Organisation
		$sqlSlctOrganis = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Organisation' AND nc_fiche.fnc_id = '$idFnc';" ;
		$rQueryOrganis = @pg_query ($conn, $sqlSlctOrganis) ;
		$iNbOrganis = @pg_num_rows ($rQueryOrganis) ;
		$organis = '' ;
		for ($j=0; $j < $iNbOrganis; $j ++)
		{
			$aResLibelOrganis = @pg_fetch_array ($rQueryOrganis) ;

			$organisLib = $aResLibelOrganis['libelle'] ;
			$virg = (($j == ($iNbOrganis-1)) ? '' : ',' ) ;
			$organis = $organis.$organisLib.$virg ;
		}


		if($lib_bu == '')
      	{
      		$lib_bu = "AUCUN";
      	}


		$corps .= "		<tr bgcolor=\"$color\">
							<td>{$sClient}</td>
							<td>{$lib_bu}</td>
							<td>{$sCode}</td>
							<td>";
								if($sSatut == "boucl?") $corps .= "cl&ocirc;tur&eacute;e";
								else $corps .= $sSatut;
		$corps .= "			</td>
							<td>{$dCreationDate}</td>
							<td>{$sType}</td>
							<td>{$sMotif}</td>
							<!--td>{$aResLibelleCause1['libelle']}</td>
							<td>{$aCauses[1]}</td>
							<td>{$aCauses[2]}</td-->
							<td id=\"idGrvt\">{$lib_cat}</td>
							<td id=\"idFrqe\">{$frq_libele}</td>
                     <td $colr>{$criticite}</td>
							<td>{$appar}</td>
							<td>{$detect}</td>
							<td>{$organis}</td>
							<td>{$sTypologie}</td>
							<td>{$sImputation}</td>
						</tr>";
	}
	$pied = "		</table>";

	/* *********************************************** affichage ******************************************* */
	if($iNbRecap != 0) $resultat = $entete.$corps.$pied; else $resultat = $entete.$pied;

	ob_clean();
	header("Pragma: cache") ;
	header("Content-type: application/octet-stream;UTF-8") ;
	header("Content-Disposition: attachment; filename = RecapExport.xls") ;
	header("Content-transfer-encoding: binary") ;
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ) ;
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" ) ;
	header("Cache-Control: post-check = 0, pre-check = 0", false ) ;
	header("Content-Length: ".strlen($resultat)) ;

	print $resultat ;

	exit ;

?>