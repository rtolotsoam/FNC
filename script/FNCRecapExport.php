<?php
require_once "DBConnect.php";

if (isset($_REQUEST['txtDate1'])) {
    $dDate1 = $_REQUEST['txtDate1'];
    $dDate2 = $_REQUEST['txtDate2_'];
} else {
    $dDate1 = date("Y-m-01");
    $dDate2 = date("Y-m-d");}

// fnc
function _sql($_conn, $_column)
{
    $zSqlFnc   = "SELECT   DISTINCT $_column AS valeur FROM nc_fiche WHERE ($_column IS NOT NULL OR $_column <> '') ORDER BY $_column";
    $oQueryFnc = @pg_query($_conn, $zSqlFnc);
    $iNbFnc    = @pg_num_rows($oQueryFnc);

    $res = "<option value=\"\" style=\"font-weight: normal; font-style: italic; \">(tous)</option>";

    for ($i = 0; $i < $iNbFnc; $i++) {
        $toFnc = @pg_fetch_array($oQueryFnc, $i);
        $res .= " <option value=\"" . $toFnc['valeur'] . "\" >" . $toFnc['valeur'] . "</option>";
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
$entete = "    <table id=\"tabNC\" name=\"tabNC\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                  <tr >
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" colspan=\"8\" align=\"center\"><b>FNC</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" colspan=\"3\" align=\"center\"><b>Analyse</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" colspan=\"3\" align=\"center\"><b></b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" align=\"center\"><b></b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" align=\"center\"><b></b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" colspan=\"9\" align=\"center\"><b>Actions</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" colspan=\"5\" align=\"center\"><b></b></td>


                  </tr>
                  <tr >
                  <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>R&eacute;ference</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Nom du Client</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>BU</b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Code</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Statut</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Date de cr&eacute;ation FNC </b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Cr&eacute;&eacute;e par </b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Type</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Motif cr&eacute;ation FNC</b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Motif d&rsquo;apparition</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Motif de non d&eacute;tection</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\"><b>Motif  d&eacute;coulant de l&rsquo;organisation</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Typologie</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\"style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Imputation</b></td>

                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Criticit&eacute;</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Faille identifi&eacute;e</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Impact</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Description</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Responsable</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Date de d&eacute;but d'actions</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Date de fin</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Date suivi</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Indicateur d&rsquo;&eacute;fficacit&eacute;</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Objectif et &eacute;ch&eacute;ance</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Etat r&eacute;alisation actions</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Validation de l'action</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Commentaire</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Taux d'avancement action</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>Taux restant</b></td>
                     <td class=\"entete\"  bgcolor = \"#1E5799\" style=\"padding-top: 5px; padding-left: 5px; color:white;\" ><b>G&eacute;n&eacute;ralisation</b></td>
                  </tr>";
/*
$entete .= "<th style=\"padding-top: 5px; padding-left: 5px\">Typo</th>
 */
// echo $entete ;

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
$zSqlRecap = "select*from(";
$zSqlRecap .= "   SELECT   fnc_ref,fnc_client, fnc_code, fnc_createur, fnc_statut,
                     \"fnc_creationDate\", fnc_type, nc_fiche.fnc_id as id_fnc,
                     fnc_motif, fnc_cause, fnc_typologie,
                     fnc_imputation, fnc_ref, fnc_process,
                     fnc_classement, fnc_module, fnc_typo,fnc_autre_cplmnt ";
$zSqlRecap .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id ";
$zSqlRecap .= " FROM nc_fiche LEFT JOIN nc_motif ON nc_motif.fnc_id = nc_fiche.fnc_id
               WHERE    nc_fiche.\"fnc_creationDate\" BETWEEN '$dDate1' AND '$dDate2' ";

               
if (!empty($zClient)) {
    $zSqlRecap .= "AND fnc_client = '$zClient' ";
}

if (!empty($zCode)) {
    $zSqlRecap .= "AND fnc_code = '$zCode' ";
}

if (!empty($zType)) {
    $zSqlRecap .= "AND fnc_type = '$zType' ";
}

if (!empty($zStatut)) {
    $zSqlRecap .= "AND fnc_statut = '$zStatut' ";
}

if (!empty($iCause1)) {
    $zSqlRecap .= " AND SUBSTRING(fnc_cause FROM 1 FOR 1) = '{$iCause1}' ";
}

if (!empty($zTypologie)) {
    if ($zTypologie == "vide") {
        $zSqlRecap .= "AND (fnc_typologie IS NULL)";
    } else {
        $zSqlRecap .= "AND fnc_typologie = '$zTypologie' ";
    }

}
if (!empty($zImputation)) {
    if ($zImputation == "vide") {
        $zSqlRecap .= "AND (fnc_imputation IS NULL)";
    } else {
        $zSqlRecap .= "AND fnc_imputation = '$zImputation' ";
    }

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
$zSqlRecap .= "  GROUP BY nc_fiche.fnc_id, fnc_type,fnc_client, fnc_code, fnc_statut, \"fnc_creationDate\", fnc_motif,                 fnc_cause, fnc_typologie, fnc_imputation, fnc_ref, fnc_process,   fnc_classement, fnc_module, fnc_typo
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
// echo '<pre>';
// print_r($zSqlRecap);
// echo '<pre>';
// contenu de la recherche

$sqlBU   = "";
$corps   = "";
$aCauses = array();
for ($i = 0; $i < $iNbRecap; $i++) {
    $toResRecap = @pg_fetch_array($oQueryRecap, $i);

    $idFnc = $toResRecap['id_fnc'];

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
    $color          = (($i % 2 == 0) ? "#ffffff" : "#EBF2FA");
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
            select *from (
            select libelle as description,fnc_id from nc_action_liste ncactlist
            inner join nc_fnc_action nclact
            on ncactlist.id  = nclact.action_liste_id
            ) as descr
            left join ( select ncaction.fnc_id,ncinfo.* from nc_fnc_infos ncinfo
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

    $sSqlProcessClassementModule = "   SELECT   DISTINCT mtp_naturecmd, mtp_classification, mtp_unitefonction
                                 FROM  mth_priorite
                                 WHERE mtp_clientidreel = '{$sCode3}'";
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
    if ($libBu == '') {
        $libBu = "AUCUN";
    }
    $corps .= "
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

    $corps .= "
                     <td id='tdTypologie{$iId}'>
                        <span id='spanTypologie{$iId}'>{$sTypologie}</span>
                        ";

    $corps .= "
                     </td>
                     <td id='tdImputation{$iId}'>
                        <span id='spanImputation{$iId}'>{$sImputation}</span>
                        ";

    $corps .= "
                     </td>
                       <td $colr>{$criticite}</td>
                     <!--td id=\"tdProcess{$iId}\">
                        <span id=\"spanProcess{$iId}\">{$sProcess}</span>";

    $corps .= "
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
    $corps .= "         <!--td id=\"tdModule{$iId}\">
                        <span id=\"spanModule{$iId}\">{$sModule}</span>

                           ";

    $corps .= "
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
    $tx_avacmnt = (float) $tx_avacmnt;

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

    $corps .= "<td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bcommentaire}</td>
   <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" ><center>{$tx_avacmnt}%</center></td>
   <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" ><center>{$tx_restant}%</center></td>
         <td bgcolor=\"$color\" style=\"padding-top: 5px; padding-left: 5px\" >{$bgeneralisation}</td>
      ";
    $corps .= "      </tr>";
}
$pied     = "     </table>";
$resultat = $entete . $corps . $pied;
//     ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream;UTF-8");
header("Content-Disposition: attachment; filename = RecapExport.xls");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check = 0, pre-check = 0", false);
header("Content-Length: " . strlen($resultat));

print $resultat;

exit;
