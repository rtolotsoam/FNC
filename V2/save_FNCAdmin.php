<?php

	/**
	 * modifie par ralainahitra angelo
	 * le 2011-07-06
	 * ajout de trois table classement module et process
	 */
	require_once("DBConnect.php");

	if(isset($_REQUEST['txtDate1'])){
		$dDate1 = $_REQUEST['txtDate1'];
		$dDate2 = $_REQUEST['txtDate2'];
	}
	else{
		$dDate1 = date("Y-m-01");
		$dDate2 = date("Y-m-d");
	}

echo " eto" ;
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Administration</title>

		<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />

		<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="../js/jquery-block-ui.js"></script>
		<script type="text/javascript" src="../js/ui.core.js"></script>
		<script type="text/javascript" src="../js/ui.dialog.js"></script>
		<script type="text/javascript" src="../js/ui.datepicker.js"></script>
		<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#txtDate1").datepicker();
				$("#txtDate2").datepicker();

				$(".tr-content").mouseover(function ()
				{
					$(this).children ("td").addClass ("td-hover") ;
				}) ;
				$(".tr-content").mouseout(function ()
				{
					$(this).children ("td").removeClass () ;
				}) ;

				$("#btnEnreg").click(function(){
					$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

					$("div.dialog").removeAttr("title") ;
					$("div.dialog").attr({title: "Enregistrement"}) ;
					$("p.pMessage").html("Voulez vous enregistrer les modifications?") ;

					$("div.dialog").dialog({
						modal: true,
						overlay: {
							backgroundColor: '#000000',
							opacity: 0.5
						},
						buttons: {
							'Enregistrer': function() {
								$("#frmAll").removeAttr("action");
								$("#frmAll").attr("action", "FNCUpdate.php");
								$("#frmAll").submit();
							},
							'Annuler': function() {
								$(this).dialog('close') ;
							}
						}
					});
				});

			});
			//export EXCEL
			function exportToXL ()
			{
				document.frmAll.action = "FNCAdminExport.php" ;
				document.frmAll.submit () ;
			}

			function edit(_txt, _span)
			{
				$("#" + _txt).show();
				$("#" + _txt).val($("#" + _span).html());
				$("#" + _txt).focus();
				$("#" + _span).hide();
			}

			function tide(_txt, _span)
			{
				$("#" + _txt).hide();
				$("#" + _span).html($("#" + _txt).val());
				$("#" + _span).show();
			}

			function select_edit_tide(_td, _slct, _span, _id)
			{
				$("#" + _td + _id).click(function(){
					edit(_slct + _id, _span + _id);
					$("#" + _slct + _id).change(function(){
						tide(_slct + _id, _span + _id);
					});
				});
				$("#" + _slct + _id).blur(function(){
					tide(_slct + _id, _span + _id);
				});
			}
		</script>
	</head>
<?php


	// fnc
	function _sql($_conn, $_column){
		$zSqlFnc	= "SELECT 	DISTINCT $_column AS valeur FROM nc_fiche WHERE ($_column IS NOT NULL OR $_column <> '') ORDER BY $_column";
		$oQueryFnc	= @pg_query($_conn, $zSqlFnc);
		$iNbFnc		= @pg_num_rows($oQueryFnc);

		$res = "<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";

		for($i = 0; $i < $iNbFnc; $i ++){
			$toFnc	= @pg_fetch_array($oQueryFnc, $i);
			$res .= "	<option value=\"".$toFnc['valeur']."\" >".$toFnc['valeur']."</option>";
		}
		return $res;
	}
	
	/*
	$sSqlCause1 = "SELECT * FROM nc_cause ORDER BY libelle ASC;";
	$rQueryCause1 = @pg_query($conn, $sSqlCause1);
	$iNbCause1 = @pg_num_rows($rQueryCause1);
	*/
	
	

	// typologie
	$zSqlTypologie		= " SELECT typologie_id, typologie_libelle FROM nc_typologie WHERE typologie_actif IS NULL ORDER BY typologie_libelle" ;
	$oQueryTypologie	= @pg_query($conn, $zSqlTypologie) ;
	$iNbTypologie		= @pg_num_rows($oQueryTypologie) ;

	// imputation
	$zSqlImputation		= " SELECT imputation_id, imputation_libelle FROM nc_imputation WHERE imputation_actif IS NULL ORDER BY imputation_libelle" ;
	$oQueryImputation	= @pg_query($conn, $zSqlImputation) ;
	$iNbImputation		= @pg_num_rows($oQueryImputation) ;

	// classement
	$sSqlClassement		= "SELECT classement_libelle FROM nc_classement ORDER BY classement_libelle ASC;" ;
	$rQueryClassement	= @pg_query($conn, $sSqlClassement) or die(@pg_last_error($conn)) ;
	$iNbClassement		= @pg_num_rows($rQueryClassement) ;

	// module
	$sSqlModule		= "SELECT module_libelle FROM nc_module ORDER BY module_libelle ASC;" ;
	$rQueryModule	= @pg_query($conn, $sSqlModule) or die(@pg_last_error($conn)) ;
	$iNbModule		= @pg_num_rows($rQueryModule) ;

	// process
	$sSqlProcess	= "SELECT process_libelle FROM nc_process ORDER BY process_libelle ASC;" ;
	$rQueryProcess	= @pg_query($conn, $sSqlProcess) or die(@pg_last_error($conn)) ;
	$iNbProcess		= @pg_num_rows($rQueryProcess) ;

	//en tete du tableau
	$entete .= "	<table id=\"tabNC\" name=\"tabNC\" cellspacing=\"1\" cellpadding=\"1\">
					<tr class=\"entete\">
						<td colspan=\"6\" align=\"center\">FNC</td>
                  <td rowspan=\"2\">Gravit&eacute;/Impact</td>
                  <td rowspan=\"2\">Fr&eacute;quence</td>
                  <td rowspan=\"2\">Criticit&eacute;</td>
						<td colspan=\"3\" align=\"center\" bgcolor=\"gray\">Analyse</td>

						<td rowspan=\"2\">
							<select style=\"width: 100px\" class=\"entete\" id=\"slctTypologie\" name=\"slctTypologie\" onchange=\"$('#frmAll').submit();\">
								<option value=\"".$_REQUEST['slctTypologie']."\">Typologie</option>
								<option value=\"vide\" style=\"font-weight: normal; font-style: italic; \">(vide)</option>
								<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>" ;
			for($i = 0; $i < $iNbTypologie; $i ++){
				$toTypologie = @pg_fetch_array($oQueryTypologie, $i) ;
				$entete .=  "	<option value=\"".$toTypologie['typologie_id']."\" >".$toTypologie['typologie_libelle']."</option>" ;
			}
			$entete .= "	</select>
						</td>

						<td rowspan=\"2\">
							<select style=\"width: 100px\" class=\"entete\" id=\"slctImputation\" name=\"slctImputation\" onchange=\"$('#frmAll').submit();\">
								<option value=\"".$_REQUEST['slctImputation']."\">Imputation</option>
								<option value=\"vide\" style=\"font-weight: normal; font-style: italic; \">(vide)</option>
								<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";
			for($i = 0; $i < $iNbImputation; $i ++)
			{
				$toImputation = @pg_fetch_array($oQueryImputation, $i);
				$entete .=  "	<option value=\"".$toImputation['imputation_id']."\">".$toImputation['imputation_libelle']."</option>";
			}
			$entete .= "	</select>
						</td>
                  
					</tr>
					<tr class=\"entete\">
						<td>
							<select style=\"width: 75px\" class=\"entete\" id=\"slctClient\" name=\"slctClient\" onchange=\"$('#frmAll').submit();\">
								<option value=\"".$_REQUEST['slctClient']."\">Client</option>";
					$entete .= _sql($conn, "fnc_client");
					$entete .= "</select>
						</td>

						<td>
							<select style=\"width: 75px\" class=\"entete\" id=\"slctCode\" name=\"slctCode\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctCode']."\">Code</option>";
					$entete .= _sql($conn, "fnc_code");

					$entete .= "</select>
							</td>

							<td>
								<select style=\"width: 75px\" class=\"entete\" id=\"slctStatut\" name=\"slctStatut\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctStatut']."\">Statut</option>
									<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>
									<option value=\"bouclé\">Cl&ocirc;tur&eacute;e</option>
									<option value=\"en attente\">En attente</option>
									<option value=\"en cours\">En cours</option>
								</select>
							</td>

							<td style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Date </td>

							<td>
								<select style=\"width: 75px\" class=\"entete\" id=\"slctType\" name=\"slctType\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctType']."\">Type</option>
									<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>
									<option value=\"audit\">audit</option>
									<option value=\"client\">client</option>
									<option value=\"interne\">interne</option>
								</select>
							</td>

							<td style=\"padding-top: 5px; padding-left: 5px\">Motif cr&eacute;ation FNC</td>

							<td>Motif apparition
								<!--select style=\"width: auto\" class=\"entete\" id='slctCause1' name='slctCause1' onchange=\"$('#frmAll').submit();\"-->
									<!--option value='{$_REQUEST['slctCause1']}'>Cause 1</option-->
									<!--option value='' style='font-weight: normal; font-style: italic; '>(tous)</option-->";
				for ($j = 0; $j < $iNbCause1; $j ++) {
					$aResCause1 = @pg_fetch_array($rQueryCause1, $j);
					$entete .= "	<option value='{$aResCause1['id']}'>{$aResCause1['libelle']}</option>";
				}
				$entete .= "	<!--/select-->
							</td>
							<td style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Motif d&eacute;tection</td>
							<td style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Motif organisation</td>

							<!--td>
								<select style=\"width: 100px\" class=\"entete\" id=\"slctProcess\" name=\"slctProcess\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctProcess']."\">Process</option>
									<option value=\"vide\" style=\"font-weight: normal; font-style: italic; \">(vide)</option>
									<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";
				for($i = 0; $i < $iNbProcess; $i ++)
				{
					$aProcess = @pg_fetch_array($rQueryProcess, $i);
					$entete .= "	<option value=\"".$aProcess['process_libelle']."\">".$aProcess['process_libelle']."</option>";
				}
				$entete .= "	</select>
							</td-->";
				/*
							<td>
								<select style=\"width: 100px\" class=\"entete\" id=\"slctClassement\" name=\"slctClassement\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctClassement']."\">Classement</option>
									<option value=\"vide\" style=\"font-weight: normal; font-style: italic; \">(vide)</option>
									<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";
				for($i = 0; $i < $iNbClassement; $i ++)
				{
					$aClassement = @pg_fetch_array($rQueryClassement, $i);
					$entete .= "	<option value=\"".$aClassement['classement_libelle']."\">".$aClassement['classement_libelle']."</option>";
				}
				$entete .= "	</select>
							</td>
				*/
				$entete .= "<!--td>
								<select style=\"width: 100px\" class=\"entete\" id=\"slctModule\" name=\"slctModule\" onchange=\"$('#frmAll').submit();\">
									<option value=\"".$_REQUEST['slctModule']."\">Module</option>
									<option value=\"vide\" style=\"font-weight: normal; font-style: italic; \">(vide)</option>
									<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";
				for($i = 0; $i < $iNbModule; $i ++)
				{
					$aModule = @pg_fetch_array($rQueryModule, $i);
					$entete .= "	<option value=\"".$aModule['module_libelle']."\">".$aModule['module_libelle']."</option>";
				}
				$entete .= "	</select>
							</td-->";
				/*
				$entete .= "<td style=\"padding-top: 5px; padding-left: 5px\">Typo</td>
				*/
	$entete .= "	</tr>" ;
				

	// recuperation des variables en-tete du tableau
	$zClient		   = $_REQUEST['slctClient'];
	$zCode			= $_REQUEST['slctCode'];
	$zType			= $_REQUEST['slctType'];
	$zStatut		   = $_REQUEST['slctStatut'];
	$zTypologie		= $_REQUEST['slctTypologie'];
	$zImputation	= $_REQUEST['slctImputation'];
	/*$zClassement	= $_REQUEST['slctClassement'];
	$zModule		= $_REQUEST['slctModule'];
	$zProcess		= $_REQUEST['slctProcess'];
	$iCause1 = $_REQUEST['slctCause1'];*/

	// requete pour recherche
	$zSqlRecap = "	SELECT 	fnc_client, fnc_code, fnc_statut,
							\"fnc_creationDate\", fnc_type, nc_fiche.fnc_id as id_fnc,
							fnc_motif, fnc_cause, fnc_typologie,
							fnc_imputation, fnc_ref, fnc_process,
							fnc_classement, fnc_module, fnc_typo,fnc_autre_cplmnt " ;
   $zSqlRecap .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id " ;
	$zSqlRecap .= " FROM 	nc_fiche LEFT JOIN nc_motif ON nc_motif.fnc_id = nc_fiche.fnc_id
					WHERE 	nc_fiche.\"fnc_creationDate\" BETWEEN '$dDate1' AND '$dDate2' ";
	if(!empty($zClient)) $zSqlRecap .= "AND fnc_client = '$zClient' ";
	if(!empty($zCode)) $zSqlRecap .= "AND fnc_code = '$zCode' ";
	if(!empty($zType)) $zSqlRecap .= "AND fnc_type = '$zType' ";
	if(!empty($zStatut)) $zSqlRecap .= "AND fnc_statut = '$zStatut' ";
	if (!empty($iCause1)) $zSqlRecap .= " AND SUBSTRING(fnc_cause FROM 1 FOR 1) = '{$iCause1}' ";

	if(!empty($zTypologie))
	{
		if($zTypologie == "vide") $zSqlRecap .= "AND (fnc_typologie IS NULL)";
		else $zSqlRecap .= "AND fnc_typologie = '$zTypologie' ";
	}
	if(!empty($zImputation))
	{
		if($zImputation == "vide") $zSqlRecap .= "AND (fnc_imputation IS NULL)";
		else $zSqlRecap .= "AND fnc_imputation = '$zImputation' ";
	}
	/*
	if(!empty($zClassement))
	{
		if($zClassement == "vide") $zSqlRecap .= "AND (fnc_classement = '')";
		else $zSqlRecap .= "AND fnc_classement = '$zClassement' ";
	}
	
	if(!empty($zModule))
	{
		if($zModule == "vide") $zSqlRecap .= "AND (fnc_module = '')";
		else $zSqlRecap .= "AND fnc_module = '$zModule' ";
	}
	if(!empty($zProcess))
	{
		if($zProcess == "vide") $zSqlRecap .= "AND (fnc_process = '')";
		else $zSqlRecap .= "AND fnc_process = '$zProcess' ";
	}
	*/
	$zSqlRecap .= "  GROUP BY nc_fiche.fnc_id, fnc_type,fnc_client, fnc_code, fnc_statut, \"fnc_creationDate\", fnc_motif,						fnc_cause, fnc_typologie, fnc_imputation, fnc_ref, fnc_process,	fnc_classement, fnc_module, fnc_typo 
	                 ORDER BY fnc_client ASC ";

	$oQueryRecap = @pg_query($conn, $zSqlRecap) or die($zSqlRecap . @pg_last_error($conn));
	$iNbRecap = @pg_num_rows($oQueryRecap);

	// contenu de la recherche
	$corps	= "	<input type='hidden' id='fnc_nb' name='fnc_nb' value='{$iNbRecap}' />";
	$aCauses = array();
	for($i = 0; $i < $iNbRecap; $i ++) {
		$toResRecap	= @pg_fetch_array($oQueryRecap, $i);
		
		$idFnc = $toResRecap['id_fnc'] ;
        
      /****************** Modif Fulgence 20150210  ******************/
      // Affichage criticité
      if($toResRecap['fnc_gravite_id'] != '')
         $igrav = $toResRecap['fnc_gravite_id'] ;
      else
         $igrav = 1 ;
      
      if($toResRecap['fnc_frequence_id'] != '')
         $ifreq = $toResRecap['fnc_frequence_id'] ;
      else
         $ifreq = 1 ;
     
      // gravité
      $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie WHERE id_categorie_grav=$igrav " ;
      //echo $sqlSltGrv ;
      $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
      $arGrv = pg_fetch_array($resGrv) ;
      $grv_ech = $arGrv['echelle_id_grav'] ;
      $grv_cat_id = $arGrv['id_categorie_grav'] ;
      
      //Fréquence
      $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq, libelle_frequence FROM nc_frequence_categorie WHERE id_categorie_freq = $ifreq " ;
      $resFrq = pg_query($conn, $sqlSltFrq) or die (pg_last_error($conn)) ;
      $arrFrq = pg_fetch_array($resFrq) ;
      $frq_ech = $arrFrq['echelle_id_freq'] ;
      $frq_cat_id = $arrFrq['id_categorie_freq'] ;
      
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
      else
         $criticite = "m" ;
      
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

      /****************** Fin modif ******************/
		
		// selection libelle nc_motif apparition
		$sqlSlctAppar = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Apparition' AND nc_fiche.fnc_id = '$idFnc' ;" ;
		
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

		$sCode3		= "";
		$color=(($i % 2 == 0) ? "#ffffff" : "#efefef");
		$oResTypologie = @pg_fetch_array(@pg_query($conn, "SELECT typologie_libelle FROM nc_typologie WHERE typologie_id = ".$toResRecap['fnc_typologie'].""));
		$oResImputation = @pg_fetch_array(@pg_query($conn, "SELECT imputation_libelle FROM nc_imputation WHERE imputation_id = ".$toResRecap['fnc_imputation'].""));

		$iId			= $toResRecap['fnc_id'];
		
      if (trim($toResRecap['fnc_client']) == "Autres"){
         $sClient = $toResRecap['fnc_autre_cplmnt'] ;
		}
      else 
         $sClient		= $toResRecap['fnc_client'];
         
		$sCode			= $toResRecap['fnc_code'];

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
		//$sCause			= $toResRecap['fnc_cause'];
		$sTypologie		= $oResTypologie['typologie_libelle'];
		$sImputation	= $oResImputation['imputation_libelle'];

		$corps .= "		<input type='hidden' id='fnc_id_{$i}' name='fnc_id_{$i}' value='{$iId}' />
						<tr bgcolor='$color' class='tr-content'>
							<td>{$sClient}</td>
							<td>{$sCode}</td>
							<td>";
								if($sSatut == "bouclé") $corps .= "cl&ocirc;tur&eacute;e";
								else $corps .= $sSatut;
		$corps .= "			
                     </td>
							<td>{$dCreationDate}</td>
							<td>{$sType}</td>
							<td>{$sMotif}</td>

							<!--td>{$aResLibelleCause1['libelle']}</td-->
							<td>
                        <select id=\"txtGravit\" name=\"txtGravit\">
                           <option value=\"\">** choisir **</option>" ;
                           $corps .= "<option value='{$igrav}' ";
                              if ($toResRecap['fnc_grav_cat_id'] == $arrFrq['id_categorie_grav']) 
                              $corps .= "selected" ; $corps .= ">".$arrFrq['libelle_gravite']."</option>
                        </select>
                     </td>
							<td>
                        <select id=\"txtFrequence\" name=\"txtFrequence\">
                           <option value=\"\">** choisir **</option>" ;
                           $corps .= "<option value='{$ifreq}' " ;
                              if ($toResRecap['fnc_freq_cat_id'] == $arrFrq['id_categorie_freq']) 
                              $corps .= "selected"; $corps .= ">".$arrFrq['libelle_frequence']."</option>
                        </select>
                     </td>
                     <td $colr>{$criticite}</td>
							<td>{$appar}</td>
							<td>{$detect}</td>
							<td>{$organis}</td>";

		$corps .=  "	<script type='text/javascript'>
							$(document).ready(function(){
								select_edit_tide('tdTypologie', 'slctTypologie', 'spanTypologie', '{$iId}');
								select_edit_tide('tdImputation', 'slctImputation', 'spanImputation', '{$iId}');
								select_edit_tide('tdProcess', 'slctProcess', 'spanProcess', '{$iId}');
								select_edit_tide('tdClassement', 'slctClassement', 'spanClassement', '{$iId}');
								select_edit_tide('tdModule', 'slctModule', 'spanModule', '{$iId}');
							});
						</script>
							<td id='tdTypologie{$iId}'>
								<span id='spanTypologie{$iId}'>{$sTypologie}</span>
								<select style=\"width: 100px; display: none\" id=\"slctTypologie{$iId}\" name=\"slctTypologie{$iId}\">
									<option value=''> *** (choix) *** </option>";
		for($ii = 0; $ii < $iNbTypologie; $ii ++)
		{
			$toTypologie = @pg_fetch_array($oQueryTypologie, $ii);
			$corps .= "				<option value='".$toTypologie['typologie_libelle']."' ";
				if($sTypologie == $toTypologie['typologie_libelle']) $corps .= "selected";
			$corps .= ">".$toTypologie['typologie_libelle']."</option>";
		}
		$corps .=  "			</select>
							</td>
							<td id='tdImputation{$iId}'>
								<span id='spanImputation{$iId}'>{$sImputation}</span>
								<select style=\"width: 100px; display: none\" id=\"slctImputation{$iId}\" name=\"slctImputation{$iId}\">
									<option value=''> *** (choix) *** </option>";
		for($ij = 0; $ij < $iNbImputation; $ij ++)
		{
			$toImputation = @pg_fetch_array($oQueryImputation, $ij);
			$corps .=  "			<option value='".$toImputation['imputation_libelle']."' ";
				if($sImputation == $toImputation['imputation_libelle']) $corps .= "selected";
			$corps .= ">".$toImputation['imputation_libelle']."</option>";
		}
		$corps .= "			</select></td>";
            
		$corps .= "		</tr>";
	}
	$pied = "		</table>";

	// affichage
	?>
	<body>
		<form id="frmAll" name="frmAll" method="POST" action="">
		<fieldset>
			<legend>Administration</legend>
			<p>
				<table width="100%">
					<tr>
						<td width="10%">Date du : </td>
						<td width="20%">
							<input type="text" id="txtDate1" name="txtDate1" class="txtInput" value="<?php echo $dDate1 ; ?>" readonly />
						</td>
						<td width="5%">au :</td>
						<td width="20%">
							<input type="text" id="txtDate2" name="txtDate2" class="txtInput" value="<?php echo $dDate2 ; ?>" readonly />
						</td>
						<td width="2.5%">&nbsp;</td>
						<td width="20%">
							<input type="submit" id="btnSubmit" name="btnSubmit" class="ui-state-default" value="Afficher" />
						</td>
						<td width="2.5%">
							<input type="button" id="btnEnreg" name="btnEnreg" class="ui-state-default" value="Enregistrer" />
						</td>
						<td width="20%" align="right">
							<input type="button" id="btnRI" name="btnRI" class="ui-state-default" value="R&eacute;initialiser" onClick="window.location.href='FNCAdmin.php'" />
						</td>
						<td width="20%" align="right">
							<input type="button" id="btnEE" name="btnEE" class="ui-state-default" value="ExportExcel" onClick="exportToXL ()" />
						</td>
					</tr>
				</table>
			</p>
			<p>&nbsp;</p>
			<?php
				if($iNbRecap != 0) $resultat = $entete.$corps.$pied; else $resultat = $entete.$pied;
				echo $resultat;
			?>
		</fieldset>
		</form>
		<p style="color: #B1221C; font-weight: bold; font-size: 12px"><?php if(isset($_REQUEST['admin_msg'])) echo urldecode($_REQUEST['admin_msg']); else echo ""; ?></p>
	</body>
</html>