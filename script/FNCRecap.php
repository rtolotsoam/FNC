<?php

/**
 * modifie par ralainahitra angelo
 * le 2011-07-06
 * ajout de trois table classement module et process
 */
require_once "DBConnect.php";

if (isset($_REQUEST['txtDate1'])) {
    $dDate1 = trim($_REQUEST['txtDate1']);
    $dDate2 = trim($_REQUEST['txtDate2']);

} else {

    $dDate1 = date("Y-m-01");
    $dDate2 = date("Y-m-d");
}

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Administration</title>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

      <link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css" />
		<script type="text/javascript">

      $(function () {

       $("#frmAll").show();
       // $("#idWrapperCache").show();
       // $("#idContentCache").show();

       $("#imLoad").hide();

       $('#tabNC').tablesorter({

        theme: 'blue',
         widthFixed : true,

        widgets: ['zebra', 'stickyHeaders'],
        headerTemplate : '{content} {icon}', // Add icon for various themes
        widgets: [ 'zebra', 'stickyHeaders', 'filter' ],
        widgetOptions: {
      // jQuery selector or object to attach sticky header to
      stickyHeaders_attachTo : '.wrapper' // or $('.wrapper')

    },
        headers: {
        0: { sorter: false}, 1: { sorter: false}, 2: { sorter: false}, 3: { sorter: false}, 4: { sorter: false}, 5: { sorter: false}, 6: { sorter: false}, 7: { sorter: false}, 8: { sorter: false}, 9: { sorter: false}, 10: { sorter: false}, 11: { sorter: false}, 12: { sorter: false}, 13: { sorter: false}, 14: { sorter: false}, 15: { sorter: false}, 16: { sorter: false}, 17: { sorter: false} }
    });});
			$(document).ready(function() {
				$("#txtDate1").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
				$("#txtDate2").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});

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
			var txtDate1 = $("#txtDate1").val();
			var txtDate2 = $("#txtDate2").val();
         // alert('66'+txtDate1);
         // return 0;
			window.location = "FNCRecapExport.php?txtDate1="+txtDate1+"&txtDate2 ="+txtDate2;
				// document.frmAll.action = "FNCRecapExport.php" ;

				// document.frmAll.submit () ;
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

         function optionChange()
         {
            var slctBU = $("#slctBU").val() ;
            var txtDate1 = $("#txtDate1").val();
            var txtDate2 = $("#txtDate2").val();
            var slctStatut = $("#slctStatut").val();
            var slctClient = $("#slctClient").val();
            var slctCode = $("#slctCode").val();
            var slctType = $("#slctType").val();
            var slctTypologie = $("#slctTypologie").val();
            var slctImputation = $("#slctImputation").val();
            //alert('xx');
            window.location = "FNCAdmin.php?slctBU=" + slctBU + "&txtDate1=" + txtDate1 + "&txtDate2= "+ txtDate2+"&slctStatut="+slctStatut+"&slctClient="+slctClient+"&slctCode="+slctCode+"&slctType="+slctType+"&slctTypologie="+slctTypologie+"&slctImputation="+slctImputation  ;
            // consol.log("slctBU=" + slctBU + "&txtDate1=" + txtDate1 + "&txtDate2 = "+ txtDate2+"&slctStatut="+slctStatut+"&slctClient="+slctClient+"&slctCode="+slctCode+"&slctType="+slctType+"&slctTypologie="+slctTypologie+"&slctImputation="+slctImputation );

         }
		</script>
      <style>
         .slct_grav {
            width : 150px;
         }

         body{font-family: verdana;font-size: 11px;}

        .wrapper {
           position: relative;

           height: 550px;
           overflow-y: scroll;
           border:4px solid #1e5799;width:100%;
            direction:ltr;
         }
         div.ui-datepicker{
 			font-size:12px;
 			z-index: 99999 !important;
		}
      </style>
	</head>
<?php

// fnc
function _sql($_conn, $_column)
{
    $zSqlFnc   = "SELECT 	DISTINCT $_column AS valeur FROM nc_fiche WHERE ($_column IS NOT NULL OR $_column <> '') ORDER BY $_column";
    $oQueryFnc = @pg_query($_conn, $zSqlFnc);
    $iNbFnc    = @pg_num_rows($oQueryFnc);

    $res = "<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";

    for ($i = 0; $i < $iNbFnc; $i++) {
        $toFnc = @pg_fetch_array($oQueryFnc, $i);
        $res .= "	<option value=\"" . $toFnc['valeur'] . "\" >" . $toFnc['valeur'] . "</option>";
    }
    return $res;
}
function loadBu($conn)
{
    $sqlLoadBu     = "";
    $ContentLoadBu = "";
    $sqlLoadBu .= "
            SELECT distinct lib_bu FROM nc_fiche f
            INNER JOIN gu_application a ON
            substring(f.fnc_code FROM 1 for 3) = a.code
            INNER JOIN  business_unit
            b ON b.id_bu = a.id_bu order by lib_bu
             ";
    $queryLoadBu = pg_query($conn, $sqlLoadBu);
    $ContentLoadBu .= "<option style = 'font-size:10px;' value='' class='optTitle'>--Tous--</option>";
    while ($row = pg_fetch_array($queryLoadBu)) {
        $ContentLoadBu .= "<option style = 'font-size:10px;' value='" . $row['lib_bu'] . "' class='optTitle'>" . $row['lib_bu'] . "</option>";
    }
    return $ContentLoadBu;
}
/*
$sSqlCause1 = "SELECT * FROM nc_cause ORDER BY libelle ASC;";
$rQueryCause1 = @pg_query($conn, $sSqlCause1);
$iNbCause1 = @pg_num_rows($rQueryCause1);
 */

function _sqlBu($_ficheId, $_conn)
{
    $sqlBu = "
        SELECT lib_bu FROM nc_fiche f
       INNER JOIN gu_application a ON
        substring(f.fnc_code FROM 1 for 3) = a.code
        INNER JOIN  business_unit
          b ON b.id_bu = a.id_bu  WHERE f.fnc_id = '$_ficheId' ";

    $queryBu  = @pg_query($_conn, $sqlBu);
    $iNbBu    = @pg_num_rows($queryBu);
    $iNbAppar = @pg_num_rows($queryBu);
    $row      = @pg_fetch_row($queryBu);
    return $row[0];

}

// typologie
$zSqlTypologie   = " SELECT typologie_id, typologie_libelle FROM nc_typologie WHERE typologie_actif IS NULL ORDER BY typologie_libelle";
$oQueryTypologie = @pg_query($conn, $zSqlTypologie);
$iNbTypologie    = @pg_num_rows($oQueryTypologie);

// imputation
$zSqlImputation   = " SELECT imputation_id, imputation_libelle FROM nc_imputation WHERE imputation_actif IS NULL ORDER BY imputation_libelle";
$oQueryImputation = @pg_query($conn, $zSqlImputation);
$iNbImputation    = @pg_num_rows($oQueryImputation);

// classement
$sSqlClassement   = "SELECT classement_libelle FROM nc_classement ORDER BY classement_libelle ASC;";
$rQueryClassement = @pg_query($conn, $sSqlClassement) or die(@pg_last_error($conn));
$iNbClassement    = @pg_num_rows($rQueryClassement);

// module
$sSqlModule   = "SELECT module_libelle FROM nc_module ORDER BY module_libelle ASC;";
$rQueryModule = @pg_query($conn, $sSqlModule) or die(@pg_last_error($conn));
$iNbModule    = @pg_num_rows($rQueryModule);

// process
$sSqlProcess   = "SELECT process_libelle FROM nc_process ORDER BY process_libelle ASC;";
$rQueryProcess = @pg_query($conn, $sSqlProcess) or die(@pg_last_error($conn));
$iNbProcess    = @pg_num_rows($rQueryProcess);

//en tete du tableau
$entete .= "	<table id=\"tabNC\" name=\"tabNC\" cellspacing=\"1\" cellpadding=\"1\">
					<tr class=\"entete\">
					<thead style='font: 12px/18px Arial, Sans-serif;'>
						<th colspan=\"8\" align=\"center\">FNC</th>
                  		<th ></th>
						<th colspan=\"3\" align=\"center\" bgcolor=\"gray\">Analyse</th>

						<th rowspan=\"2\" align=\"center\">Typologie</th>

						<th rowspan=\"2\" align=\"center\">Imputation</th>
                   		<th rowspan=\"2\">Criticit&eacute;</th>
                   		<th ></th>
                   		<th ></th>
                    	<th colspan=\"8\" align=\"center\">Action</th>
                    	<th colspan=\"5\"</th>
					</tr>
					<tr class=\"entete\">
						<th> R&eacute;ference</th>
						<th>Nom du Client</th>
						<th style=\"padding-top: 2px; padding-left: 2px\">BU</th>
                     	<th>Code</th>
                     	<th>Statut</th>
                     	<th style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Date de cr&eacute;ation FNC </th>
                     	<th style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Cr&eacute;&eacute;e par </th>
						<th>Type</th>
						<th style=\"padding-top: 5px; padding-left: 5px\">Motif cr&eacute;ation FNC</th>
						<th>Motif apparition</th>
						<th style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Motif d&eacute;tection</th>
						<th style=\"padding-top: 5px; padding-left: 5px; padding-right: 5px\">Motif organisation</th>
			            <th data-sorter='false' align=\"center\" style='padding-top: 10px; padding-left: 5px'>Faille identifi&eacute;e</th>
			            <th data-sorter='false' style='padding-top: 12px;padding-left: 5px'>Impact</th>
			            <th data-sorter='false' style='padding-top: 12px; padding-left: 5px'>Description</th>
			            <th data-sorter='false' style='padding-top: 12px;padding-left: 5px'>Responsable</th>
			            <th data-sorter='false' style='padding-top: 5px; padding-left: 5px' align=\"center\" >Date de d&eacute;but d'actions</th>
			            <th data-sorter='false' style='padding-top: 5px; padding-left: 5px' align=\"center\" >Date de fin</th>
			            <th data-sorter='false' style='padding-top: 5px; padding-left: 5px' align=\"center\" >Date de suivi</th>
			            <th data-sorter='false' align=\"center\" >Indicateur </br> d&lsquo;&eacute;fficacit&eacute;</th>
			            <th data-sorter='false' align=\"center\" >Objectif et &eacute;ch&eacute;ance</th>
			            <th align=\"center\">Etat r&eacute;alisation actions</th>
			            <th align=\"center\">Validation de l'action</th>
			            <th data-sorter='false' align=\"center\" style='padding-top: 12px;padding-left: 5px' >Commentaire</th>
            <th data-sorter='false' align=\"center\" style='padding-top: 12px;padding-left: 5px' >Taux<br/>d'avancement action</th>
            <th data-sorter='false' align=\"center\" style='padding-top: 12px;padding-left: 5px' >Taux <br/> restant</th>
			            <th data-sorter='false' align=\"center\"  style='padding-top: 12px;padding-left: 5px'>G&eacute;n&eacute;ralisation</th>
             ";

$entete .= "    	</tr>
					 	</thead>
					<tbody>";

// recuperation des variables en-tete du tableau
$zClient     = $_REQUEST['slctClient'];
$zCode       = $_REQUEST['slctCode'];
$zType       = $_REQUEST['slctType'];
$zStatut     = $_REQUEST['slctStatut'];
$zTypologie  = $_REQUEST['slctTypologie'];
$zImputation = $_REQUEST['slctImputation'];
/*$zClassement    = $_REQUEST['slctClassement'];
$zModule        = $_REQUEST['slctModule'];
$zProcess        = $_REQUEST['slctProcess'];
$iCause1 = $_REQUEST['slctCause1'];*/

// requete pour recherche

    
        if($dDate1 != $dDate2) {   

                $zSqlRecap      = "
                        SELECT
                        *
                        FROM(
                        ";
                $zSqlRecap      .= "	
                        SELECT
                            FNC_BU              AS  FNC_0VVT
                        ,   FNC_REF
                        ,   FNC_CLIENT
                        ,   FNC_CODE
                        ,   FNC_CREATEUR
                        ,   FNC_STATUT
                        ,   \"fnc_creationDate\"
                        ,   FNC_TYPE
                        ,   NC_FICHE.FNC_ID     AS  ID_FNC
                        ,   FNC_MOTIF
                        ,   FNC_CAUSE
                        ,   FNC_TYPOLOGIE
                        ,   FNC_IMPUTATION
                        ,   FNC_REF
                        ,   FNC_PROCESS
                        ,   FNC_CLASSEMENT
                        ,   FNC_MODULE
                        ,   FNC_TYPO
                        ,   FNC_AUTRE_CPLMNT
                        ";
                $zSqlRecap      .= " 
                        ,   FNC_GRAVITE_ID
                        ,   FNC_FREQUENCE_ID
                        ,   FNC_FREQ_CAT_ID
                        ,   FNC_GRAV_CAT_ID
                        ";
                $zSqlRecap      .= " 
                        FROM
                            NC_FICHE
                        LEFT JOIN
                            NC_MOTIF
                        ON
                            NC_MOTIF.FNC_ID =   NC_FICHE.FNC_ID
                        WHERE
                            NC_FICHE.\"fnc_creationDate\"   >=  '$dDate1'   
                        AND NC_FICHE.\"fnc_creationDate\"   <=  '$dDate2'
                        ";
        }else{

                $zSqlRecap      = "
                        SELECT
                        *
                        FROM(
                        ";
                $zSqlRecap      .= "    
                        SELECT
                            FNC_BU              AS  FNC_0VVT
                        ,   FNC_REF
                        ,   FNC_CLIENT
                        ,   FNC_CODE
                        ,   FNC_CREATEUR
                        ,   FNC_STATUT
                        ,   \"fnc_creationDate\"
                        ,   FNC_TYPE
                        ,   NC_FICHE.FNC_ID     AS  ID_FNC
                        ,   FNC_MOTIF
                        ,   FNC_CAUSE
                        ,   FNC_TYPOLOGIE
                        ,   FNC_IMPUTATION
                        ,   FNC_REF
                        ,   FNC_PROCESS
                        ,   FNC_CLASSEMENT
                        ,   FNC_MODULE
                        ,   FNC_TYPO
                        ,   FNC_AUTRE_CPLMNT
                        ";
                $zSqlRecap      .= " 
                        ,   FNC_GRAVITE_ID
                        ,   FNC_FREQUENCE_ID
                        ,   FNC_FREQ_CAT_ID
                        ,   FNC_GRAV_CAT_ID
                        ";
                $zSqlRecap      .= " 
                        FROM
                            NC_FICHE
                        LEFT JOIN
                            NC_MOTIF
                        ON
                            NC_MOTIF.FNC_ID =   NC_FICHE.FNC_ID
                        WHERE
                            NC_FICHE.\"fnc_creationDate\"   =  '$dDate1'    
                        ";

        }

    if (!empty($zClient)) {
    $zSqlRecap .= "AND  FNC_CLIENT  =   '$zClient' ";
    }
    
    if (!empty($zCode)) {
    $zSqlRecap .= "AND  FNC_CODE    =   '$zCode'";
    }
    
    if (!empty($zType)) {
    $zSqlRecap .= "AND  FNC_TYPE    =   '$zType'";
    }
    
    if (!empty($zStatut)) {
    $zSqlRecap .= "AND  FNC_STATUT  =   '$zStatut'";
    }
    
    if (!empty($iCause1)) {
    $zSqlRecap .= "AND  SUBSTRING(FNC_CAUSE FROM 1 FOR 1)   =   '{$iCause1}'";
    }

/*if(!empty($zTypologie))
{
if($zTypologie == "vide") $zSqlRecap .= "AND (fnc_typologie IS NULL)";
else $zSqlRecap .= "AND fnc_typologie = '$zTypologie' ";
}
if(!empty($zImputation))
{
if($zImputation == "vide") $zSqlRecap .= "AND (fnc_imputation IS NULL)";
else $zSqlRecap .= "AND fnc_imputation = '$zImputation' ";
} << SOLUTION TEMPORAIRE 9420 A ENLEVER */

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
if (!empty($_REQUEST['slctBU'])) {
    $buTemp = $_REQUEST['slctBU'];
    $zSqlRecap .= "and fnc_code in (SELECT distinct fnc_code FROM nc_fiche f
            INNER JOIN gu_application a ON
            substring(f.fnc_code FROM 1 for 3) = a.code
            INNER JOIN  business_unit
            b ON b.id_bu = a.id_bu
            where b.lib_bu ilike '%" . $buTemp . "%'

	   order by fnc_code)";
}
$zSqlRecap .= "  GROUP BY nc_fiche.fnc_id, fnc_type,fnc_client, fnc_code, fnc_statut, \"fnc_creationDate\", fnc_motif,						fnc_cause, fnc_typologie, fnc_imputation, fnc_ref, fnc_process,	fnc_classement, fnc_module, fnc_typo
	                  ";
$sqlBU = "";
$iBU   = trim($_REQUEST['slctBU']);
if (isset($_REQUEST['slctBU']) && $_REQUEST['slctBU'] != '') {
    $sqlBU .= "  and lib_bu ilike '%{$iBU}%'  ";
}
$zSqlRecap .= "
                )AS TEMP
                LEFT JOIN
                    (
                        SELECT DISTINCT
                            LIB_BU
                        ,   FNC_ID
                        FROM
                            NC_FICHE    F
                        INNER JOIN
                            GU_APPLICATION  A
                        ON
                                (
                                    (
                                        CHAR_LENGTH(TRIM(F.FNC_CODE))   =   3
                                    AND SUBSTR(F.FNC_CODE, 0, 4)        =   A.CODE
                                    )
                                AND (
                                        F.FNC_CODE  !=  'QUAL'
                                    OR  F.FNC_CODE  !=  '0VVT001'
                                    )
                                )
                            OR  (
                                    (
                                        CHAR_LENGTH(TRIM(F.FNC_CODE))   =   6
                                    AND SUBSTR(F.FNC_CODE, 0, 4)        =   A.CODE
                                    )
                                AND (
                                        F.FNC_CODE  !=  'QUAL'
                                    OR  F.FNC_CODE  !=  '0VVT001'
                                    )
                                )
                            OR  (
                                    (
                                        CHAR_LENGTH(TRIM(F.FNC_CODE))   =   7
                                    AND F.FNC_CODE  ILIKE   '0%'
                                    AND F.FNC_CODE                      !=  '0VVT001'
                                    AND SUBSTR(F.FNC_CODE, 2, 3)        =   A.CODE
                                    )
                                AND F.FNC_CODE  !=  'QUAL'
                                )
                        INNER JOIN
                            BUSINESS_UNIT   B
                        ON
                            B.ID_BU     =   A.ID_BU" . $sqlBU . "
                        -- and lib_bu ='RC'
                        UNION
                        SELECT
                            LIB_BU
                        ,   FNC_ID
                        FROM    NC_FICHE    NCF
                        INNER JOIN
                            BUSINESS_UNIT   BU
                        ON
                            NCF.FNC_BU  =   BU.ID_BU
                        AND (
                                NCF.FNC_CODE    =   'QUAL'
                            OR  NCF.FNC_CODE    =   '0VVT001'
                            )    
                    )   AS  TEMP2
                ON
                    TEMP2.FNC_ID    =   TEMP.ID_FNC
                ORDER BY
                    FNC_CLIENT  ASC
   ";
$oQueryRecap = @pg_query($conn, $zSqlRecap) or die($zSqlRecap . @pg_last_error($conn));
$iNbRecap    = @pg_num_rows($oQueryRecap);
/* echo '<pre>';
print_r($zSqlRecap);
echo '<pre>';*/
// contenu de la recherche
$sqlBU   = "";
$corps   = "	<input type='hidden' id='fnc_nb' name='fnc_nb' value='{$iNbRecap}' />";
$aCauses = array();
for ($i = 0; $i < $iNbRecap; $i++) {
    $toResRecap = @pg_fetch_array($oQueryRecap, $i);

    $idFnc      = $toResRecap['id_fnc'];
    $tx_avacmnt = $toResRecap['tx_avacmnt'];

    $tx_restant = 0;
    $tx_restant = 100 - $tx_avacmnt;
    $tx_avacmnt = number_format((float) $tx_avacmnt, 2, '.', '');

    /****************** Modif Fulgence 20150210  ******************/
    // Affichage criticité
    if ($toResRecap['fnc_gravite_id'] != '' && $toResRecap['fnc_frequence_id'] != '') {
        //if($toResRecap['fnc_gravite_id'] != '')
        $igrav = $toResRecap['fnc_gravite_id'];
        /*else
        $igrav = 1 ;

        if($toResRecap['fnc_frequence_id'] != '')*/
        $ifreq = $toResRecap['fnc_frequence_id'];
        /*else
        $ifreq = 1 ;*/

        // gravité
        $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie WHERE id_categorie_grav=$igrav ";
        //echo $sqlSltGrv ;
        $resGrv     = pg_query($conn, $sqlSltGrv) or die(pg_last_error($conn));
        $arGrv      = pg_fetch_array($resGrv);
        $grv_ech    = $arGrv['echelle_id_grav'];
        $grv_cat_id = $arGrv['id_categorie_grav'];
        $lib_cat    = $arGrv['libelle_gravite'];

        //Fréquence
        $sqlSltFrq  = "SELECT id_categorie_freq, echelle_id_freq, libelle_frequence FROM nc_frequence_categorie WHERE id_categorie_freq = $ifreq ";
        $resFrq     = pg_query($conn, $sqlSltFrq) or die(pg_last_error($conn));
        $arrFrq     = pg_fetch_array($resFrq);
        $frq_ech    = $arrFrq['echelle_id_freq'];
        $frq_cat_id = $arrFrq['id_categorie_freq'];
        $frq_libele = $arrFrq['libelle_frequence'];

        if ($grv_ech == 1) {
            $criticite = "m";
        } elseif ($grv_ech == 2 && $frq_ech <= 2) {
            $criticite = "m";
        } elseif ($grv_ech == 2 && $frq_ech >= 3) {
            $criticite = "M";
        } elseif ($grv_ech == 3 && $frq_ech < 4) {
            $criticite = "M";
        } elseif ($grv_ech == 3 && $frq_ech >= 4) {
            $criticite = "C";
        } elseif ($grv_ech >= 4) {
            $criticite = "C";
        }

        /*else
        $criticite = "m" ;*/

        // test de couleur
        if ($criticite == "m") {
            $colr      = "style='background-color:#FCF03F;font-weight:bold;'";
            $criticite = "mineure";
        } elseif ($criticite == "M") {
            $colr      = "style='background-color:#F28810;font-weight:bold;'";
            $criticite = "Majeure";
        } elseif ($criticite == "C") {
            $colr      = "style='background-color:#E71D07;color:#FFFFFF;font-weight:bold;'";
            $criticite = "Critique";
        }
    } else {
        //$color = "style='background-color:#FFFFFF;font-weight:bold;'" ;
        $colr      = ($i % 2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'");
        $criticite = "";
    }
    /****************** Fin modif ******************/

    /*
    $aCauses = explode("*#|#*", $toResRecap['fnc_cause']);
    $sCause2 = $aCauses[1];
    $sCause3 = $aCauses[2];
    $aResLibelleCause1 = @pg_fetch_array(@pg_query($conn, "SELECT libelle FROM nc_cause WHERE id = '{$aCauses[0]}';"));
     */

    // selection libelle nc_motif apparition
    $sqlSlctAppar = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Apparition' AND nc_fiche.fnc_id = '$idFnc' ;";

    $rQueryAppar = @pg_query($conn, $sqlSlctAppar);
    $iNbAppar    = @pg_num_rows($rQueryAppar);
    $appar       = '';
    for ($j = 0; $j < $iNbAppar; $j++) {
        $aResLibelAppar = @pg_fetch_array($rQueryAppar);

        $apparLib = $aResLibelAppar['libelle'];
        $virg     = (($j == ($iNbAppar - 1)) ? '' : ',');
        $appar    = $appar . $apparLib . $virg;
    }

    // selection libelle nc_motif detection
    $sqlSlctDetect = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Détection' AND nc_fiche.fnc_id = '$idFnc';";

    $rQueryDetect = @pg_query($conn, $sqlSlctDetect);
    $iNbDetect    = @pg_num_rows($rQueryDetect);
    $detect       = '';
    for ($j = 0; $j < $iNbDetect; $j++) {
        $aResLibelDetect = @pg_fetch_array($rQueryDetect);

        $detectLib = $aResLibelDetect['libelle'];
        $virg      = (($j == ($iNbDetect - 1)) ? '' : ',');
        $detect    = $detect . $detectLib . $virg;
    }

    // selection libelle nc_motif Organisation
    $sqlSlctOrganis = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Organisation' AND nc_fiche.fnc_id = '$idFnc';";

    $rQueryOrganis = @pg_query($conn, $sqlSlctOrganis);
    $iNbOrganis    = @pg_num_rows($rQueryOrganis);
    $organis       = '';
    for ($j = 0; $j < $iNbOrganis; $j++) {
        $aResLibelOrganis = @pg_fetch_array($rQueryOrganis);

        $organisLib = $aResLibelOrganis['libelle'];
        $virg       = (($j == ($iNbOrganis - 1)) ? '' : ',');
        $organis    = $organis . $organisLib . $virg;
    }

    $sCode3         = "";
    $color          = (($i % 2 == 0) ? "#ffffff" : "#efefef");
    $oResTypologie  = @pg_fetch_array(@pg_query($conn, "SELECT typologie_libelle FROM nc_typologie WHERE typologie_id = " . $toResRecap['fnc_typologie'] . ""));
    $oResImputation = @pg_fetch_array(@pg_query($conn, "SELECT imputation_libelle FROM nc_imputation WHERE imputation_id = " . $toResRecap['fnc_imputation'] . ""));

    $iId = $toResRecap['fnc_id'];
    //$sTypo            = $toResRecap['fnc_typo'];

    if (trim($toResRecap['fnc_client']) == "Autres") {
        $sClient = $toResRecap['fnc_autre_cplmnt'];
    } else {
        $sClient = $toResRecap['fnc_client'];
    }

    $sCode   = $toResRecap['fnc_code'];
    $fncref  = $toResRecap['fnc_ref'];
    $fncreat = $toResRecap['fnc_createur'];

    // Ajouter par Mle 9092, pour prendre prenompersonnel

    $sqlCreater = "SELECT prenompersonnel FROM personnel WHERE matricule = " . $fncreat;
    $resCreater = @pg_query($conn, $sqlCreater);
    $oCreateur  = @pg_fetch_array($resCreater, 0);
    /*
     ***
     *
     */
    $bSqlNcInfos = "";
    $bSqlNcInfos .= "
            select * from (
            select libelle as description,fnc_id from nc_action_liste ncactlist
            inner join nc_fnc_action nclact
            on ncactlist.id  = nclact.action_liste_id
            ) as descr
            left join (	select ncaction.fnc_id,ncinfo.* from nc_fnc_infos ncinfo
               inner join nc_fnc_action ncaction  on
               ncaction.fnc_info_id = ncinfo.id

            ) as temp on  descr.fnc_id = temp.fnc_id where temp.fnc_id::integer = {$idFnc}
                     ";

    $resultNcInfos    = pg_query($conn, $bSqlNcInfos);
    $arrtNcInfos      = pg_fetch_array($resultNcInfos);
    $failleIdentifiee = $arrtNcInfos['faille_identifiee'];
    $impact           = $arrtNcInfos['impact'];
    $responsable      = $arrtNcInfos['responsable'];
    $bdateDebut       = $arrtNcInfos['date_debut'];
    $bdateFin         = $arrtNcInfos['date_fin'];
    $bdateSuivi       = $arrtNcInfos['date_suivi'];
    $indicEfficacite  = $arrtNcInfos['indic_efficacite'];
    $objEcheance      = $arrtNcInfos['obj_echeance'];
    $bcommentaire     = $arrtNcInfos['commentaire'];
    $bgeneralisation  = $arrtNcInfos['generalisation'];
    $bdescription     = $arrtNcInfos['description'];
    $betat            = $arrtNcInfos['etat'];
    $bvalid_action    = $arrtNcInfos['valid_action'];
    /*
     ***
     *
     */
    for ($iSizeOfCode = 0; $iSizeOfCode < 3; $iSizeOfCode++) {
        $sCode3 .= $sCode[$iSizeOfCode];
    }

    $sSqlProcessClassementModule = "	SELECT	DISTINCT mtp_naturecmd, mtp_classification, mtp_unitefonction
											FROM	mth_priorite
											WHERE	mtp_clientidreel = '{$sCode3}'";
    $aResProcessClassementModule = @pg_fetch_array(@pg_query($conn, $sSqlProcessClassementModule));
    if (empty($toResRecap['fnc_process'])) {
        $sProcess = $aResProcessClassementModule['mtp_naturecmd'];
    } else {
        $sProcess = $toResRecap['fnc_process'];
    }

    if (empty($toResRecap['fnc_classement'])) {
        $sClassement = $aResProcessClassementModule['mtp_classification'];
    } else {
        $sClassement = $toResRecap['fnc_classement'];
    }

    if (empty($toResRecap['fnc_module'])) {
        $sModule = $aResProcessClassementModule['mtp_unitefonction'];
    } else {
        $sModule = $toResRecap['fnc_module'];
    }

    $sSatut        = $toResRecap['fnc_statut'];
    $dCreationDate = $toResRecap['fnc_creationDate'];
    $sType         = $toResRecap['fnc_type'];
    $sMotif        = $toResRecap['fnc_motif'];
    //$sCause            = $toResRecap['fnc_cause'];
    $sTypologie  = $oResTypologie['typologie_libelle'];
    $sImputation = $oResImputation['imputation_libelle'];
    $bu          = $_REQUEST['slctBU'];
    $libBu       = $toResRecap['lib_bu'];

    if (trim($toResRecap['fnc_0vvt']) == '' && $sCode == '0VVT001') {
        $libBu = 'AUCUN';
    }
    $corps .= "		<input type='hidden' id='fnc_id_{$i}' name='fnc_id_{$i}' value='{$iId}' />
						<tr bgcolor='$color' class='tr-content'>
							<td>{$fncref}</td>
							<td>{$sClient}</td>
							<td >" . $libBu . "</td>
							<td>{$sCode}</td>
							<td>";
    if ($sSatut == "bouclé") {
        $corps .= "cl&ocirc;tur&eacute;e";
    } else {
        $corps .= $sSatut;
    }

    $corps .= "
                     </td>
							<td>{$dCreationDate}</td>
							<td>{$fncreat} - {$oCreateur['prenompersonnel']}</td>
							<td>{$sType}</td>
							<td>{$sMotif}</td>

							<!--td>{$aResLibelleCause1['libelle']}</td-->
							<!--<td id=\"idGrvt\">{$lib_cat}</td> -->


							<td>{$appar}</td>
							<td>{$detect}</td>
							<td>{$organis}</td>

                     ";

    $corps .= "	<script type='text/javascript'>
							$(document).ready(function(){
								select_edit_tide('tdTypologie', 'slctTypologie', 'spanTypologie', '{$iId}');
								select_edit_tide('tdImputation', 'slctImputation', 'spanImputation', '{$iId}');
								select_edit_tide('tdProcess', 'slctProcess', 'spanProcess', '{$iId}');
								select_edit_tide('tdClassement', 'slctClassement', 'spanClassement', '{$iId}');
								select_edit_tide('tdModule', 'slctModule', 'spanModule', '{$iId}');
                        select_edit_tide('idGrvt', 'txtGravit', '{$iId}');
                        select_edit_tide('idFrqe', 'txtFrequency', '{$iId}');
							});
						</script>
							<td id='tdTypologie{$iId}'>
								<span id='spanTypologie{$iId}'>{$sTypologie}</span>
								<select style=\"width: 100px; display: none\" id=\"slctTypologie{$iId}\" name=\"slctTypologie{$iId}\">
									<option value=''> *** (choix) *** </option>";
    for ($ii = 0; $ii < $iNbTypologie; $ii++) {
        $toTypologie = @pg_fetch_array($oQueryTypologie, $ii);
        $corps .= "				<option value='" . $toTypologie['typologie_libelle'] . "' ";
        if ($sTypologie == $toTypologie['typologie_libelle']) {
            $corps .= "selected";
        }

        $corps .= ">" . $toTypologie['typologie_libelle'] . "</option>";
    }
    $corps .= "			</select>
							</td>
							<td id='tdImputation{$iId}'>
								<span id='spanImputation{$iId}'>{$sImputation}</span>
								<select style=\"width: 100px; display: none\" id=\"slctImputation{$iId}\" name=\"slctImputation{$iId}\">
									<option value=''> *** (choix) *** </option>";
    for ($ij = 0; $ij < $iNbImputation; $ij++) {
        $toImputation = @pg_fetch_array($oQueryImputation, $ij);
        $corps .= "			<option value='" . $toImputation['imputation_libelle'] . "' ";
        if ($sImputation == $toImputation['imputation_libelle']) {
            $corps .= "selected";
        }

        $corps .= ">" . $toImputation['imputation_libelle'] . "</option>";
    }
    $corps .= "			</select>
							</td>
                       <td $colr>{$criticite}</td>
							<!--td id=\"tdProcess{$iId}\">
								<span id=\"spanProcess{$iId}\">{$sProcess}</span>
								<select style=\"width: 100px; display: none\" id=\"slctProcess{$iId}\" name=\"slctProcess{$iId}\">
									<option value=''> *** (choix) *** </option>";
    for ($ik = 0; $ik < $iNbProcess; $ik++) {
        $aProcess = @pg_fetch_array($rQueryProcess, $ik);
        $corps .= "			<option value='" . $aProcess['process_libelle'] . "' ";
        if ($sProcess == $aProcess['process_libelle']) {
            $corps .= "selected";
        }

        $corps .= ">" . $aProcess['process_libelle'] . "</option>";
    }
    $corps .= "				</select>
							</td-->";
    /*
    <td id=\"tdClassement{$iId}\">
    <span id=\"spanClassement{$iId}\">{$sClassement}</span>
    <select style=\"width: 100px; display: none\" id=\"slctClassement{$iId}\" name=\"slctClassement{$iId}\">
    <option value=''> *** (choix) *** </option>";
    for($il = 0; $il < $iNbClassement; $il ++)
    {
    $aClassement = @pg_fetch_array($rQueryClassement, $il);
    $corps .=  "            <option value='".$aClassement['classement_libelle']."' ";
    if($sClassement == $aClassement['classement_libelle']) $corps .= "selected";
    $corps .= ">".$aClassement['classement_libelle']."</option>";
    }
    $corps .= "                </select>
    </td>
     */
    $corps .= "			<!--td id=\"tdModule{$iId}\">
								<span id=\"spanModule{$iId}\">{$sModule}</span>
								<select style=\"width: 100px; display: none\" id=\"slctModule{$iId}\" name=\"slctModule{$iId}\">
									<option value=''> *** (choix) *** </option>";
    for ($im = 0; $im < $iNbModule; $im++) {
        $aModule = @pg_fetch_array($rQueryModule, $im);
        $corps .= "			<option value='" . $aModule['module_libelle'] . "' ";
        if ($sModule == $aModule['module_libelle']) {
            $corps .= "selected";
        }

        $corps .= ">" . $aModule['module_libelle'] . "</option>";
    }
    $corps .= "				</select>
							</td-->";
    /*
    <td id=\"tdTypo{$iId}\" onclick=\"edit('txtTypo{$iId}', 'spanTypo{$iId}');\">
    <span id=\"spanTypo{$iId}\">{$sTypo}</span>
    <textarea id=\"txtTypo{$iId}\" name=\"txtTypo{$iId}\" style=\"border: 1px solid green; background: white; width: 150px; height: 75px; display: none\" onblur=\"tide('txtTypo{$iId}', 'spanTypo{$iId}')\">{$sTypo}</textarea>
    </td>
     */
    $tx_avacmnt = $arrtNcInfos['tx_avacmnt'];
    $tx_restant = 0;
    $tx_restant = 100 - $tx_avacmnt;
    $tx_avacmnt = number_format((float) $tx_avacmnt, 2, '.', '');
    $tx_restant = number_format((float) $tx_restant, 2, '.', '');
    $corps .= "
	         <td bgcolor=\"$color\"  >{$failleIdentifiee}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$impact}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bdescription}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$responsable}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bdateDebut}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bdateFin}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bdateSuivi}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$indicEfficacite}</td>
	         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$objEcheance}</td>";

    if ($betat == "en attente" || $betat == "") {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >non entam&eacute;</td>";

    } elseif ($betat == "en cours") {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >en cours</td>";

    } else {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >action faite</td>";

    }

    if ($bvalid_action == 0 || $bvalid_action == "0") {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >non d&eacute;finie</td>";

    } elseif ($bvalid_action == 1 || $bvalid_action == "1") {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >OK</td>";

    } else {

        $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >KO</td>";

    }

    $corps .= "
       		<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bcommentaire}</td>
		<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" ><center>{$tx_avacmnt}</center></td>
		<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" ><center>{$tx_restant}</center></td>
    		<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bgeneralisation}</td>
      ";
    $corps .= "		</tr>";
}
$pied = "		</tbody></table>";

// affichage
?>
	<body>
   <center><span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /><br/><b>Chargement...</b></span></center>
		<form id="frmAll" name="frmAll" method="POST" action="" style='display:none'>

		<fieldset>
			<legend>R&eacute;capitulatif</legend>

				<table >
					<tr>
						<td >Date du : &nbsp;&nbsp;&nbsp;<input type="text" id="txtDate1" name="txtDate1" class="txtInput" value="<?php echo $dDate1; ?>" readonly />&nbsp;&nbsp;&nbsp;au :&nbsp;&nbsp;&nbsp;<input type="text" id="txtDate2" name="txtDate2" class="txtInput" value="<?php echo $dDate2; ?>" readonly />&nbsp;&nbsp;&nbsp;<input type="submit" id="btnSubmit" name="btnSubmit" class="ui-state-default" value="Afficher" />&nbsp;&nbsp;&nbsp;<input type="button" id="btnRI" name="btnRI" class="ui-state-default" value="R&eacute;initialiser"/>&nbsp;&nbsp;&nbsp;<input type="button" id="btnEE" name="btnEE" class="ui-state-default" value="ExportExcel" onClick="exportToXL ()" /></td>

					</tr>
				</table>

			<p>&nbsp;</p>
			<?php

if ($iNbRecap != 0) {
    $resultat = $entete . $corps . $pied;
} else {
    $resultat = $entete . $pied;
}

echo "<div class='wrapper' id='idWrapperCache'  >" . $resultat . '</div>';
?>
		</fieldset>
		</form>
		<p style="color: #B1221C; font-weight: bold; font-size: 12px"><?php if (isset($_REQUEST['admin_msg'])) {
    echo urldecode($_REQUEST['admin_msg']);
} else {
    echo "";
}
?></p>
	</body>
</html>