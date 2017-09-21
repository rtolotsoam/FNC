<?php

/**
 * Créé le 13/07/2012 par Fulgence
 * Modifié le 13/07/2012 par Fulgence
 *
 */

// ------- Accès à la base ------------
require_once "/var/www.cache/dgconn.inc";

$date_deb = $_REQUEST['date_deb'];
$date_fin = $_REQUEST['date_fin'];
$etat     = $_REQUEST['etat'];

$sFileName = "listeActionsCorrectives.xls";

$sContent = " 
<p>
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
        <tr style=\"background-color : #E6EEEE;\">
            <td><strong>Type</strong></td>
            <td><strong>Nom du client</strong></td>
            <td style=\"text-align : center;\"><strong>BU</strong></td>
            <td style=\"text-align : center;\"><strong>Code</strong></td>
            <td><strong>R&eacute;f&eacute;rence</strong></td>
            <td><strong>Type d'appel</strong></td>
            <td><strong>Date de cr&eacute;ation FNC</strong></td>
            <td><strong>Cr&eacute;&eacute;e par</strong></td>
            <td><strong>Faille identifi&eacute;e</strong></td>
            <td><strong>Impact</strong></td>
            <td><strong>Imputation</strong></td>
            <td><strong>Description</strong></td>
            <td><strong>Responsable</strong></td>
            <td><strong>Date de d&eacute;but d'actions</strong></td>
            <td><strong>Date fin</strong></td>
            <td><strong>Date suivi</strong></td>
            <td><strong>Indicateur d'&eacute;fficacit&eacute;</strong></td>
            <td><strong>Objectif et &eacute;ch&eacute;ance</strong></td>
            <td><strong>Etat r&eacute;alisation actions</strong></td>
            <td><strong>Validation de l'action</strong></td>
            <td><strong>Commentaire</strong></td>
            <td><strong>G&eacute;n&eacute;ralisation</strong></td>
            <td><strong>Taux d'avancement action</strong></td>
            <td><strong>Taux restant</strong></td>
            <td><strong>Criticit&eacute;</strong></td>
        </tr>
                            ";

if ($etat != '*') {

    if ($etat == 'en attente') {
        $clWhere = " AND (nfi.etat = 'en attente' or nfi.etat = '') ";
    } else {
        $clWhere = " AND nfi.etat = '$etat' ";
    }

}

if($date_deb != $date_fin){

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
                    AND DATE_DEBUT          >=      '$date_deb'  
                    AND DATE_DEBUT          <=      '$date_fin'
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
                    AND DATE_DEBUT          =      '$date_deb'
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

$rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn));

$clWere = '';
$cpt    = 0;

for ($i = 0; $i < @pg_num_rows($rQueryId); $i++) {
    $reference   = '';
    $type        = '';
    $resSelectId = @pg_fetch_array($rQueryId, $i);
    $id_         = $resSelectId['id'];
    $dated_      = $resSelectId['dated_'];
    $datef_      = $resSelectId['datef_'];
    $etat_       = $resSelectId['etat_'];
    $faille_     = $resSelectId['faille_'];
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
            AND NC_FNC_INFOS.ID                 =   $inf_id
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
                    ,   NC_FICHE.\"fnc_creationDate\"   AS DATE_CREAT
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
                    ,   NC_FNC_INFOS.VALID_ACTION   "
                    ;

    $zSqlInfo .= " 
                    ,   FNC_GRAVITE_ID
                    ,   FNC_FREQUENCE_ID
                    ,   FNC_FREQ_CAT_ID
                    ,   FNC_GRAV_CAT_ID
                    ,   NC_FICHE.FNC_ID         FNC_ID_L
                    ,   NC_FICHE.FNC_CLIENT
                    ,   NC_FICHE.FNC_ID     AS  FNCID
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
                                                    ";
    //echo $zSqlInfo;die();
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
        $resFrq    = pg_query($conn, $sqlSltFrq) or die(pg_last_error($conn));
        $arrFrq    = pg_fetch_array($resFrq);
        $frq_ech   = $arrFrq['echelle_id_freq'];

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

    $bgColor = ($i % 2 == 1) ? "#ffffff" : "#F0F0F6";

    /*if(empty($toRes['datefin'])) $color = "#000000";
    else{
    if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
    elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
    else $color = "#008000";
    }*/

    $aDescrition = explode("*#|#*", $toRes['description']);

    //type d'appel
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
            $typeApp = utf8_decode($gAppeType);
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
                        MATRICULE   =   ".$toRes['fnc_createur']
                    ;
    $resCreater = @pg_query($conn, $sqlCreater);
    $oCreateur  = @pg_fetch_array($resCreater, 0);

    //taux
    $tx_avacment = $toRes['tx_avacmnt'];
    $tx_restant  = 0;
    $tx_restant  = 100 - $tx_avacment;
    $tx_restant  = (float) $tx_restant;
    $tx_avacment = (float) $tx_avacment;

    $sContent .= "<tr>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$type}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['fnc_client']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['lib_bu']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['fnc_code']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$reference}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$typeApp}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['date_creat']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['fnc_createur']} - {$oCreateur['prenompersonnel']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['faille_identifiee']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['impact']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$oImputation['imputation_libelle']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$aDescrition[0]}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['responsable']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datedeb']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datefin']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['date_suivi']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['indic_efficacite']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['obj_echeance']}</td>
                        ";

    if ($toRes['etat'] == "en attente" || $toRes['etat'] == "") {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >non entam&eacute;</td>";
    } elseif ($toRes['etat'] == "en cours") {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >en cours</td>";
    } else {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >action faite</td>";
    }

    if ($toRes['valid_action'] == "0") {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >non d&eacute;finie</td>";
    } elseif ($toRes['valid_action'] == "1") {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >OK</td>";
    } else {
        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >KO</td>";
    }

    "<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['etat']}</td>";

    $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['commentaire']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['generalisation']}</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$tx_avacment}%</td>
                        <td bgcolor = '$bgColor'  style = 'color: $color' >{$tx_restant}%</td>
                        <td $colr >{$criticite}</td>
                    </tr> ";
}
$sContent .= "</table></p>";

ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream;UTF-8");
header("Content-Disposition: attachment; filename = $sFileName");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check = 0, pre-check = 0", false);
header("Content-Length: " . strlen($sContent));

print $sContent;

exit;
