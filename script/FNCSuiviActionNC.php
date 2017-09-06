<?php
session_start();

// echo $_SERVER['REMOTE_ADDR'];
/*if($_SERVER['REMOTE_ADDR'] != '192.168.10.131')
{
exit();
} */

// matricule personne connecté
$matricule_login = $_SESSION['matricule'];

/*****************************************************************/
//                                                               //
//  fichier de cofiguration admin FNC, ajouter par mle 9092     //
/****************************************************************/

include "FNCUser_admin.php";

require_once "DBConnect.php";
$zSelectRef      = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC";
$oQuerySelectRef = @pg_query($zSelectRef);
$iNbSelectRef    = @pg_num_rows($oQuerySelectRef);
$matr_autorise   = $_SESSION['matricule'];
echo "<input type='hidden' id='edit_autoris' value='" . $matr_autorise . "' />";
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Suivi des actions</title>

      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

      <link rel="stylesheet" type="text/css" href="../css/FNCAjout.css?1" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css?1" />
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css?1" />
      <style>
            div.ui-datepicker{
            font-size: 12px;
            position : relative;
            z-index: 9999 !important;
            }


            .titre{
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            font-weight: bold;
            }
            .contenu{
            border-right: 1px solid black;
            }
            table{
            font-family: verdana;
            font-size: 11px;
            border: 1px solid black;
            }
            .wrapper {
            position: relative;
            height: 500px;
            overflow-y: auto;
            border:4px solid #1e5799;width:100%;
            direction:ltr;
            }
    </style>

  </head>

  <body>

  <?php
$dateDeb_act = date('Y-m');
$dateFin_act = date('Y-m-d');
?>


  <form id="frmActionCorrective" method="post" action="?">
    <span id='idContentCache' style='display:none;'>
    <?php /*    commenter par demande, fait par mle 9092       ?>
<p style="font-family: verdana; font-size: 12px; color: #666666">
Cette page permet de visualiser les actions correctives dont la validation de l'action n'est pas "OK".
<ul style="font-family: verdana; font-size: 12px">
<li>
<font color="#FF0066">En rouge :</font> les actions correctives dont la date de fin est inf&eacute;rieure &agrave; la date du jour.
</li>
<li>
<font color="#FF9933">En orange :</font> les actions correctives dont la date de fin est &eacute;gale &agrave; la date du jour.
</li>
<li>
<font color="#008000">En vert :</font> les actions correctives dont la date de fin est sup&eacute;rieure &agrave; la date du jour.
</li>
<li>
<font color="#000000">En noir :</font> les actions correctives dont aucune date de fin n'est d&eacute;finie.
</li>
</ul>
</p>
<?php */?>
    <br />

    <!-- Ajouter par le mle 9092, pour afficher les FNC sauf le validation OK -->

  <div>

    <p align="right" style="float : right;">
      <label for="dateDeb_act">Date de d&eacute;but d'actions : <input type="text" value="<?php if (isset($_REQUEST['debAct'])) {echo $_REQUEST['debAct'];} else {echo $dateDeb_act . '-01';}?>" id="dateDeb_act" name="dateDeb_act" /></label>
      <label for="dateFin_act"> jusq' &agrave; <input type="text" id="dateFin_act" value="<?php if (isset($_REQUEST['finAct'])) {echo $_REQUEST['finAct'];} else {echo $dateFin_act;}?>" name="dateFin_act" /></label>
      <label> &nbsp; <input type="button" value="Afficher" onclick="searchDate();" style="cursor: pointer;"/></label>
      &nbsp;
    </p>

    <p align="left">
      &nbsp;
      <?php
if (isset($_REQUEST['debAct']) && isset($_REQUEST['finAct'])) {
    ?>
      <a href="toExcel2.php?debAct=<?php echo $_REQUEST['debAct']; ?>&finAct=<?php echo $_REQUEST['finAct']; ?>" style="text-decoration: none">
      <?php
} else {
    ?>
      <a href="toExcel2.php?debAct=<?php echo $dateDeb_act; ?>&finAct=<?php echo $dateFin_act; ?>" style="text-decoration: none">
      <?php
}
?>
        <input type="button" id="btnExportToExcel" name="btnExportToExcel" class="ui-state-default" value="Export Excel" style="cursor: pointer" />
      </a>
      <input type="button" id="btnAffiche" name="btnAffiche" class="ui-state-default" style="cursor: pointer; width:250px;" value="Afficher toutes les actions" onclick = "afficheActionCorrective ();" />

    </p>
  </div>
</span>

    <center>
        <span id='info_warning' style='display:none;'>
          <br/>
          <b>D&eacute;sol&eacute;, veuillez r&eacute;essayer plus tard, car la base de donn&eacute;es est indisponible pour l'instant!</b>
          <img src='../images/error.png' height='12' width='12'>
          <br/>
        </span>
        <span id='info_warning_comment' style='display:none;'>
          <br/>
          <b>Veuillez remplir le champ commentaire !</b>
          <img src='../images/error.png' height='12' width='12'>
          <br/>
        </span>
        <span id='info_warning_date' style='display:none;'>
          <br/>
          <b>Veuillez ins&eacute;rer des dates valides : Date de d&eacute;but d'actions inf&eacute;rieur jusqu' &agrave; !</b>
          <img src='../images/error.png' height='12' width='12'>
          <br/>
        </span>
        <span id='info_modif' style='display:none;'>
          <br/>
          <b>Modification <span id="msg_modif"> </span> &eacute;ffectu&eacute;e</b>
          <img src='../images/success.png' height='12' width='12'>
          <br/>
        </span>
    </center>
<br/>
<center >
    <span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /></br><b>Chargement ... </b></span>
</center>

</br>
    <div class='wrapper' id='idWrapperCache' style='display:none;' >
      <table id="table1" style='display:none;'>
         <tr><thead>
                <th class="titre" colspan="8" style="text-align: center;">Fiche de non conformit&eacute; </th>
                <th class="titre" rowspan="2">Faille identifi&eacute;e</th>
                <th class="titre" rowspan="2">Impact</th>
                <th class="titre" rowspan="2">Imputation</th>
                <th colspan="10" class="titre" align="center">Actions</th>
                <th class="titre" rowspan="2">G&eacute;n&eacute;ralisation</th>
    <th class="titre" rowspan="2">Taux d'avancement action</th>
                <th class="titre" rowspan="2">Taux restant </th>
                <th class="titre" rowspan="2">Criticit&eacute;</th>
              </tr>
              <tr>
                <!--th class="titre">Nom du client</th-->
                <th class="titre">Type</th>

                <th class="titre">Nom du client</th>
                <th class="titre">BU</th>
                <th class="titre">Code</th>
                <th class="titre">R&eacute;f&eacute;rence</th>
                <th class="titre">Type d'appel</th>
                <th class="titre" style='text-align:center;'>Date de cr&eacute;ation FNC</th>
                <th class="titre" style='text-align:center;'>Cr&eacute;&eacute;e par</th>
                <th class="titre">Description</th>
                <th class="titre">Responsable</th>
                <th class="titre" style='text-align:center;'>Date de d&eacute;but d'actions</th>
                <th class="titre" style='text-align:center;'>Date fin </th>
                <th class="titre" style='text-align:center;'>Date suivi</th>
                <th class="titre" style='text-align:center;'>Indicateur d&rsquo;&eacute;fficacit&eacute;</th>
                <th class="titre" style='text-align:center;'>Objectif et &eacute;ch&eacute;ance</th>

                <th class="titre">

                  <?php

echo "Etats r&eacute;alisation actions";

?>

               </th>
                <th class="titre">Validation de l'action</th>
                <th class="titre">Commentaire</th>


              </thead></tr><tbody>

<?php
$sAdminMsg = "";
$iCounter  = 0;
$nombre    = $_REQUEST["nombre"];

// pour avoir les FNC à la date indiquer dans : Date de debut actions :

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
                            AND DATE_DEBUT          <=        '$dateFin_act'
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
                            AND DATE_DEBUT          <=        '$dateFin_act'
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
                            ,   NFA.FNC_INFO_ID   
                            ORDER BY
                                DATE_DEBUT ASC  "
        ;

    }

}

$rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn));

$clWere          = '';
$cpt             = 0;
$coul            = 0;
$arrayStockFncId = array();
array_push($arrayStockFncId, 0);
$bufferI = 0;

for ($i = 0; $i < @pg_num_rows($rQueryId); $i++) {

    $bufferI += $i;
    $reference   = '';
    $type        = '';
    $resSelectId = @pg_fetch_array($rQueryId, $i);
    $id_         = $resSelectId['id'];
    $dated_      = $resSelectId['dated_'];
    $datef_      = $resSelectId['datef_'];
    $etat_       = $resSelectId['etat_'];
    $faille_     = str_replace("'", "''", $resSelectId['faille_']);
    $imp_        = str_replace("'", "''", $resSelectId['imp_']);
    $gen_        = $resSelectId['gen_'];
    $inf_id       = $resSelectId['inf_id'];

    $zSqlInfo = "
                                    SELECT
                                        *
                                    FROM
                                    ("
    ;
    $zSqlInfo .= "
                                    SELECT DISTINCT
                                        NC_FICHE.FNC_CODE
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
                                    ,   NC_FICHE.FNC_ID                 AS  FNCID
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

    $zSqlInfo .= "
                                    ,   FNC_GRAVITE_ID
                                    ,   FNC_FREQUENCE_ID
                                    ,   FNC_FREQ_CAT_ID
                                    ,   FNC_GRAV_CAT_ID
                                    ,   NC_FICHE.FNC_ID     AS  FNC_ID_L
                                    ,   TX_AVACMNT  "
    ;

    $zSqlInfo .= "
                                    FROM
                                        NC_FNC_INFOS
                                    ,   NC_FNC_ACTION
                                    ,   NC_ACTION_LISTE
                                    ,   NC_FICHE
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
                                     "
    ;

    /* if (isset($_REQUEST['slctActionValidationFiltre'])) {
    if (!empty($_REQUEST['slctActionValidationFiltre'])){
    if($_REQUEST['slctActionValidationFiltre'] == 'en attente'){

    $clWhere = " AND (nc_fnc_infos.etat = 'en attente' OR nc_fnc_infos.etat = '')";
    }else{
    $etat_inf = $_REQUEST['slctActionValidationFiltre'];
    $clWhere = " AND nc_fnc_infos.etat = '{$etat_inf}' ";
    }

    }
    }*/

    /*$zSqlInfo .= "
    )AS TEMP
    LEFT JOIN
    (
    SELECT
    DISTINCT
    LIB_BU
    ,   FNC_ID
    FROM
    NC_FICHE    F
    INNER JOIN
    GU_APPLICATION  A
    ON
    SUBSTRING(F.FNC_CODE FROM 1 FOR 3)  =   A.CODE
    INNER JOIN
    BUSINESS_UNIT   B
    ON
    B.ID_BU =   A.ID_BU
    UNION
    SELECT
    LIB_BU
    ,   FNC_ID
    FROM    NC_FICHE    NCF
    INNER JOIN
    BUSINESS_UNIT   BU
    ON
    NCF.FNC_BU  =   BU.ID_BU
    )   AS  TEMP2
    ON
    TEMP2.FNC_ID    =   TEMP.FNC_ID_L"
    ;*/

    /*==========================================================>*/

    $zSqlInfo .= "
                           )AS  TEMP
                                LEFT JOIN
                                    (
                                        SELECT
                                            DISTINCT
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
                                            B.ID_BU =   A.ID_BU
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
                                    TEMP2.FNC_ID    =   TEMP.FNC_ID_L"
    ;

    /*echo '</br>';
    echo '<pre>';
    print_r($zSqlInfo);
    echo '</pre>';*/

    $rQueryInfo = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn));

    $nombre_enreg = pg_num_rows($rQueryInfo);

    //echo '***'.$nombre_enreg.'****';

    for ($iTmp = 0; $iTmp < $nombre_enreg; $iTmp++) {

        $toRes = @pg_fetch_array($rQueryInfo, $iTmp);
        //echo $toRes['fnc_id'] ;
        $obj   = $toRes['obj_echeance'];
        $eff   = $toRes['indic_efficacite'];
        $idFNC = $toRes['fncid'];
        $type  = $toRes['fnc_type'];
        /*if(in_array($idFNC,$arrayStockFncId))
        {

        }
        else
        {*/

        array_push($arrayStockFncId, $idFNC);
        $bufferI += 1;
        /****************** Modif Fulgence 20150210  ******************/
        // Affichage criticit&eacute;
        if ($toRes['fnc_grav_cat_id'] != '' && $toRes['fnc_freq_cat_id'] != '') {
            //if($toRes['fnc_grav_cat_id'] != '')
            $cat_id_grav = $toRes['fnc_grav_cat_id'];
            /*else
            $cat_id_grav                   = 1 ;

            if($toRes['fnc_freq_cat_id']   != '')*/
            $cat_id_freq = $toRes['fnc_freq_cat_id'];
            /*else
            $cat_id_freq                   = 1 ;*/

            // gravit&eacute;
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

            //Fr&eacute;quence
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
            $colr      = ($i % 2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'");
            $criticite = "";

        }
        /****************** Fin modif ******************/

        // commenter sur demande, fait par mle 9092

        /*$coul =($i%2==1?"#ffffff":"#ebebeb");

        if(empty($toRes['datefin'])) $color = "#000000";
        else{
        if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
        elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
        else $color = "#008000";
        }*/

        $aDescrition = explode("*#|#*", $toRes['description']);

        $types      = explode("-", $type);
        $types      = explode("-", $type);
        $references = explode(",", $reference);

        //echo $toRes['fnc_ref'] ;
        $bu = trim($toRes['lib_bu']);

        if ($bu == '') {
            $bu = "AUCUN";
        }

        $clt          = $toRes['fnc_client'];
        $TmpReference = $toRes['fnc_ref'];

        echo "  <tr>
                        <td class ='contenu' valign='top' style='cursor:pointer;' onclick='viewFNC(" . $idFNC . ");' >&nbsp;" . $type . "</td>
                        <td class ='contenu' valign='top'>&nbsp;" . $clt . "</td>
                        <td class ='contenu' valign='top'>&nbsp;" . $bu . "</td>
                        <td class ='contenu' valign='top'>&nbsp;" . $toRes['fnc_code'] . "</td>
                        <td class ='contenu' valign='top' style=\" witdh : 20px; height:10px;\">&nbsp;<a style=\"text-decoration: none; color: black; display: block; width:100%; height:100%;\" href=\"FNCConsulter.php?varTest=0&corrective=1&txtRef=" . $toRes['fnc_ref'] . "&txtId=" . $toRes['fnc_id'] . " \" >
                        " . $TmpReference . "
                        ";

        $b_fncid = $toRes['fnc_id'];

        if (isset($idFNC)) {
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

        echo "</a></td>

                            <td class='contenu' valign='top' style='text-align: center;'>&nbsp;" . utf8_decode($typeApp) . "</td>
                            <td class='contenu' valign='top' style='text-align: center;'>&nbsp;" . $toRes['date_creat'] . "</td>
                            <td class='contenu' valign='top' style='text-align: center;'>&nbsp;" . $toRes['fnc_createur'] . " - " . $oCreateur['prenompersonnel'] . " &nbsp;</td>";
        ?>
                            <td class='contenu' valign='top'>
                            <?php
if (in_array($matricule_login, $array_admin_CQ)) {
            ?>

                                <textarea   onblur = "maj_faille(<?php echo $toRes['idinfo'].",".$idFNC; ?>);" id="txtfaille<?php echo $toRes['idinfo']; ?>" name="txtfaille<?php echo $toRes['idinfo']; ?>" style="width: 150px; height: 80px" ><?php echo $toRes['faille_identifiee']; ?></textarea>
                                <input type="hidden" id="tmp_faille_<?php echo $toRes['idinfo']; ?>" value="<?php echo $toRes['faille_identifiee']; ?>"/>

                            <?php
} else {

            echo $toRes['faille_identifiee'];
        }
        ?>
                            </td>
                            <td class='contenu' valign='top'>
                            <?php
if (in_array($matricule_login, $array_admin_CQ)) {
            ?>
                                <textarea   onblur = "maj_impact(<?php echo $toRes['idinfo'].",".$idFNC; ?>);" id="txtimpact<?php echo $toRes['idinfo']; ?>" name="txtimpact<?php echo $toRes['idinfo']; ?>" style="width: 150px; height: 80px" ><?php echo $toRes['impact']; ?></textarea>
                                <input type="hidden" id="tmp_impact_<?php echo $toRes['idinfo']; ?>" value="<?php echo $toRes['impact']; ?>" />
                            <?php
} else {
            echo $toRes['impact'];
        }
        ?>
                            </td>
    <?php
echo "<td class='contenu' valign='top'>&nbsp; " . $oImputation['imputation_libelle'] . "</td>";
        ?>
                            <td class='contenu' valign='top' >
                            <?php
if (in_array($matricule_login, $array_admin_CQ)) {
            ?>
                            <textarea   onblur = "maj_description(<?php echo $idFNC.",".$bufferI.",".$toRes['idinfo']; ?>);" id="txtdesc<?php echo $idFNC; ?>" name="txtdesc<?php echo $idFNC; ?>" style="width: 150px; height: 150px" ><?php echo $aDescrition[0]; ?></textarea>
                            <input type="hidden" id="tmp_desc_<?php echo $idFNC; ?>" value="<?php echo $aDescrition[0]; ?>" />

                            <?php
} else {
            echo $aDescrition[0];
        }
        ?>

                            </td>
<?php
echo "<td class='contenu' valign='top'>&nbsp;" . $toRes['responsable'] . "</td>
                            <td class='contenu' valign='top'>&nbsp;" . $toRes['datedeb'] . "</td>
                            <!--td class='contenu' valign='top'>&nbsp;" . $toRes['datefin'] . "</td-->";

        //$b_comment = utf8_decode($toRes['commentaire']);
        $b_infoid  = $toRes['idinfo'];
       // $b_val     = $b_infoid . "||" . $b_comment;
        echo "<input type ='hidden' value = '" . $b_infoid . "' id = 'bvalId_" . $bufferI . "'>";
        // $up_comment    = utf8_decode($toRes['commentaire']);
        // $up_comment = $i;

        ?>
              <td class='contenu' valign="top">

                <?php

        /******************************************************/
        //
        // ajouter par mle 9092, pour limiter action Date fin
        //
        /*******************************************************/

        if (in_array($matricule_login, $array_admin_CQ)) {

            ?>
                        <input class="kilasy" type="text" onchange= "upFin(<?php echo $bufferI.",".$idFNC; ?>);" id="txtDateFin<?php echo $bufferI ?>" name="txtDateFin<?php echo $bufferI ?>" style="width: 100px; height: 19px; cursor: text; background: white; border: 1px solid gray" value="<?php echo $toRes['datefin']; ?>" readonly />
                        <input  type ="hidden" id="tmp_datef_<?php echo $bufferI ?>"  value="<?php echo $toRes['datefin']; ?>" />

                <?php
} else {

            echo $toRes['datefin'];

        }
        ?>
            </td>

            <td class='contenu' valign="top">

                <?php

        /******************************************************/
        //
        // ajouter par mle 9092, pour limiter action Date fin
        //
        /*******************************************************/

        if (in_array($matricule_login, $array_admin_CQ)) {

            ?>

              <input class="kilasy " onchange = "upSuivi(<?php echo $bufferI.",".$idFNC; ?>);"  type="text" id="txtDateSuivi<?php echo $bufferI ?>" name="txtDateSuivi<?php echo $bufferI ?>" style="width: 100px; height: 19px; cursor: text; background: white; border: 1px solid gray" value="<?php echo $toRes['date_suivi']; ?>" readonly />
                        <input type='hidden' value="<?php echo $toRes['date_suivi']; ?>" id="hide_suivi<?php echo $bufferI; ?>" />

                <?php
} else {

            echo $toRes['date_suivi'];

        }
        ?>

                        </td>
                        <td class='contenu' valign="top">
                        <?php
if (in_array($matricule_login, $array_admin_CQ)) {
            ?>

                            <textarea   onblur = "maj_efficacite(<?php echo $toRes['idinfo'].",".$bufferI.",".$idFNC; ?>);" class ="<?php echo $bufferI; ?>" id="txteffic<?php echo $toRes['idinfo']; ?>" name="txteffic<?php echo $toRes['idinfo']; ?>" style="width: 150px; height: 80px" ><?php echo $eff; ?></textarea>
                            <input type="hidden" id="tmp_effic_<?php echo $toRes['idinfo']; ?>" value="<?php echo $eff; ?>" />

                        <?php
} else {
            echo $eff;
        }
        ?>

                        </td>
                        <td class='contenu' valign="top">
                        <?php
if (in_array($matricule_login, $array_admin_CQ)) {
            ?>
                            <textarea   onblur = "maj_obj(<?php echo $toRes['idinfo'].",".$bufferI.",".$idFNC; ?>);" class ="<?php echo $bufferI; ?>" id="txtobj<?php echo $toRes['idinfo']; ?>" name="txtobj<?php echo $toRes['idinfo']; ?>" style="width: 150px; height: 80px" ><?php echo $obj; ?></textarea>
                            <input type="hidden" id="tmp_obj_<?php echo $toRes['idinfo']; ?>" value="<?php echo $obj; ?>" />

                        <?php
} else {
            echo $obj;
        }
        ?>

                        </td>

                        <td class='contenu' valign="top">

                <?php

        /******************************************************/
        //
        // ajouter par mle 9092, pour limiter action Date fin
        //
        /*******************************************************/

        if (in_array($matricule_login, $array_admin_CQ)) {

            ?>
                        <select id="slctActionValidation<?php echo $bufferI; ?>" name="slctActionValidation<?php echo $bufferI; ?>" style="width: 100px; height: 17px" onchange="changeEtat(<?php echo $bufferI; ?>,<?php echo $idFNC; ?>)">
                        <option value="en attente" <?php if ($toRes['etat'] == "en attente" || $toRes['etat'] == "") {
                echo "selected";
            }
            ?>>non entam&eacute;</option>
                        <option value="en cours" <?php if ($toRes['etat'] == "en cours") {
                echo "selected";
            }
            ?>>en cours</option>
                        <option value="ok" <?php if ($toRes['etat'] == "ok") {
                echo "selected";
            }
            ?>>action faite</option>
                        </select>

                        <input type="hidden" id="tmpEtat_<?php echo $bufferI; ?>" value="<?php echo $toRes['etat']; ?>"/>

                <?php
} else {
            ?>

                      <select style="width: 100px; height: 17px" disabled>
                      <option value="en attente" <?php if ($toRes['etat'] == "en attente" || $toRes['etat'] == "") {
                echo "selected";
            }
            ?>>non entam&eacute;</option>
                      <option value="en cours" <?php if ($toRes['etat'] == "en cours") {
                echo "selected";
            }
            ?>>en cours</option>
                      <option value="ok" <?php if ($toRes['etat'] == "ok") {
                echo "selected";
            }
            ?>>action faite</option>
                      </select>

                <?php
}
        ?>

            </td>

                        <td class='contenu' valign="top">

                <?php

        /******************************************************/
        //
        // ajouter par mle 9092, pour limiter action Date fin
        //
        /*******************************************************/

        if (in_array($matricule_login, $array_admin_CQ)) {
            ?>

                      <select id="slt_valid_<?php echo $toRes['idinfo']; ?>" name="slt_valid_<?php echo $toRes['idinfo']; ?>" style="width: 100px; height: 17px" onchange="changeValid_action(<?php echo $toRes['idinfo'].",".$bufferI.",".$idFNC; ?>);">
                      <option value="0" <?php if ($toRes['valid_action'] == "0") {
                echo "selected";
            }
            ?>>non d&eacute;finie</option>
                      <option value="1" <?php if ($toRes['valid_action'] == "1") {
                echo "selected";
            }
            ?>>OK</option>
                      <option value="2" <?php if ($toRes['valid_action'] == "2") {
                echo "selected";
            }
            ?>>KO</option>
                      </select>

                      <input type="hidden" id="tmpvalid_<?php echo $toRes['idinfo']; ?>" value="<?php echo $toRes['valid_action']; ?>"/>

                <?php
} else {

            if ($toRes['valid_action'] == "0" || $toRes['valid_action'] == 0) {
                echo "non d&eacute;finie";
            } elseif ($toRes['valid_action'] == "1" || $toRes['valid_action'] == 1) {
                echo "OK";
            } else {
                echo "KO";
            }

        }
        ?>


              </td>

            <td class='contenu' valign="top">

                <?php

        /******************************************************/
        //
        // ajouter par mle 9092, pour limiter action Date fin
        //
        /*******************************************************/

        if (in_array($matricule_login, $array_admin_CQ)) {

            ?>
                        <textarea   onblur = "upComment(<?php echo $bufferI.",".$idFNC; ?>);" class ='<?php echo $bufferI; ?>' id="txtComment<?php echo $bufferI; ?>" name="txtComment<?php echo $bufferI; ?>" style="width: 120px; height: 60px" ><?php echo $toRes['commentaire']; ?></textarea>
                        <input type="hidden"  id="tmp_comment_<?php echo $bufferI; ?>" value="<?php echo $toRes['commentaire']; ?>" />
                        <input type="hidden" id="idinf<?php echo $bufferI; ?>" value="<?php echo $b_infoid; ?>"/>
                <?php
} else {
            ?>

                        <div style="width: 120px; height: 60px; overflow: auto;">
                            <?php echo $toRes['commentaire']; ?>
                        </div>

                <?php
}
        ?>

                        </td>

                        <input type="hidden" name="idaction<?php echo $bufferI; ?>" id="idaction<?php echo $bufferI; ?>" value="<?php echo $id_; ?>">
                        <input type="hidden" name="idinfo<?php echo $bufferI; ?>" id="idinfo<?php echo $bufferI; ?>" value="<?php echo $reference; ?>">

    <?php

        $hideId      = $toRes['idinfo'];
        $tx_avacment = $toRes['tx_avacmnt'];

        $tx_restant  = 0;
        $tx_restant  = number_format((float) $tx_restant, 2, '.', '');
        $tx_restant  = 100 - $tx_avacment;
        $tx_avacment = number_format((float) $tx_avacment, 2, '.', '');

        if (($hideId >= 3505 && $hideId <= 3664) && ($toRes['generalisation'] == $toRes['indic_efficacite'])) {
            echo "    <td class='contenu' valign='top'>&nbsp;</td> ";
        } else {

            if (in_array($matricule_login, $array_admin_CQ)) {
    ?>  
                <td class="contenu" valign="top">
                <textarea   onblur = "upGen(<?php echo $bufferI.",".$idFNC.",".$toRes['idinfo']; ?>);" class ="<?php echo $bufferI; ?>" id="txtGen<?php echo $bufferI; ?>" style="width: 120px; height: 60px" ><?php echo $toRes['generalisation']; ?></textarea>
                <input type="hidden" id="temp_gen_<?php echo $bufferI; ?>" value="<?php echo $toRes['generalisation']; ?>"/>
                </td>

    <?php
            }else{
                echo "    <td class='contenu' valign='top'>&nbsp;" . $toRes['generalisation'] . "</td>";
            }
        }

        if ($_SESSION['matricule'] != 42 && $_SESSION['matricule'] != 606 && $_SESSION['matricule'] != 9507 && $_SESSION['matricule'] != 6548) {
            $inputm = "
                <td  >
                         <span id='etat_avcmnt_span" . $bufferI . "' > " . $tx_avacment . "</span>
                          <input type='text' id='etat_avcmnt_in" . $bufferI . "' style='display:none' value=" . $tx_avacment . " onblur='update_avcmt(" . $bufferI."," . $idFNC .");' >
                      </td>
                ";

        } else {
            $inputm = "
                <td  onclick='etat_avcmnt(" . $bufferI . ");'>
                         <span id='etat_avcmnt_span" . $bufferI . "' > " . $tx_avacment . "</span>
                          <input type='text' id='etat_avcmnt_in" . $bufferI . "' style='display:none' value=" . $tx_avacment . " onblur='update_avcmt(" . $bufferI ."," . $idFNC . ");' >
                      </td>
                ";
        }
        // $jr_restant = $toRes['datefin'].'--'.$toRes['datedeb'];
        echo "    $inputm
                      <td>
                           <span id='tx_rest_span" . $bufferI . "' > " . number_format((float) $tx_restant, 2, '.', '') . "</span>
                      </td>";

        echo "    <td $colr valign='top' >&nbsp;" . $criticite . "</td> ";

        ?>


    <?php
echo "</tr>";
        echo "<input type = 'hidden' value = '" . $bufferI . "' id = 'nbr_i'>";
        $coul++;
        //}
    }
}
// print_r($arrayStockFncId);
 ?>
     <input type="hidden" id="nombre" name="nombre" value="<?php echo @pg_num_rows($rQueryId); ?>">
      </tbody></table>
    </div>

  </form>

  <p style="color: #B1221C; font-weight: bold; font-size: 12px"><?php echo $sAdminMsg; ?></p>
<script type="text/javascript" src="js/jquery-1.11.3.min.js?1"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js?1"></script>
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../js/thickbox.js"></script>

<script type="text/javascript">
$(function () {
       $("#table1").show();
       $("#idWrapperCache").show();
       $("#idContentCache").show();
       $("#imLoad").hide();
       $('#table1').tablesorter({
            theme: 'blue',
            widthFixed : true,
            widgets: ['zebra', 'stickyHeaders'],
            headerTemplate : '{content} {icon}', // Add icon for various themes
            widgets: [ 'zebra', 'stickyHeaders','filter'],
            widgetOptions: {
          // jQuery selector or object to attach sticky header to
                stickyHeaders_attachTo : '.wrapper', // or $('.wrapper')
                stickyHeaders_zIndex : 2

            },
            headers: { 0: { sorter: false}, 17: {sorter: false}, 3: {sorter: false} }
        });

        $(".kilasy").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});

        $("#dateDeb_act").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
        $("#dateFin_act").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});

        

});

$(document).ready(function(){


    var edit_autoris = $.trim($("#edit_autoris").val());
        edit_autoris = parseInt(edit_autoris);

    if (edit_autoris != 42 && edit_autoris != 606 && edit_autoris != 9507 && edit_autoris != 6548) {

                    var nbr_list = $("#nbr_i").val();
                    nbr_list = parseInt(nbr_list, 10)

      for(var p = 0 ; p<nbr_list ; p++)
      {

         $("#etat_avcmnt_in"+p).prop( "disabled", true );
      }

    }


});

function upComment(x,idfnc)
{

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtComment'+x).removeAttr('disabled');


    var content_fin = $('#txtDateFin'+x).val();
    var content_suivi = $('#txtDateSuivi'+x).val();

    var comment = $('#txtComment'+x).val();
    var tempcomment = $('#tmp_comment_'+x).val();
   //var content_comment = $('.'+x).val();
    
    console.log('commentaire');


   if(content_fin.trim() == '' || content_suivi.trim() == '' || comment.trim() == '')
   {    

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 

            }, 1);

            $('#txtComment'+x).val(tempcomment);

            alert('Merci de renseigner les champs suivants : \n\t - Date fin : non vide \n\t - Date suivi : non vide \n\t - Commentaire : non vide');

   }else{

               if(confirm('Voulez-vous vraiment modifi\351e le Commentaire ?'))
               {
                  
                  var id_inf = $('#idinf'+x).val();

                  $.ajax({
                        type: "POST",
                        url: "FNCUpdateAcionCorr.php",
                        data: {
                            comment : 'comment_only',
                            data : comment,
                            id_inf:id_inf,
                            idfnc: idfnc,
                            matricule : <?php echo $matricule_login; ?>
                        },
                        success: function(rslt){

                        if(rslt == 1)
                        {       

                            $("#msg_modif").empty();
                            $("#msg_modif").html("Commentaire");
                            $("#info_modif").show();

                            $('#txtComment'+x).val(comment);
                            $('#tmp_comment_'+x).val(comment);

                            setTimeout(function(){  
                                $("#info_modif").css('display','none'); 
                            }, 1500);

                        }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txtComment'+x).val(tempcomment);

                                return 0;

                        }

                     },
                     async: false
                  });

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

               }
               else
               {

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

                    $('#txtComment'+x).val(tempcomment);

                    return 0 ;

               }

    }


}

function myValidation()
{
   // var xx = $("#slctActionValidationFiltre").val();alert('3'+xx);
   $("#frmActionCorrective").submit();
}
   // Fonction affichage action corrective
function afficheActionCorrective ()
{
   document.getElementById('frmActionCorrective').action = "FNCAfficheActionCorrective.php" ;
   document.getElementById('frmActionCorrective').submit() ;
   //NC_SIR_160204
}


function changeEtat(id,idfnc)
{

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $("#slctActionValidation"+id).removeAttr('disabled');

    var b_slctActionValidation = $("#slctActionValidation"+id).val();
    var tmp_etat = $('#tmpEtat_'+id).val();
    var id_nc_fnc_infos = $("#bvalId_"+id).val();
    
    console.log('etat');

    if(b_slctActionValidation.trim() == 'en cours'){

        var date_suivi = $('#txtDateSuivi'+id).val();

        if(date_suivi.trim() == ''){

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 

                $("#slctActionValidation"+id).val(tmp_etat);

            }, 1);

            alert('Merci de renseigner le champ suivant : \n\t - Date suivi : non vide');

            return 0;

        }else{

            if(confirm('Voulez-vous vraiment modifi\351e l\'Etats r\351alisaton actions ?')){

                    $.ajax({
                           
                            type: "POST",
                            url: "FNCUpdateAcionCorr.php",
                            data: {
                                  etat : b_slctActionValidation,
                                  idfnc : idfnc,
                                  id_inf :  id_nc_fnc_infos,
                                  matricule : <?php echo $matricule_login; ?>
                            },
                            success: function(rslt){

                                    if(rslt == 1)
                                    {
                                        $("#msg_modif").empty();
                                        $("#msg_modif").html("Etats r\351alisaton actions");
                                        $("#info_modif").show();
                                         
                                        $("#slctActionValidation"+id).val(b_slctActionValidation);
                                        $('#tmpEtat_'+id).val(b_slctActionValidation);

                                        setTimeout(function(){  
                                            $("#info_modif").css('display','none'); 
                                        }, 1500);
                                    }else{

                                            $("#info_warning").show();

                                            setTimeout(function(){  
                                                
                                                $("#info_warning").css('display','none'); 

                                            }, 3000);

                                            $("#slctActionValidation"+id).val(tmp_etat);

                                            return 0;

                                    }
                            },
                            async: false
                    });

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

            }else{

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                    $("#slctActionValidation"+id).val(tmp_etat);

                }, 1);

                return 0;
            }

        }


    }else{

            if(confirm('Voulez-vous vraiment modifi\351e l\'Etats r\351alisaton actions ?')){

                    $.ajax({
                           
                            type: "POST",
                            url: "FNCUpdateAcionCorr.php",
                            data: {
                                  etat : b_slctActionValidation,
                                  idfnc : idfnc,
                                  id_inf :  id_nc_fnc_infos,
                                  matricule : <?php echo $matricule_login; ?>
                            },
                            success: function(rslt){

                                    if(rslt == 1)
                                    {
                                        $("#msg_modif").empty();
                                        $("#msg_modif").html("Etats r\351alisaton actions");
                                        $("#info_modif").show();

                                        $("#slctActionValidation"+id).val(b_slctActionValidation);
                                        $('#tmpEtat_'+id).val(b_slctActionValidation);
                                            
                                        setTimeout(function(){  
                                            $("#info_modif").css('display','none'); 
                                        }, 1500);
                                    }else{

                                            $("#info_warning").show();

                                            setTimeout(function(){  
                                                
                                                $("#info_warning").css('display','none'); 

                                            }, 3000);

                                            $("#slctActionValidation"+id).val(tmp_etat);

                                            return 0;

                                    }

                            },
                            async: false
                    });

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

            }else{

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                    $("#slctActionValidation"+id).val(tmp_etat);

                }, 1);

                return 0;
            }
    }

}

function upSuivi(x, idfnc)
{

    //hide_input_avcmnt();

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);

    $('#txtDateSuivi'+x).removeAttr('disabled');

    var tmp_suivi = $("#hide_suivi"+x).val();

    //console.log(tmp_suivi);

    var date_suivi = $('#txtDateSuivi'+x).val();

    var date_fin = $('#txtDateFin'+x).val();

    var comment = $('#txtComment'+x).val();
      
    var id_inf = $('#idinf'+x).val();
    // return 0;
    
    console.log('date_suivi');
    
    if(date_fin.trim() == '' || date_suivi.trim() == ''){

        setTimeout(function(){  

            $('textarea').removeAttr('disabled');
            $('select').removeAttr('disabled');
            $('input').removeAttr('disabled'); 

        }, 1);
        
        $('#txtDateSuivi'+x).val(tmp_suivi);

        alert('Merci de renseigner les champs suivants : \n\t - Commentaire : non vide \n\t - Date suivi : non vide \n\t - Date fin : non vide');

        return 0; 

    }else{

        if(confirm('Voulez-vous vraiment modifi\351e la Date suivi ?')){
      
                $.ajax({
                     type: "POST",
                     url: "FNCUpdateAcionCorr.php",
                     data: {
                           comment : 'date_suivi',
                           data : date_suivi,
                           id_inf:id_inf,
                           idfnc : idfnc,
                           matricule : <?php echo $matricule_login; ?>
                           },
                     success: function(rslt){
                        if(rslt == 1)
                        {
                            $("#msg_modif").empty();
                            $("#msg_modif").html("Date suivi");
                            $("#info_modif").show();

                            $('#txtDateSuivi'+x).val(date_suivi);
                            $("#hide_suivi"+x).val(date_suivi);

                            setTimeout(function(){  
                                $("#info_modif").css('display','none'); 
                            }, 1500);
                        }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txtDateSuivi'+x).val(tmp_suivi);

                                return 0;

                        }
                     },
                     async: false
                });


                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);


        }else{


                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#txtDateSuivi'+x).val(tmp_suivi);

                return 0;

        }
    
    }


}

function upFin(x, idfnc)
{

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtDateFin'+x).removeAttr('disabled');

    var datefin = $('#txtDateFin'+x).val();
    var tmpdatef = $('#tmp_datef_'+x).val();

    var id_inf = $('#idinf'+x).val();

    console.log('date fin');

    if(datefin.trim() == ''){

        setTimeout(function(){  

            $('textarea').removeAttr('disabled');
            $('select').removeAttr('disabled');
            $('input').removeAttr('disabled'); 

        }, 1);

        alert('Date fin vide !');

        return 0;


    }else{
      
        if(confirm('Voulez-vous vraiment modifi\351e la Date fin ?')){

                $.ajax({
                        type: "POST",
                        url: "FNCUpdateAcionCorr.php",
                        data: {
                               comment : 'date_fin',
                               data : datefin,
                               id_inf:id_inf,
                               idfnc : idfnc,
                               matricule : <?php echo $matricule_login; ?>
                        },
                        success: function(rslt){
                        if(rslt == 1)
                        {
                            $("#msg_modif").empty();
                            $("#msg_modif").html("Date fin");
                            $("#info_modif").show();

                            $('#txtDateFin'+x).val(datefin);
                            $('#tmp_datef_'+x).val(datefin);

                            setTimeout(function(){  
                                $("#info_modif").css('display','none'); 
                            }, 1500);
                        }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txtDateFin'+x).val(tmpdatef);

                                return 0;

                        }
                     },
                     async: false
                });

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

        }else{

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 

            }, 1);

            $('#txtDateFin'+x).val(tmpdatef);

            return 0;
        }
       
    }


}


function etat_avcmnt(i)
{

  hide_input_avcmnt();

  $('#etat_avcmnt_in'+i).show();
  $('#etat_avcmnt_span'+i).hide();

}

function update_avcmt(i, idfnc)
{
    var tx_avcmnt  = $.trim($('#etat_avcmnt_in'+i).val());
    var tx_avcmnt_span  = $.trim($('#etat_avcmnt_span'+i).text());
    var content_comment = $('#txtComment'+i).val();

    if(validate_decimal(tx_avcmnt) == false) /*Teste si nombre acceptee*/
    {
      $('#etat_avcmnt_in'+i).val(tx_avcmnt_span);
      return false;
    }

    if(tx_avcmnt =='' || tx_avcmnt > 100 || tx_avcmnt < 0 )
    {
        alert_msg_err();
        return false;
    }

    if(content_comment.trim() == ''){

      $("#info_warning_comment").show();
            setTimeout(function(){  
                $("#info_warning_comment").css('display','none'); 
            }, 4000);
        return false;
    }

    $('#etat_avcmnt_in'+i).hide();
    $('#etat_avcmnt_span'+i).show();


    $('#etat_avcmnt_span'+i).text($('#etat_avcmnt_in'+i).val());
    // var tx_avcmnt_span  = $.trim($('#etat_avcmnt_span'+i).text());
        var tx_avcmnt_span  = $.trim($('#etat_avcmnt_span'+i).text());
          tx_avcmnt_span  = parseFloat(tx_avcmnt_span).toFixed(2); /* .00 uniformiser la partie fractionnaire */
        var id_inf = $('#idinf'+i).val();

        //upSuivi(i);

        $.ajax({
             type: "POST",
             url: "FNCUpdateAcionCorr.php",
             data: {
                   comment : 'taux_avcmnt',
                   tx_avcmnt_span : tx_avcmnt_span,
                   id_inf:id_inf,
                   idfnc: idfnc,
                   matricule : <?php echo $matricule_login; ?>
                   },
                success: function(rslt){
                  $('#etat_avcmnt_span'+i).text(tx_avcmnt_span); /*valeur dans span = valeur dans input*/
                  $('#etat_avcmnt_in'+i).val(tx_avcmnt_span);      /*valeur dans span = valeur dans input*/
                  var tx_restant = 100 - tx_avcmnt_span;
                  $('#tx_rest_span'+i).text(tx_restant.toFixed(2)); /*Taux restants */
                  hide_input_avcmnt(); /*Cache tous les ipnuts ouvert*/

                    if(rslt == 1)
                    {
                        $("#msg_modif").empty();
                        $("#msg_modif").html("Taux d'avancement action");
                        $("#info_modif").show();
                            
                        setTimeout(function(){  
                            $("#info_modif").css('display','none'); 
                        }, 1500);
                    }else{

                            $("#info_warning").show();

                            setTimeout(function(){  
                                
                                $("#info_warning").css('display','none'); 

                            }, 3000);

                            return 0;

                    }

             },
             async: true
        });



}

function hide_input_avcmnt()
{
    var nbr_row =  $("#nombre").val();

    for (var j = 0; j < nbr_row; j++)
    {
        $('#etat_avcmnt_in'+j).hide();
        $('#etat_avcmnt_span'+j).show();
    }

}

function validate_decimal(value)    {
    var re = /^\d*(\.\d{1})?\d{0,1}$/;
    if(re.test(value)){
       return true;
    }else{
        alert('Vous devez entrer un taux exacte.');
        return false;
    }
}

function alert_msg_err()
{
  alert('Vous devez entrer un taux entre  0 et 100');
}

function is_float(n)
{
    return n === +n && n !== (n|0);
}



function viewFNC(idFNC)
{

      var widthScreen = screen.width;
         widthScreen -= 50;
      var test = 0;
         // alert('ppp'+idFNC);return 0;
      tb_show('','FNCConsulter.php?height=600&width='+widthScreen+'&idFNC='+idFNC+'&varTest='+test+'&corrective=1');
      widthScreen = screen.width;
}

// function pour le filtre date

function searchDate(){

  var dateDeb_act = $('#dateDeb_act').val();
  var dateFin_act = $('#dateFin_act').val();

  deb = dateDeb_act.split('-');
  fin = dateFin_act.split('-');

  Odeb = new Date(deb[0], deb[1], deb[2]);
  Ofin = new Date(fin[0], fin[1], fin[2]);

  if(Odeb <= Ofin){
    document.location.href = 'FNCSuiviActionNC.php?debAct='+dateDeb_act+'&finAct='+dateFin_act;
  }else{
    $("#info_warning_date").show();
    setTimeout(function(){  $("#info_warning_date").css('display','none'); }, 2500);
  }



}



function changeValid_action(id_inf, x, idfnc){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#slt_valid_'+id_inf).removeAttr('disabled');

    var valid = $('#slt_valid_'+id_inf).val();

    var tmp_valid = $('#tmpvalid_'+id_inf).val();

    var taux  = $.trim($('#etat_avcmnt_span'+x).text());
        taux  = parseFloat(taux).toFixed(2); 

    var realisation = $("#slctActionValidation"+x).val();

    var comment = $('#txtComment'+x).val();
    var datefin = $('#txtDateFin'+x).val();
    var datesuivi = $('#txtDateSuivi'+x).val();

    console.log('valid action');

    if(valid == 1){

        if( taux == 100.00 && comment.trim() != '' && datefin.trim() != '' && datesuivi.trim() != '' && realisation.trim() == 'ok'){

            if(confirm('Voulez-vous vraiment modifi\351e la Validation de l\'action ?')){

                    $.ajax({
                           type: "POST",
                           url: "FNCUpdateAcionCorr.php",
                           data: {
                                 comment : 'valid_action',
                                 data : valid,
                                 id_inf: id_inf,
                                 idfnc : idfnc,
                                 matricule : <?php echo $matricule_login; ?>
                           },
                           success: function(rslt){

                              if(rslt == 1)
                              {
                                    $("#msg_modif").empty();
                                    $("#msg_modif").html("Validation de l\'action");
                                    $("#info_modif").show();

                                    $("#info_modif").show();
                                        
                                    setTimeout(function(){  
                                        $("#info_modif").css('display','none'); 
                                    }, 1500);

                                    $('#slt_valid_'+id_inf).val(valid);
                                    $('#tmpvalid_'+id_inf).val(valid);
                              }else{

                                        $("#info_warning").show();

                                        setTimeout(function(){  
                                            
                                            $("#info_warning").css('display','none'); 

                                        }, 3000);

                                        $('#slt_valid_'+id_inf).val(tmp_valid);

                                        return 0;

                                }
                           },
                           async: true
                    });

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

            }else{

                
                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#slt_valid_'+id_inf).val(tmp_valid);

                return 0;

            }

        }else{

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 

            }, 1);

            $('#slt_valid_'+id_inf).val(tmp_valid);

            alert('Merci de renseigner les champs suivants : \n\t- Taux d\'avancement action : 100.00\n\t - Commentaire : non vide \n\t - Etats r\351alisation actions : action faite \n\t - Date fin : non vide \n\t - Date suivi : non vide');


        }

    }else{

            if(confirm('Voulez-vous vraiment modifi\351e la Validation de l\'action ?')){

                    $.ajax({
                           type: "POST",
                           url: "FNCUpdateAcionCorr.php",
                           data: {
                                 comment : 'valid_action',
                                 data : valid,
                                 id_inf: id_inf,
                                 idfnc : idfnc,
                                 matricule : <?php echo $matricule_login; ?>
                           },
                           success: function(rslt){

                              if(rslt == 1)
                              { 
                                    $("#msg_modif").empty();
                                    $("#msg_modif").html("Validation de l'action");
                                    $("#info_modif").show();

                                    $("#info_modif").show();
                                        
                                    setTimeout(function(){  
                                        $("#info_modif").css('display','none'); 
                                    }, 1500);

                                    $('#slt_valid_'+id_inf).val(valid);
                                    $('#tmpvalid_'+id_inf).val(valid);
                              }else{

                                        $("#info_warning").show();

                                        setTimeout(function(){  
                                            
                                            $("#info_warning").css('display','none'); 

                                        }, 3000);

                                        $('#slt_valid_'+id_inf).val(tmp_valid);

                                        return 0;

                                }
                           },
                           async: false
                    });

                    setTimeout(function(){  

                        $('textarea').removeAttr('disabled');
                        $('select').removeAttr('disabled');
                        $('input').removeAttr('disabled'); 

                    }, 1);

            }else{

                
                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#slt_valid_'+id_inf).val(tmp_valid);

                return 0;

            }

    }
}

function maj_faille(id_inf, idfnc){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtfaille'+id_inf).removeAttr('disabled');

    var faille = $('#txtfaille'+id_inf).val();
    var tmpfaille = $('#tmp_faille_'+id_inf).val();

    console.log('faille');

    if(faille.trim() ==''){

        alert('Faille identifi\351e vide ! ');

        setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 
        }, 1);

    }else{

              if(confirm('Voulez-vous vraiment modifi\351e le Faille identifi\351e ?')){

                $.ajax({
                           type: "POST",
                           url: "FNCUpdateAcionCorr.php",
                           data: {
                                 comment : 'faille',
                                 data : faille,
                                 id_inf: id_inf,
                                 idfnc : idfnc,
                                 matricule : <?php echo $matricule_login; ?>
                           },
                           success: function(rslt){

                                if(rslt == 1)
                                {   

                                    $("#msg_modif").empty();
                                    $("#msg_modif").html("Faille identifi\351e");
                                    $("#info_modif").show();

                                    $('#txtfaille'+id_inf).val(faille);
                                    $('#tmp_faille_'+id_inf).val(faille);

                                    setTimeout(function(){
                                        $("#info_modif").css('display','none'); 
                                    }, 1500);
                                }else{

                                        $("#info_warning").show();

                                        setTimeout(function(){  
                                            
                                            $("#info_warning").css('display','none'); 

                                        }, 3000);

                                        $('#txtfaille'+id_inf).val(tmpfaille);

                                        return 0;

                                }

                           },
                           async: true
                });

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

              }else{

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#txtfaille'+id_inf).val(tmpfaille);

                return 0;
              }
    }

}


function maj_impact(id_inf, idfnc){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtimpact'+id_inf).removeAttr('disabled');

    var impact = $('#txtimpact'+id_inf).val();

    var tmpimpact = $('#tmp_impact_'+id_inf).val();

    console.log('impact');

    if(impact.trim() == ''){

        alert('Impact vide ! ');

        setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 
        }, 1);

    }else{

          if(confirm('Voulez-vous vraiment modifi\351e l\'Impact ?')){

            $.ajax({
                   type: "POST",
                   url: "FNCUpdateAcionCorr.php",
                   data: {
                         comment : 'impact',
                         data : impact,
                         id_inf: id_inf,
                         idfnc : idfnc,
                         matricule : <?php echo $matricule_login; ?>
                   },
                   success: function(rslt){

                      if(rslt == 1)
                      {

                            $("#msg_modif").empty();
                            $("#msg_modif").html("Impact");
                            $("#info_modif").show();

                            $('#txtimpact'+id_inf).val(impact);
                            $('#tmp_impact_'+id_inf).val(impact);    


                            setTimeout(function(){  
                                $("#info_modif").css('display','none'); 
                            }, 1500);
                      }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txtimpact'+id_inf).val(tmpimpact);

                                return 0;

                     }
                   },
                   async: true
            });

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 

            }, 1);

          }else{

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);  

                $('#txtimpact'+id_inf).val(tmpimpact);
                
                return 0;

          }

    }

}


function maj_description(id_fnc, x, id_inf){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtdesc'+id_fnc).removeAttr('disabled');

    var desc = $('#txtdesc'+id_fnc).val();
    var tmpdesc = $('#tmp_desc_'+id_fnc).val();

    console.log('description');

    if(desc.trim() == ''){

        alert('Description vide ! ');

        setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 
        }, 1);

    }else{

            if(confirm('Voulez-vous vraiment modifi\351e la Description ?')){

                $.ajax({
                       type: "POST",
                       url: "FNCUpdateAcionCorr.php",
                       data: {
                             comment : 'description',
                             data : desc,
                             idfnc: id_fnc,
                             id_inf: id_inf,
                             matricule : <?php echo $matricule_login; ?>
                       },
                       success: function(rslt){

                          if(rslt == 1)
                          { 
                            
                                $("#msg_modif").empty();
                                $("#msg_modif").html("Description");
                                $("#info_modif").show();

                                $('#txtdesc'+id_fnc).val(desc);
                                $('#tmp_desc_'+id_fnc).val(desc);
                                
                                setTimeout(function(){  
                                    $("#info_modif").css('display','none'); 
                                }, 1500);
                          }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txtdesc'+id_fnc).val(tmpdesc);

                                return 0;

                        }
                       },
                       async: true
                });

                setTimeout(function(){
                    
                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

            }else{
                
                setTimeout(function(){
                    
                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#txtdesc'+id_fnc).val(tmpdesc);

                return 0;
            }

    }

}


function maj_efficacite(id_inf, x, id_fnc){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txteffic'+id_fnc).removeAttr('disabled');

    var efficacite = $('#txteffic'+id_inf).val();
    var tmpeffic = $('#tmp_effic_'+id_inf).val();

    console.log('efficacite');

    if(efficacite.trim() == ''){

        alert('Indicateur d\'\351fficacit\351 vide ! ');

        setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled'); 
        }, 1);

    }else{

          if(confirm('Voulez-vous vraiment modifi\351e l\'Indicateur d\'\351fficacit\351 ?')){

                $.ajax({
                       type: "POST",
                       url: "FNCUpdateAcionCorr.php",
                       data: {
                             comment : 'efficacite',
                             data : efficacite,
                             id_inf: id_inf,
                             idfnc : id_fnc,
                             matricule : <?php echo $matricule_login; ?>
                       },
                       success: function(rslt){

                          if(rslt == 1)
                          {

                                $("#msg_modif").empty();
                                $("#msg_modif").html("Indicateur d\'\351fficacit\351");
                                $("#info_modif").show();

                                $('#txteffic'+id_inf).val(efficacite);
                                $('#tmp_effic_'+id_inf).val(efficacite);

                                setTimeout(function(){  
                                    $("#info_modif").css('display','none'); 
                                }, 1500);
                          }else{

                                $("#info_warning").show();

                                setTimeout(function(){  
                                    
                                    $("#info_warning").css('display','none'); 

                                }, 3000);

                                $('#txteffic'+id_inf).val(tmpeffic);

                                return 0;

                        }
                    },
                       async: false
                });

                setTimeout(function(){
                    
                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

          }else{

                setTimeout(function(){  

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled'); 

                }, 1);

                $('#txteffic'+id_inf).val(tmpeffic);

            return 0;
          }

    }
      

}

function maj_obj(id_inf, x, id_fnc){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    $('#txtobj'+id_inf).removeAttr('disabled');

    var obj = $('#txtobj'+id_inf).val();
    var tmpobj = $('#tmp_obj_'+id_inf).val();

    console.log('objectif');

    if(obj.trim() == ''){

        alert('Objectif et \351ch\351ance vide ! ');

        setTimeout(function(){  

            $('textarea').removeAttr('disabled');
            $('select').removeAttr('disabled');
            $('input').removeAttr('disabled');
        
        }, 1);

    }else{

          if(confirm('Voulez-vous vraiment modifi\351e l\'Objectif et \351ch\351ance ?')){

            $.ajax({
                   type: "POST",
                   url: "FNCUpdateAcionCorr.php",
                   data: {
                         comment : 'objectif',
                         data : obj,
                         id_inf: id_inf,
                         idfnc : id_fnc,
                         matricule : <?php echo $matricule_login; ?>
                   },
                   success: function(rslt){

                      if(rslt == 1)
                      { 

                            $("#msg_modif").empty();
                            $("#msg_modif").html("Objectif et \351ch\351ance");
                            $("#info_modif").show();

                            $('#txtobj'+id_inf).val(obj);
                            $('#tmp_obj_'+id_inf).val(obj);

                            setTimeout(function(){  
                                
                                $("#info_modif").css('display','none'); 

                            }, 1500);

                      }else{

                            $("#info_warning").show();

                            setTimeout(function(){  
                                
                                $("#info_warning").css('display','none'); 

                            }, 3000);

                            $('#txtobj'+id_inf).val(tmpobj);

                            return 0;

                      }
                   },
                   async: false
            });

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled');

            }, 1);

          }else{

                setTimeout(function(){ 

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled');

                }, 1);

                $('#txtobj'+id_inf).val(tmpobj);

                return 0;
          }
    }

}

function upGen(id, idfnc, id_inf){

    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);
    $('input').attr('disabled', true);
    
    $('#txtGen'+id).removeAttr('disabled');

    var gen = $('#txtGen'+id).val();
    var tempgen = $('#temp_gen_'+id).val();

    console.log('generalisation');

    if(gen.trim() == '' ){

        alert('G\351n\351ralisation vide ! ');

        setTimeout(function(){  

            $('textarea').removeAttr('disabled');
            $('select').removeAttr('disabled');
            $('input').removeAttr('disabled');
        
        }, 1);

    }else{

        if(confirm('Voulez-vous vraiment modifi\351e G\351n\351ralisation ?')){

            $.ajax({
                   type: "POST",
                   url: "FNCUpdateAcionCorr.php",
                   data: {
                         comment : 'update_gen',
                         data : gen,
                         id_inf: id_inf,
                         idfnc: idfnc,
                         matricule : <?php echo $matricule_login; ?> 
                   },
                   success: function(rslt){

                      if(rslt == 1)
                      { 

                            $("#msg_modif").empty();
                            $("#msg_modif").html("G\351n\351ralisation");
                            $("#info_modif").show();

                            $('#txtGen'+id).val(gen);
                            $('#temp_gen_'+id).val(gen);

                            setTimeout(function(){  
                                
                                $("#info_modif").css('display','none'); 

                            }, 1500);

                      }else{

                            $("#info_warning").show();

                            setTimeout(function(){  
                                
                                $("#info_warning").css('display','none'); 

                            }, 3000);

                            $('#txtGen'+id).val(tempgen);

                            return 0;

                      }
                   },
                   async: false
            });

            setTimeout(function(){  

                $('textarea').removeAttr('disabled');
                $('select').removeAttr('disabled');
                $('input').removeAttr('disabled');

            }, 1);

          }else{

                setTimeout(function(){ 

                    $('textarea').removeAttr('disabled');
                    $('select').removeAttr('disabled');
                    $('input').removeAttr('disabled');

                }, 1);

                $('#txtGen'+id).val(tempgen);

                return 0;
          }

    }


}
    </script>

      </body>


</html>