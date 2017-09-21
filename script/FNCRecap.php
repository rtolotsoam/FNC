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

// requete pour recherche
    
        if($dDate1 != $dDate2) {   

                
                $zSqlRecap      = "	
                        SELECT DISTINCT
                            FNC_REF
                        ,   FNC_CLIENT
                        ,   CASE
                                WHEN
                                    NC_FICHE.FNC_CODE   =   'QUAL'
                                OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                THEN
                                    B1.LIB_BU
                                ELSE
                                    B.LIB_BU
                            END LIB_BU
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
                        LEFT JOIN
                                GU_APPLICATION  GA
                            ON
                                (
                                    NC_FICHE.FNC_CODE   !=  'QUAL'
                                AND NC_FICHE.FNC_CODE   !=  '0VVT001'
                                )
                            AND (
                                    GA.CODE =   SUBSTR(NC_FICHE.FNC_CODE, 0, 4)
                                OR  GA.CODE =   SUBSTR(NC_FICHE.FNC_CODE, 2, 3)
                                )
                        LEFT JOIN
                                BUSINESS_UNIT   B
                            ON
                                B.ID_BU =   GA.ID_BU
                        LEFT JOIN
                                BUSINESS_UNIT   B1
                            ON
                                (
                                    NC_FICHE.FNC_CODE   =   'QUAL'
                                OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                )
                            AND NC_FICHE.FNC_BU =   B1.ID_BU    
                        WHERE
                            NC_FICHE.\"fnc_creationDate\"   >=  '$dDate1'   
                        AND NC_FICHE.\"fnc_creationDate\"   <=  '$dDate2'
                        ";
        }else{

   
                $zSqlRecap      = "    
                        SELECT DISTINCT
                            FNC_REF
                        ,   FNC_CLIENT
                        ,   CASE
                                WHEN
                                    NC_FICHE.FNC_CODE   =   'QUAL'
                                OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                THEN
                                    B1.LIB_BU
                                ELSE
                                    B.LIB_BU
                            END LIB_BU
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
                        LEFT JOIN
                                GU_APPLICATION  GA
                            ON
                                (
                                    NC_FICHE.FNC_CODE   !=  'QUAL'
                                AND NC_FICHE.FNC_CODE   !=  '0VVT001'
                                )
                            AND (
                                    GA.CODE =   SUBSTR(NC_FICHE.FNC_CODE, 0, 4)
                                OR  GA.CODE =   SUBSTR(NC_FICHE.FNC_CODE, 2, 3)
                                )
                        LEFT JOIN
                                BUSINESS_UNIT   B
                            ON
                                B.ID_BU =   GA.ID_BU
                        LEFT JOIN
                                BUSINESS_UNIT   B1
                            ON
                                (
                                    NC_FICHE.FNC_CODE   =   'QUAL'
                                OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                )
                            AND NC_FICHE.FNC_BU =   B1.ID_BU
                        WHERE
                            NC_FICHE.\"fnc_creationDate\"   =  '$dDate1'    
                        ";

        }

 
$zSqlRecap .= "  GROUP BY
                        NC_FICHE.FNC_ID
                    ,   FNC_TYPE
                    ,   FNC_CLIENT
                    ,   FNC_CODE
                    ,   FNC_STATUT
                    ,   \"fnc_creationDate\"
                    ,   FNC_MOTIF
                    ,   FNC_CAUSE
                    ,   FNC_TYPOLOGIE
                    ,   FNC_IMPUTATION
                    ,   FNC_REF
                    ,   FNC_PROCESS
                    ,   FNC_CLASSEMENT
                    ,   FNC_MODULE
                    ,   FNC_TYPO
                    ,   B1.LIB_BU
                    ,   B.LIB_BU
	           ";


$oQueryRecap = @pg_query($conn, $zSqlRecap) or die($zSqlRecap . @pg_last_error($conn));
$iNbRecap    = @pg_num_rows($oQueryRecap);
/* echo '<pre>';
print_r($zSqlRecap);
echo '<pre>';*/
// contenu de la recherche
$corps   = "";
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
            SELECT *
            FROM
              ( SELECT libelle AS description,
                       fnc_id
               FROM nc_action_liste ncactlist
               INNER JOIN nc_fnc_action nclact ON ncactlist.id = nclact.action_liste_id ) AS descr
            LEFT JOIN
              (SELECT ncaction.fnc_id,
                      ncinfo.*
               FROM nc_fnc_infos ncinfo
               INNER JOIN nc_fnc_action ncaction ON ncaction.fnc_info_id = ncinfo.id ) AS TEMP ON descr.fnc_id = temp.fnc_id
            WHERE temp.fnc_id::integer = {$idFnc}
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
    
    $libBu       = $toResRecap['lib_bu'];

    if ($libBu == NULL && $sCode == '0VVT001') {
        $libBu = 'AUCUN';
    }
    $corps .= "		<tr bgcolor='$color' class='tr-content'>
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


							<td>{$appar}</td>
							<td>{$detect}</td>
							<td>{$organis}</td>

                     ";

    $corps .= "
							<td>{$sTypologie}</td>
							<td>{$sImputation}</td>
              <td $colr>{$criticite}</td>";
							
    
    
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