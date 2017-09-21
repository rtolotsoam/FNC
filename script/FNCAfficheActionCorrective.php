<?php
session_start();
/**
 * Créé le 13/07/2012 par Fulgence
 * Modifié le 13/07/2012 par Fulgence
 *
 */

// ------- Accès à la base ------------
require_once "/var/www.cache/dgconn.inc";

include "FNCUser_admin.php";

// Ajout par Fulgence -------------
$dateDeb = date('Y-m-d');
$dateFin = date('Y-m-d');

if (isset($_REQUEST['txtDateDeb'])) {
    $dateDeb = $_REQUEST['txtDateDeb'];
}

if (isset($_REQUEST['txtDateFin'])) {
    $dateFin = $_REQUEST['txtDateFin'];
}

if (isset($_REQUEST['etat'])) {
    $etat = $_REQUEST['etat'];
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
        <?php
if (in_array($_SESSION['matricule'], $array_admin_gen)) {
    ?>
            <style>
            .wrapper {position : relative; height: 450px; border:1px solid #E6EEEE; width:100%; overflow-y : scroll;}
            .ui-datepicker{
                position : relative;
                z-index: 999;
            }
            </style>
        <?php
} else {
    ?>
            <style>
            .wrapper {position : relative; height: 450px; border:1px solid #E6EEEE; width:100%; overflow-y : scroll;}
            .ui-datepicker{
                position : relative;
                z-index: 999;
            }
            </style>
        <?php
}
?>
        <script type="text/javascript">
        $(document).ready (function () {
            $("#isset_affiche").val("0");

           $("#tabAction").tablesorter ({
                    theme:'blue',
                      widthFixed : true,

                     widgets: ['zebra', 'stickyHeaders','filter'],
                     headerTemplate : '{content} {icon}', // Add icon for various themes

                     widgetOptions: {
                   // jQuery selector or object to attach sticky header to
                        stickyHeaders_attachTo : '.wrapper', // or $('.wrapper')
                        stickyHeaders_zIndex : 2

                   }

                    //headers: { 0: { sorter: false}, 15: {sorter: false}, 3: {sorter: false} }
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
        <form id="frmAfficheAction" name="frmAfficheAction" method="post" action="FNCAfficheActionCorrective.php" style="overflow-x: auto;">
            <center>
            <fieldset>
                <legend>Liste des actions correctives</legend>
                <center>
                <table>
               <tr>
                  <td>Actions Cr&eacute;&eacute;es entre le :</td>
                  <td>
                     <input type="text" id="txtDateDeb" name="txtDateDeb" value="<?php echo $dateDeb; ?>" class="txtInput" readonly/>
                  </td>
                  <td>Et le :</td>
                  <td>
                     <input type="text" id="txtDateFin" name="txtDateFin" value="<?php echo $dateFin; ?>" class="txtInput" readonly />
                     <input type = 'hidden' id = 'isset_affiche' name = 'isset_affiche' value = '0' />
                  </td>
                 <td>
                        Etat r&eacute;alisation actions :
                </td>
                    <td>
                    <select id="etat" name="etat" >
                            <option value="*" <?=$etat == '*' ? ' selected="selected"' : '';?>>-- tous --</option>
                            <option value="en cours" <?=$etat == 'en cours' ? ' selected="selected"' : '';?> >en cours</option>
                            <option value="en attente" <?=$etat == 'en attente' ? ' selected="selected"' : '';?>>non entam&eacute;</option>
                            <option value="ok" <?=$etat == 'ok' ? ' selected="selected"' : '';?>>action faite</option>
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
                        <td align="left">
                            <input type="button" id="btnRetour" name="btnRetour" value="Retour" class = "ui-state-default" onclick="javascript: document.location.href = 'FNCSuiviActionNC.php' " />
                            <a href="toExcelActionNC.php?date_deb=<?php echo $dateDeb; ?>&date_fin=<?php echo $dateFin; ?>&etat=<?php echo $etat; ?>" style="text-decoration: none">
                                <input type="button" id="btnExportToExcel" name="btnExportToExcel" class="ui-state-default" value="Export Excel" style="cursor: pointer" />
                            </a>
                        </td>
                    </tr>
                </table>
                <br/>
                <center>
                    <span id='info_modif' style='display:none;'>
                        <br/>
                        <b>Modification &eacute;ffectu&eacute;e</b>
                        <img src='../images/success.png' height='12' width='12'>
                        <br/>
                    </span>
                </center>
                <br/>

        <center>
                <div class='wrapper'>

                <table id="tabAction">
                    <thead>
                        <tr bgcolor="#efefef">
                            <br />
                            <th>Type</th>
                            <th>Nom du client</th>
                            <th>BU</th>
                            <th>Code</th>
                            <th>R&eacute;f&eacute;rence</th>
                            <th>Type d'appel</th>
                            <th>Date de cr&eacute;ation FNC</th>
                            <th>Cr&eacute;&eacute;e par</th>
                            <th>Faille identifi&eacute;e</th>
                            <th>Impact</th>
                            <th>Imputation</th>
                            <th>Description</th>
                            <th>Responsable</th>
                            <th>Date de d&eacute;but d'actions</th>
                            <th>Date fin</th>
                            <th>Date suivi</th>
                            <th>Indicateur d'&eacute;fficacit&eacute;</th>
                            <th>Objectif et &eacute;ch&eacute;ance</th>
                            <th>Etat r&eacute;alisation actions</th>
                            <th>Validation de l'action</th>
                            <th>Commentaire</th>
                            <th>G&eacute;n&eacute;ralisation</th>
                            <th>Taux d'avancement action</th>
                            <th>Taux restant</th>
                            <th>Criticit&eacute;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

    $coul = 0;

    if ($etat != '*') {

        if ($etat == 'en attente') {
            $clWhere = " AND (nfi.etat = 'en attente' or nfi.etat = '') ";
        } else {
            $clWhere = " AND nfi.etat = '$etat' ";
        }

    }

    if($dateDeb != $dateFin){

        $zSqlId = "
                    SELECT DISTINCT
                        NFA.ACTION_LISTE_ID     AS  ID
                    ,   NFI.DATE_DEBUT          AS  DATED_
                    ,   NFI.DATE_FIN            AS  DATEF_
                    ,   NFI.ETAT                AS  ETAT_
                    ,   NFI.FAILLE_IDENTIFIEE   AS  FAILLE_
                    ,   NFI.IMPACT              AS  IMP_
                    ,   NFI.GENERALISATION      AS  GEN_
                    ,   LIBELLE                 AS  DESCRIPTION
                    ,   NFA.FNC_INFO_ID         AS  INF_ID
                    FROM
                        NC_FICHE        NF
                    ,   NC_FNC_ACTION   NFA
                    ,   NC_FNC_INFOS    NFI
                    ,   NC_ACTION_LISTE NAL
                    WHERE
                        NFA.ACTION_LISTE_ID =       NAL.ID
                    AND NF.FNC_ID           =       CAST(NFA.FNC_ID AS  integer)
                    AND NFA.FNC_INFO_ID     =       NFI.ID
                    AND NAL.TYPE            !=      'curative'  $clWhere
                    AND DATE_DEBUT          >=      '$dateDeb'  
                    AND DATE_DEBUT          <=      '$dateFin'
                    GROUP BY
                        NFA.ACTION_LISTE_ID
                    ,   NFI.DATE_DEBUT
                    ,   NFI.DATE_FIN
                    ,   NFI.ETAT
                    ,   NFI.FAILLE_IDENTIFIEE
                    ,   NFI.IMPACT
                    ,   NFI.GENERALISATION
                    ,   LIBELLE
                    ,   NFA.FNC_INFO_ID  
                    ORDER BY
                        NFA.ACTION_LISTE_ID ASC "
        ;

    }else{

        $zSqlId = "
                    SELECT DISTINCT
                        NFA.ACTION_LISTE_ID     AS  ID
                    ,   NFI.DATE_DEBUT          AS  DATED_
                    ,   NFI.DATE_FIN            AS  DATEF_
                    ,   NFI.ETAT                AS  ETAT_
                    ,   NFI.FAILLE_IDENTIFIEE   AS  FAILLE_
                    ,   NFI.IMPACT              AS  IMP_
                    ,   NFI.GENERALISATION      AS  GEN_
                    ,   LIBELLE                 AS  DESCRIPTION
                    ,   NFA.FNC_INFO_ID         AS  INF_ID
                    FROM
                        NC_FICHE        NF
                    ,   NC_FNC_ACTION   NFA
                    ,   NC_FNC_INFOS    NFI
                    ,   NC_ACTION_LISTE NAL
                    WHERE
                        NFA.ACTION_LISTE_ID =       NAL.ID
                    AND NF.FNC_ID           =       CAST(NFA.FNC_ID AS  integer)
                    AND NFA.FNC_INFO_ID     =       NFI.ID
                    AND NAL.TYPE            !=      'curative'  $clWhere
                    AND DATE_DEBUT          =       '$dateDeb'
                    GROUP BY
                        NFA.ACTION_LISTE_ID
                    ,   NFI.DATE_DEBUT
                    ,   NFI.DATE_FIN
                    ,   NFI.ETAT
                    ,   NFI.FAILLE_IDENTIFIEE
                    ,   NFI.IMPACT
                    ,   NFI.GENERALISATION
                    ,   LIBELLE
                    ,   NFA.FNC_INFO_ID         
                    ORDER BY
                        NFA.ACTION_LISTE_ID ASC ";
    }

    //echo $zSqlId ;
    $rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn));

    for ($i = 0; $i < @pg_num_rows($rQueryId); $i++) {
        $reference   = '';
        $type        = '';
        $resSelectId = @pg_fetch_array($rQueryId, $i);
        $id_         = $resSelectId['id'];
        $dated_      = $resSelectId['dated_'];
        $datef_      = $resSelectId['datef_'];
        $etat_       = $resSelectId['etat_'];
        $faille_     = pg_escape_string($resSelectId['faille_']);
        $imp_        = str_replace("'", "''", $resSelectId['imp_']);
        $gen_        = $resSelectId['gen_'];
        $libelle     = $resSelectId['description'];
        $inf_id      = $resSelectId['inf_id'];

        $zSql = "
                    SELECT DISTINCT
                        NC_FICHE.FNC_REF    AS  REF
                    ,   NC_FICHE.FNC_TYPE   AS  TYPE
                    FROM
                        NC_FNC_ACTION
                    ,   NC_ACTION_LISTE
                    ,   NC_FICHE
                    ,   NC_FNC_INFOS
                    WHERE
                        NC_FICHE.FNC_ID                 =   CAST(NC_FNC_ACTION.FNC_ID   AS  integer)
                    AND NC_FNC_INFOS.ID                 =   NC_FNC_ACTION.FNC_INFO_ID
                    AND NC_FNC_ACTION.ACTION_LISTE_ID   =   $id_
                    AND NC_FNC_INFOS.ETAT               =   '$etat_'
                    AND NC_FNC_INFOS.DATE_DEBUT         =   '$dated_'
                    AND NC_FNC_INFOS.DATE_FIN           =   '$datef_'
                    AND NC_FNC_INFOS.IMPACT             =   '$imp_'
                    
                                    ";

        $rQueryId_ = @pg_query($conn, $zSql) or die(@pg_last_error($conn));
        for ($j = 0; $j < @pg_num_rows($rQueryId_); $j++) {
            $resRefId = @pg_fetch_array($rQueryId_, $j);
            if ($reference == '') {
                $reference = $resRefId['ref'];
            } else {
                $reference .= ', ' . $resRefId['ref'];
            }

            //$type = $resRefId['type'] ;
            if ($type == '') {
                $type = $resRefId['type'];
            } else {
                $type .= '-' . $resRefId['type'];
            }

        }

        $zSqlInfo = "
                        SELECT DISTINCT
                            NC_FICHE.FNC_CREATEUR
                        ,   CASE
                                    WHEN
                                        NC_FICHE.FNC_CODE   =   'QUAL'
                                    OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                    THEN
                                        B1.LIB_BU
                                    ELSE
                                        B.LIB_BU
                            END LIB_BU    
                        ,   NC_FICHE.FNC_CODE
                        ,   NC_FICHE.\"fnc_creationDate\"   AS  DATE_CREAT
                        ,   DATE_DEBUT                  AS  DATEDEB
                        ,   DATE_FIN                    AS  DATEFIN
                        ,   RESPONSABLE
                        ,   ETAT
                        ,   FAILLE_IDENTIFIEE
                        ,   IMPACT
                        ,   FNC_IMPUTATION
                        ,   OBJ_ECHEANCE
                        ,   INDIC_EFFICACITE
                        ,   GENERALISATION
                        ,   DATE_SUIVI
                        ,   COMMENTAIRE
                        ,   NC_FNC_INFOS.ID             AS  IDINFO
                        ,   LIBELLE                     AS  DESCRIPTION
                        ,   INDICE
                        ,   NC_FNC_INFOS.VALID_ACTION
                        ,   NC_FICHE.FNC_ID             AS  FNCID"
        ;

        $zSqlInfo .= "
                        ,   FNC_GRAVITE_ID
                        ,   FNC_FREQUENCE_ID
                        ,   FNC_FREQ_CAT_ID
                        ,   FNC_GRAV_CAT_ID
                        ,   NC_FICHE.FNC_ID     FNC_ID_L
                        ,   NC_FICHE.FNC_CLIENT
                        ,   TX_AVACMNT  "
        ;

        $zSqlInfo .= "
                        FROM
                            NC_FNC_INFOS
                            INNER JOIN
                                    NC_FNC_ACTION
                                ON
                                    NC_FNC_ACTION.FNC_INFO_ID   =   NC_FNC_INFOS.ID
                            INNER JOIN
                                    NC_FICHE
                                ON
                                    NC_FICHE.FNC_ID =   NC_FNC_ACTION.FNC_ID::integer
                            INNER JOIN
                                    NC_ACTION_LISTE
                                ON
                                    NC_ACTION_LISTE.ID  =   NC_FNC_ACTION.ACTION_LISTE_ID
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
                            NC_FNC_INFOS.ID                 =   NC_FNC_ACTION.FNC_INFO_ID
                        AND NC_FNC_INFOS.ID                 =   $inf_id
                        AND NC_FICHE.FNC_ID                 =   CAST(NC_FNC_ACTION.FNC_ID   AS  integer)
                        AND NC_ACTION_LISTE.ID              =   NC_FNC_ACTION.ACTION_LISTE_ID
                        AND NC_FNC_ACTION.ACTION_LISTE_ID   =   $id_
                        AND NC_FNC_INFOS.ETAT               =   '$etat_'
                        AND NC_FNC_INFOS.DATE_DEBUT         =   '$dated_'
                        AND NC_FNC_INFOS.DATE_FIN           =   '$datef_'
                        AND NC_ACTION_LISTE.TYPE            !=  'curative'
                        AND NC_FNC_INFOS.IMPACT             =   '$imp_' 
                        AND NC_FNC_INFOS.FAILLE_IDENTIFIEE  =   '$faille_'
                        "
        ;

        /*echo "<br/>";
        print_r("<pre>");
        print_r($zSqlInfo);
        print_r("</pre>");
        echo "<br/>";*/

        $rQueryInfo = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn));
        $toRes      = @pg_fetch_array($rQueryInfo, 0);

        /****************** Modif Fulgence 20150210  ******************/
        // Affichage criticité
        if ($toRes['fnc_grav_cat_id'] != '' && $toRes['fnc_freq_cat_id'] != '') {
            //if($toRes['fnc_grav_cat_id'] != '')
            $cat_id_grav = $toRes['fnc_grav_cat_id'];
            /*else
            $cat_id_grav = 1 ;

            if($toRes['fnc_freq_cat_id'] != '')*/
            $cat_id_freq = $toRes['fnc_freq_cat_id'];
            /*else
            $cat_id_freq= 1 ;*/

            // gravité
            $sqlSltGrv = "
                        SELECT
                            ID_CATEGORIE_GRAV
                        ,   ECHELLE_ID_GRAV
                        FROM
                            NC_GRAVITE_CATEGORIE
                        WHERE
                            ID_CATEGORIE_GRAV   =   $cat_id_grav    "
            ;
            //echo $sqlSltGrv ;
            $resGrv  = pg_query($conn, $sqlSltGrv) or die(pg_last_error($conn));
            $arGrv   = pg_fetch_array($resGrv);
            $grv_ech = $arGrv['echelle_id_grav'];

            //Fréquence
            $sqlSltFrq = "
                        SELECT
                            ID_CATEGORIE_FREQ
                        ,   ECHELLE_ID_FREQ
                        FROM
                            NC_FREQUENCE_CATEGORIE
                        WHERE
                            ID_CATEGORIE_FREQ   =   $cat_id_freq    "
            ;
            $resFrq  = pg_query($conn, $sqlSltFrq) or die(pg_last_error($conn));
            $arrFrq  = pg_fetch_array($resFrq);
            $frq_ech = $arrFrq['echelle_id_freq'];

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
            $color     = ($i % 2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'");
            $criticite = "";
        }
        /****************** Fin modif ******************/

        if (empty($toRes['datefin'])) {
            $color = "#000000";
        } else {
            if ($toRes['datefin'] < date("Y-m-d")) {
                $color = "#FF0066";
            } elseif ($toRes['datefin'] == date("Y-m-d")) {
                $color = "#FF9933";
            } else {
                $color = "#008000";
            }

        }
        $aDescrition = explode("*#|#*", $toRes['description']);

        // Type appel
        if (isset($toRes['fncid'])) {
            $sqlAppeType = "
                             select libelle_typologie from cc_sr_typologie cc_typo inner join nc_fiche  ncf
                             on ncf.id_cc_sr_typo = cc_typo.id_typologie
                             where fnc_id = " . $toRes['fncid'];

            $resAppeType = pg_query($conn, $sqlAppeType) or die(pg_last_error($conn));
            $arAppeType  = pg_fetch_array($resAppeType);
            $gAppeType   = $arAppeType['libelle_typologie'];
            // $typeApp = 'Aucun';
            if ($gAppeType == '') {
                $typeApp = 'Aucun';
            } else {
                $typeApp = $gAppeType;
            }
        }

        /**
         * ajouter par Mle 9092, pour afficher imputation
         */

        $id_imputation = $toRes['fnc_imputation'];

        $sqlImputation = "
                            SELECT
                                IMPUTATION_LIBELLE
                            FROM
                                NC_IMPUTATION
                            WHERE
                                IMPUTATION_ID       =   " . $id_imputation . "
                            AND IMPUTATION_ACTIF    IS  NULL";

        $resImputation = @pg_query($conn, $sqlImputation);

        $oImputation = @pg_fetch_array($resImputation, 0);

        /**
         *
         */

        // ajouter par mle 9092, pour afficher prenompersonnel

        $sqlCreater = "
                        SELECT
                            PRENOMPERSONNEL
                        FROM
                            PERSONNEL
                        WHERE
                            MATRICULE   =   " . $toRes['fnc_createur']
        ;
        $resCreater = @pg_query($conn, $sqlCreater);
        $oCreateur  = @pg_fetch_array($resCreater, 0);

        ?>
                        <tr <?php if ($coul % 2 == 0) {
            echo 'class="odd"';
        }
        ?>>
                            <td><?php echo $type; ?></td>
                            <td><?php echo $toRes['fnc_client']; ?></td>
                            <td><?php echo $toRes['lib_bu']; ?></td>
                            <td><?php echo $toRes['fnc_code']; ?></td>
                            <td><input type="hidden"  id="ref_<?php echo $toRes['idinfo']; ?>" value="<?php echo $reference; ?>"><?php echo $reference; ?></td>
                            <td><?php echo utf8_decode($typeApp); ?></td>
                            <td><?php echo $toRes['date_creat']; ?></td>
                            <td><?php echo $toRes['fnc_createur'] . " - " . $oCreateur['prenompersonnel']; ?></td>
                            <td><?php echo $toRes['faille_identifiee']; ?></td>
                            <td><?php echo $toRes['impact']; ?></td>
                            <td><?php echo $oImputation['imputation_libelle']; ?></td>
                            <td><?php echo $aDescrition[0]; ?></td>
                            <td><?php echo $toRes['responsable']; ?></td>
                            <td><?php echo $toRes['datedeb']; ?></td>
                            <td><?php echo $toRes['datefin']; ?></td>
                            <td><?php echo $toRes['date_suivi']; ?></td>
                            <td><?php echo $toRes['indic_efficacite']; ?></td>
                            <td><?php echo $toRes['obj_echeance']; ?></td>
                            <td>
                                <?php
if ($toRes['etat'] == "en attente" || $toRes['etat'] == "") {
            echo "non entam&eacute;";
        } elseif ($toRes['etat'] == "en cours") {
            echo "en cours";
        } else {
            echo "action faite";
        }

        ?>
                            </td>
                            <td>
                                <?php
if ($toRes['valid_action'] == "0" || $toRes['valid_action'] == 0) {
            echo "non d&eacute;finie";
        } elseif ($toRes['valid_action'] == "1" || $toRes['valid_action'] == 1) {
            echo "OK";
        } else {
            echo "KO";
        }

        ?>
                            </td>
                            <td><?php echo $toRes['commentaire']; ?></td>
                            
                            <td><?php echo $toRes['generalisation']; ?></td>

    <?php
        $tx_avacment = $toRes['tx_avacmnt'];
        $tx_restant  = 0;
        $tx_restant  = 100 - $tx_avacment;
        $tx_restant  = number_format((float) $tx_restant, 2, '.', '');
        $tx_avacment = number_format((float) $tx_avacment, 2, '.', '');

        ?>
                            <td><?php echo $tx_avacment . "%"; ?></td>
                            <td><?php echo $tx_restant . "%"; ?></td>
                            <td <?php echo $colr; ?>><?php echo $criticite; ?></td-->
                        </tr>
                        <?php
$coul++;
    }
    ?>
                    </tbody>
                </table>
                </div>
        </center>
                <?php
}
?>
            </fieldset>
        </center>
        </form>


    </body>
</html>
