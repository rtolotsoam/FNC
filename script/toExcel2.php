<?php
require_once "DBConnect.php";

$dateDeb = $_REQUEST['debAct'];
$dateFin = $_REQUEST['finAct'];

$zSelectRef = "
                    SELECT
                        FNC_REF
                    FROM
                        NC_FICHE
                    ORDER BY
                        FNC_REF ASC"
;
$oQuerySelectRef = @pg_query($zSelectRef);
$iNbSelectRef    = @pg_num_rows($oQuerySelectRef);

$sFileName = "ActionsCorrectives.xls";

$sContent = "   <p>
                        <table width=\"100%\"  border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                            <tr>
                                <td colspan = \"11\" align=\"center\">
                                    <div style = \"color: #000000; text-align: center; font-weight: bold\">
                                        ACTIONS CORRECTIVES, VALIDATION DE L'ACTION A NON OK
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </p>
                    <p>
                        <table width=\"100%\"  border=\"1\" cellspacing=\"2\" cellpadding=\"3\">
                     <tr style=\"background-color: #1E5799; color: white;\">
                                <td colspan=\"9\" style=\"text-align : center;\"><strong>Fiche de non conformit&eacute;</strong></td>
                            <td></td>
                        <td></td>

                                <td colspan=\"12\" class=\"titre\" align=\"center\"><strong>Actions</strong></td>
                                <td colspan='1' style=' border-style: solid 10px; border-color: #ff0000 #0000ff;'></td>
                                <td colspan='1' style=' border-style: solid 10px; border-color: #ff0000 #0000ff;'></td>

                              </tr>
                              <tr style=\"background-color: #1E5799; color: white;\">
                                <td><strong>Type</strong></td>
                <td><strong>Nom du client</strong></td>
                <td><strong>BU</strong></td>
                <td><strong>Code</strong></td>
                                <td><strong>R&eacute;f&eacute;rence</strong></td>
                                <td><strong>Type d'appel</strong></td>
                <td style='text-align:center;'><strong>Date de cr&eacute;ation FNC</strong></td>
                <td style='text-align:center;'><strong>Cr&eacute;&eacute;e par</strong></td>
                <td ><strong>Faille identifi&eacute;e</strong></td>
                                <td ><strong>Impact</strong></td>
                                <td ><strong>Imputation</strong></td>
                                <td align=\"center\"><strong>Description</strong></td>
                                <td><strong>Responsable</strong></td>
                                <td><strong>Date de d&eacute;but d'actions</strong></td>
                                <td><strong>Date de fin</strong></td>
                                <td><strong>Date suivi</strong></td>
                                <td><strong>Indicateur d'&eacute;fficacit&eacute;</strong></td>
                                <td><strong>Objectif et &eacute;ch&eacute;ance</strong></td>
                                <td><strong>Etats r&eacute;alisation actions</strong></td>
                                <td><strong>Validation de l'action</strong></td>
                                <td><strong>Commentaire</strong></td>
                <td rowspan=\"1\"><strong>G&eacute;n&eacute;ralisation</strong></td>

                                <td rowspan=\"1\"><strong><center>Taux  d'avancement action</strong></center></td>
                                <td rowspan=\"1\"><center><strong>Taux  restant</strong></center></td>
                                <td rowspan=\"1\"><strong>Criticit&eacute;</strong></td>

                              </tr>";

if (isset($_REQUEST['debAct']) && isset($_REQUEST['finAct'])) {

    $dateDeb_act = $_REQUEST['debAct'];
    $dateFin_act = $_REQUEST['finAct'];

    if($dateDeb_act != $dateFin_act){
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
                            ,   NF.FNC_ID               AS  FNCID
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
                            AND NAL.TYPE            !=      'curative'
                            AND DATE_DEBUT          >=      '$dateDeb_act'  
                            AND DATE_DEBUT          <=      '$dateFin_act'
                            GROUP BY
                                NFA.ACTION_LISTE_ID
                            ,   NFI.DATE_DEBUT
                            ,   NFI.DATE_FIN
                            ,   NFI.ETAT
                            ,   NFI.FAILLE_IDENTIFIEE
                            ,   NFI.IMPACT
                            ,   NFI.GENERALISATION
                            ,   LIBELLE
                            ,   NF.FNC_ID
                            ,   NFA.FNC_INFO_ID         
                            ORDER BY
                                DATE_DEBUT ASC  "
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
                            ,   NF.FNC_ID               AS  FNCID
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
                            AND NAL.TYPE            !=      'curative'
                            AND DATE_DEBUT          =       '$dateDeb_act'  
                            GROUP BY
                                NFA.ACTION_LISTE_ID
                            ,   NFI.DATE_DEBUT
                            ,   NFI.DATE_FIN
                            ,   NFI.ETAT
                            ,   NFI.FAILLE_IDENTIFIEE
                            ,   NFI.IMPACT
                            ,   NFI.GENERALISATION
                            ,   LIBELLE
                            ,   NF.FNC_ID
                            ,   NFA.FNC_INFO_ID         
                            ORDER BY
                                DATE_DEBUT ASC  "
        ;

    }

} else {

    if($dateDeb_act != $dateFin_act){
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
                            ,   NF.FNC_ID               AS  FNCID
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
                            AND NAL.TYPE            !=      'curative'
                            AND DATE_DEBUT          >=      '$dateDeb_act'  
                            AND DATE_DEBUT          <=      '$dateFin_act'
                            GROUP BY
                                NFA.ACTION_LISTE_ID
                            ,   NFI.DATE_DEBUT
                            ,   NFI.DATE_FIN
                            ,   NFI.ETAT
                            ,   NFI.FAILLE_IDENTIFIEE
                            ,   NFI.IMPACT
                            ,   NFI.GENERALISATION
                            ,   LIBELLE
                            ,   NF.FNC_ID
                            ,   NFA.FNC_INFO_ID         
                            ORDER BY
                                DATE_DEBUT ASC  "
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
                            ,   NF.FNC_ID               AS  FNCID
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
                            AND NAL.TYPE            !=      'curative'
                            AND DATE_DEBUT          =       '$dateDeb_act'  
                            GROUP BY
                                NFA.ACTION_LISTE_ID
                            ,   NFI.DATE_DEBUT
                            ,   NFI.DATE_FIN
                            ,   NFI.ETAT
                            ,   NFI.FAILLE_IDENTIFIEE
                            ,   NFI.IMPACT
                            ,   NFI.GENERALISATION
                            ,   LIBELLE
                            ,   NF.FNC_ID
                            ,   NFA.FNC_INFO_ID         
                            ORDER BY
                                DATE_DEBUT ASC  "
        ;

    }

}

    /*echo '<pre>';
    print_r($zSqlId);
    echo '</pre>'; */



$rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn));

//$clWere          = '';
//$cpt             = 0;
//$arrayStockFncId = array();

//array_push($arrayStockFncId, 0);

for ($i = 0; $i < @pg_num_rows($rQueryId); $i++) {

    $reference   = '';
    $type        = '';
    $resSelectId = @pg_fetch_array($rQueryId, $i);
    $id_         = $resSelectId['id'];
    $dated_      = $resSelectId['dated_'];
    $datef_      = $resSelectId['datef_'];
    $etat_       = $resSelectId['etat_'];
    $idFNC       = $resSelectId['fncid'];
    $faille_     = pg_escape_string($resSelectId['faille_']);
    $imp_        = str_replace("'", "''", $resSelectId['imp_']);
    $gen_        = $resSelectId['gen_'];
    $b_fncid     = $resSelectId['fnc_id'];
    // print_r($resSelectId);
    $libelle     = $resSelectId['description'];
    $inf_id      = $resSelectId['inf_id'];
    //$bu          =  $resSelectId['lib_bu'] ;

    $sqlAppeType = "
                                    SELECT
                                        LIBELLE_TYPOLOGIE
                                    FROM
                                        CC_SR_TYPOLOGIE CC_TYPO
                                    INNER JOIN
                                        NC_FICHE    NCF
                                    ON
                                        NCF.ID_CC_SR_TYPO   =   CC_TYPO.ID_TYPOLOGIE
                                    WHERE
                                        FNC_ID  =   " . $idFNC
    ;

    $resAppeType = pg_query($conn, $sqlAppeType) or die(pg_last_error($conn));
    $arAppeType  = pg_fetch_array($resAppeType);
    $gAppeType   = $arAppeType['libelle_typologie'];
    $typeApp     = 'Aucun';

    if ($gAppeType == '') {
        $typeApp = 'Aucun';
    } else {
        $typeApp = utf8_decode($gAppeType);
    }

    /*$zSqlInfo = "
                            SELECT
                                *
                            FROM
                            ("
    ;*/

    $zSqlInfo = "
                            SELECT DISTINCT
                                NC_FICHE.FNC_CODE
                            ,   CASE
                                        WHEN
                                            NC_FICHE.FNC_CODE   =   'QUAL'
                                        OR  NC_FICHE.FNC_CODE   =   '0VVT001'
                                        THEN
                                            B1.LIB_BU
                                        ELSE
                                            B.LIB_BU
                                END LIB_BU
                            ,   NC_FICHE.FNC_CREATEUR
                            ,   NC_FICHE.\"fnc_creationDate\"   AS  DATE_CREAT
                            ,   NC_FICHE.FNC_TYPE
                            ,   NC_FICHE.FNC_CLIENT
                            ,   OBJ_ECHEANCE
                            ,   INDIC_EFFICACITE
                            ,   DATE_DEBUT                      AS  DATEDEB
                            ,   DATE_FIN                        AS  DATEFIN
                            ,   RESPONSABLE
                            ,   ETAT
                            ,   NC_FICHE.FNC_ID
                            ,   FNC_REF
                            ,   FAILLE_IDENTIFIEE
                            ,   IMPACT
                            ,   FNC_IMPUTATION
                            ,   GENERALISATION
                            ,   DATE_SUIVI
                            ,   COMMENTAIRE
                            ,   NC_FNC_INFOS.ID                 AS  IDINFO
                            ,   LIBELLE                         AS  DESCRIPTION
                            ,   INDICE
                            ,   NC_FNC_INFOS.VALID_ACTION   "
    ;

    $zSqlInfo .= " ,
                                FNC_GRAVITE_ID
                            ,   FNC_FREQUENCE_ID
                            ,   FNC_FREQ_CAT_ID
                            ,   FNC_GRAV_CAT_ID
                            ,   NC_FICHE.FNC_ID     AS  FNC_ID_L
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
                            AND    NC_FNC_INFOS.ID              = $inf_id  
                            AND NC_FICHE.FNC_ID                 =   CAST(NC_FNC_ACTION.FNC_ID   AS  integer)
                            AND NC_ACTION_LISTE.ID              =   NC_FNC_ACTION.ACTION_LISTE_ID
                            AND NC_FNC_ACTION.ACTION_LISTE_ID   =   $id_
                            AND NC_FNC_INFOS.ETAT               =   '$etat_'
                            AND NC_FNC_INFOS.DATE_DEBUT         =   '$dated_'
                            AND NC_FNC_INFOS.DATE_FIN           =   '$datef_'
                            AND (
                                    NC_FNC_INFOS.VALID_ACTION   !=  1
                                OR  NC_FNC_INFOS.ETAT           =   'en cours'
                                )
                            AND NC_ACTION_LISTE.TYPE            !=  'curative'
                            AND NC_FNC_INFOS.IMPACT             =   '$imp_'
                            AND NC_FNC_INFOS.FAILLE_IDENTIFIEE                   =   '$faille_' 
                            "
    ;

    /*echo '<pre>';
    print_r($zSqlInfo);
    echo '</pre>';*/ 

    $rQueryInfo   = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn));

    $nb_enreg = 0;

    $nb_enreg = @pg_num_rows($rQueryInfo);

    /*echo "<br/>";
    echo $nb_enreg;*/

    if($nb_enreg == 1){

    for ($iTmp = 0; $iTmp < $nb_enreg; $iTmp++) {

        $criticite = "";

        /* if(in_array($idFNC,$arrayStockFncId))
        {

        }
        else
        {*/
        //array_push($arrayStockFncId, $idFNC);

        $toRes = @pg_fetch_array($rQueryInfo, $iTmp);

        /****************** Modif Fulgence 20150210  ******************/
        // echo $toRes['fnc_grav_cat_id'];exit();
        // Affichage criticit&eacute;
        //


        if (trim($toRes['fnc_grav_cat_id']) != '' && trim($toRes['fnc_freq_cat_id']) != '') {


            //if($toRes['fnc_grav_cat_id'] != '')
            $cat_id_grav = $toRes['fnc_grav_cat_id'];
            /*else
            $cat_id_grav                   = 1 ;

            if($toRes['fnc_freq_cat_id']   != '')*/
            $cat_id_freq = $toRes['fnc_freq_cat_id'];
            /*else
            $cat_id_freq                   = 1 ;*/
            // exit('1');
            // gravit&eacute;
            $sqlSltGrv = "
                                                    SELECT
                                                        ID_CATEGORIE_GRAV
                                                    ,   ECHELLE_ID_GRAV
                                                    FROM
                                                        NC_GRAVITE_CATEGORIE
                                                    WHERE
                                                        id_categorie_grav   =   $cat_id_grav    "
            ;
            //echo $sqlSltGrv ;
            $resGrv  = pg_query($conn, $sqlSltGrv) or die(pg_last_error($conn));

            $arGrv   = pg_fetch_array($resGrv);


            $grv_ech = $arGrv['echelle_id_grav'];


            /*echo "grav = ".$grv_ech;
            echo "<br/>";*/

            //Fr&eacute;quence
            $sqlSltFrq = "
                                                    SELECT
                                                        ID_CATEGORIE_FREQ
                                                    ,   ECHELLE_ID_FREQ
                                                    FROM
                                                        NC_FREQUENCE_CATEGORIE
                                                    WHERE
                                                        id_categorie_freq   =   $cat_id_freq    "
            ;
            $resFrq  = pg_query($conn, $sqlSltFrq) or die(pg_last_error($conn));

            $arrFrq  = pg_fetch_array($resFrq);

            $frq_ech = $arrFrq['echelle_id_freq'];

            /*echo "freq = ".$frq_ech;
            echo "<br/>";*/

            if ($grv_ech == 1) {
                $criticite = "m";
            } elseif ($grv_ech == 2 && $frq_ech <= 2) {
                $criticite = "m";
            } elseif ($grv_ech == 2 && $frq_ech >= 3) {
                $criticite = "M";
            } elseif ($grv_ech == 3 && $frq_ech < 4) {
                $criticite = "M";
            } elseif ($grv_ech == 3 && $frq_ech == 4) {
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

        //echo $criticite;
        //echo "<br/>";

        /****************** Fin modif ******************/

        // exit('=====');
        $bgColor = ($i % 2 == 1) ? "#ffffff" : "#EBF2FA";

        $obj     = $toRes['obj_echeance'];
        $eff     = $toRes['indic_efficacite'];
        $libelle = $toRes['description'];

        /*if(empty($toRes['datefin'])) $color = "#000000";
        else{
        if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
        elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
        else $color = "#008000";
        }*/

        $type        = $toRes['fnc_type'];
        $reference   = $toRes['fnc_ref'];

        /*echo "<br/>";
        echo $criticite;
        echo $reference;
        echo "<br/>";*/

        $tx_avacment = $toRes['tx_avacmnt'];
        $tx_restant  = 0;
        $tx_restant  = 100 - $tx_avacment;

        $bu = trim($toRes['lib_bu']);

        if ($bu == '') {
            $bu = 'AUCUN';
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

        $oImputation   = @pg_fetch_array($resImputation, 0);

        /**
         *
         */

        // Ajouter par Mle 9092, pour prendre prenompersonnel

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

        $sContent .= "  <tr>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$type}</a></td>
                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['fnc_client']}</a></td>
                <td bgcolor = '$bgColor'  style = 'color: $color' >{$bu}</a></td>
                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['fnc_code']}</a></td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$reference}</a></td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$typeApp}</a></td>
                <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['date_creat']}</a></td>
                <td bgcolor = '$bgColor'  style = 'color: $color; text-align : center;' >{$toRes['fnc_createur']} - {$oCreateur['prenompersonnel']}</a></td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['faille_identifiee']}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['impact']}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$oImputation['imputation_libelle']}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$libelle}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['responsable']}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datedeb']}</td>
                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['datefin']}</td>

                                <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['date_suivi']}</td>
                         <td bgcolor = '$bgColor'  style = 'color: $color' >{$eff}</td>
                         <td bgcolor = '$bgColor'  style = 'color: $color' >{$obj}</td>";

        if ($toRes['etat'] == 'en attente' || $toRes['etat'] == '') {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >non entam&eacute;</td>";
        } elseif ($toRes['etat'] == 'en cours') {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >en cours</td>";
        } else {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >action faite</td>";
        }

        if ($toRes['valid_action'] == '0' || $toRes['valid_action'] == 0) {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >non d&eacute;finie</td>";
        } elseif ($toRes['valid_action'] == '1' || $toRes['valid_action'] == 1) {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >OK</td>";
        } else {
            $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >KO</td>";
        }

        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['commentaire']}</td>";

        $sContent .= "          <td bgcolor = '$bgColor'  style = 'color: $color' >{$toRes['generalisation']}</td>";

        $sContent .= "<td bgcolor = '$bgColor'  style = 'color: $color; text-align:center'>" . (float) $tx_avacment . "%</td>";
        $sContent .= "      <td bgcolor = '$bgColor'  style = 'color: $color ; text-align:center'>
                                    " . (float) $tx_restant . "%
                                    </td>";

        $sContent .= "                      <td $colr>{$criticite}</td>
                            </tr>";
        
        
        } // if nb_enreg
    } // for 2
} // for 1

$sContent .= "</table></p> ";

//exit; 

ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream;UTF-8");
header("Content-Disposition: attachment; filename = $sFileName");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check                 = 0, pre-check = 0", false);
header("Content-Length: " . strlen($sContent));

print $sContent;

exit;
