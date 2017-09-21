<?php
require_once "DBConnect.php";

if (isset($_REQUEST['txtDate1'])) {
    $dDate1 = $_REQUEST['txtDate1'];
    $dDate2 = $_REQUEST['txtDate2_'];
} else {
    $dDate1 = date("Y-m-01");
    $dDate2 = date("Y-m-d");
}


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
// echo '<pre>';
// print_r($zSqlRecap);
// echo '<pre>';
// contenu de la recherche

$corps   = "";
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
    
    $libBu       = $toResRecap['lib_bu'];
    if ($libBu == NULL) {
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
                     <td>
                        {$sTypologie}
                        ";

    $corps .= "
                     </td>
                     <td>
                        {$sImputation}
                        ";

    $corps .= "
                     </td>
                       <td $colr>{$criticite}</td>";
       
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
