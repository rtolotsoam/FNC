<?php
require_once "DBConnect.php";

$zSelectRef      = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC";
$oQuerySelectRef = @pg_query($conn, $zSelectRef) or die(pg_last_error($conn));
$iNbSelectRef    = @pg_num_rows($oQuerySelectRef);

$zSql = "
SELECT
    *
FROM
    (
        SELECT
            *
        FROM
            (
                SELECT
                    NC_FICHE.\"FNC_CREATIONDATE\"    AS  DATE_CREAT
                ,   NC_FICHE.FNC_ID     AS  ID
                ,   NC_FICHE.FNC_CLIENT AS  CLIENT
                ,   NC_FICHE.FNC_TYPE   AS  TYPE
                ,   NC_FICHE.FNC_REF    AS  REFFNC
                ,   NC_ACTION.\"ACTION_DEBDATE\"  AS  DATEDEB
                ,   NC_ACTION.\"ACTION_FINDATE\"  AS  DATEFIN
                ,   NC_ACTION.ACTION_DESCRIPTION    AS  DESCRIPTION
                ,   NC_ACTION.ACTION_RESPONSABLE    AS  RESPONSABLE
                ,   FNC_GRAVITE_ID
                ,   FNC_FREQUENCE_ID
                ,   FNC_FREQ_CAT_ID
                ,   FNC_GRAV_CAT_ID
                FROM
                    NC_FICHE
                ,   NC_ACTION
                WHERE
                    NC_ACTION.ACTION_ETAT   !=  'ok'
                AND NC_FICHE.FNC_ID         =   NC_ACTION.\"ACTION_FNCID\"
                AND NC_ACTION.ACTION_TYPE   =   'curative'
                ORDER BY
                    NC_ACTION.\"ACTION_FINDATE\"    ASC,
                    NC_FICHE.FNC_REF    ASC
            )   AS  TEMP
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
            TEMP2.FNC_ID    =   TEMP.ID
    )   AS  TEMP3
ORDER BY
    DATEDEB ASC";

/* echo "<pre>";
print_r($zSql);
echo "</pre>";*/
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Suivi des actions Curatives</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

      <script type="text/javascript" src="../js/thickbox.js"></script>

      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css" />

    <!--link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
    <script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <script type="text/javascript">$(document).ready(function(){$("#table1").tablesorter();});</script-->

    <style>
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

           height: 550px;
           overflow-y: auto;
           border:4px solid #1e5799;width:100%;
            direction:ltr;
         }

    </style>

  </head>

  <body>
   <center><span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /></br><b>Chargement...</b></span></center>
   <span id='idWrapperCache' style='display:none;'>
    <p style="font-family: verdana; font-size: 12px; color: #666666">
      Cette page permet de visualiser les actions curatives dont l'&eacute;tat n'est pas "OK".
         <ul style="font-family: verdana; font-size: 12px">
            <li>
               <font color="#FF0066">En rouge :</font> les actions curatives dont la date de fin est inf&eacute;rieure &agrave; la date du jour.
            </li>
            <li>
               <font color="#FF9933">En orange :</font> les actions curatives dont la date de fin est &eacute;gale &agrave; la date du jour.
            </li>
            <li>
               <font color="#008000">En vert :</font> les actions curatives dont la date de fin est sup&eacute;rieure &agrave; la date du jour.
            </li>
            <li>
               <font color="#000000">En noir :</font> les actions curatives dont aucune date de fin n'est d&eacute;finie.
            </li>
         </ul>
    </p>

      <p align="right">
         <a href="toExcel.php?txtActionType=c" style="text-decoration: none">
            <input type="button" id="btnExportToExcel" name="btnExportToExcel" class="ui-state-default" value="Export Excel" style="cursor: pointer" />
         </a>
      </p>
</span>
    <div class='wrapper' style='display:none;' id='idCache'>
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="3" id='myTable' >
            <thead><tr>
               <th colspan="6" class="titre">Fiche de non conformit&eacute; </th>
               <th colspan="4" class="titre">Actions</th>
               <th class="titre" rowspan="2">Criticit&eacute;</th>
            </tr>
            <tr>
               <th width="12.5%" class="titre">Nom du client </th>
               <th width="5%" class="titre">BU </th>

               <th width="15%" class="titre">R&eacute;f&eacute;rence</th>
               <th width="5%" class="titre">Type</th>
               <th width="5%" class="titre">Type d'appel</th>
               <!-- Ajouter par mle 9092, pour afficher la date de création du FNC-->
               <th width="8%" class="titre">Date de cr&eacute;ation FNC </th>
               <th width="8%" class="titre">Date de d&eacute;but d'actions</th>
               <th width="25%" class="titre">Description</th>
               <th width="12.5%" class="titre">Responsable</th>
               <th width="12%" class="titre">Date de fin </th>
            </tr></thead><tbody>
            <?php
// echo $zSql;
$oQuerySql = @pg_query($conn, $zSql) or die(pg_last_error($conn));
$iNbSql    = @pg_num_rows($oQuerySql);

for ($i = 0; $i < $iNbSql; $i++) {

    $coul  = ($i % 2 == 1 ? "#ffffff" : "#ebebeb");
    $toRes = @pg_fetch_array($oQuerySql, $i);
    $idFNC = $toRes['id'];
    /****************** Modif Fulgence 20150210  ******************/
    // Affichage criticité
    if ($toRes['fnc_gravite_id'] != '' && $toRes['fnc_frequence_id'] != '') {
        //if($toRes['fnc_gravite_id'] != '')
        $igrav = $toRes['fnc_gravite_id'];
        /*else
        $igrav = 1 ;

        if($toRes['fnc_frequence_id'] != '')*/
        $ifreq = $toRes['fnc_frequence_id'];
        /*else
        $ifreq = 1 ;*/

        // gravité
        $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$igrav ";
        //echo $sqlSltGrv ;
        $resGrv  = pg_query($conn, $sqlSltGrv) or die(pg_last_error($conn));
        $arGrv   = pg_fetch_array($resGrv);
        $grv_ech = $arGrv['echelle_id_grav'];

        //Fréquence
        $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $ifreq ";
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
    if (isset($idFNC)) {
        $sqlAppeType = "
                     select libelle_typologie from cc_sr_typologie cc_typo inner join nc_fiche  ncf
                     on ncf.id_cc_sr_typo = cc_typo.id_typologie
                     where fnc_id = " . $idFNC;

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

    $bu_curative = trim($toRes['lib_bu']);
    // print_r($bu_curative);
    if ($bu_curative == '') {
        $bu_curative = 'AUCUN';
    }
    echo "  <tr style=\"cursor:pointer; color: $color;\" bgcolor=\"$coul\" onclick=viewFNC(" . $idFNC . "); >
                           <td width=\"12.5%\" class=\"contenu\">&nbsp;" . $toRes['client'] . "</td>
                           <td width=\"15%\" class=\"contenu\">&nbsp;" . $bu_curative . "</td>
                           <td width=\"15%\" class=\"contenu\">&nbsp;" . $toRes['reffnc'] . "</td>
                           <td width=\"5%\" class=\"contenu\">&nbsp;" . $toRes['type'] . "</td>
                           <td width=\"8%\" class=\"contenu\" style='text-align: center;'>&nbsp;" . $typeApp . "</td>
                           <td width=\"8%\" class=\"contenu\" style='text-align: center;'>&nbsp;" . $toRes['date_creat'] . "</td>
                           <td width=\"8%\" class=\"contenu\">&nbsp;" . $toRes['datedeb'] . "</td>
                           <td width=\"25%\" class=\"contenu\">&nbsp;" . $toRes['description'] . "</td>
                           <td width=\"12.5%\" class=\"contenu\">&nbsp;" . $toRes['responsable'] . "</td>
                           <td width=\"15%\" class=\"contenu\">&nbsp;" . $toRes['datefin'] . "</td>
                           <td width=\"12%\" $colr >&nbsp;" . $criticite . "</td>
                        </tr>";
}
?>
      </tbody></table>
    </div>
    <script type="text/javascript">

      $(function () {
       $("#idCache").show();
       $("#idWrapperCache").show();
       // $("#idContentCache").show();
       $("#imLoad").hide();
       $('#myTable').tablesorter({

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
        0: { sorter: false}, 1: { sorter: false} }
    });});

   function viewFNC(idFNC)
   {
      var widthScreen = screen.width;
         widthScreen -= 50;
      var test = 0;
         // alert('ppp'+idFNC);return 0;
        tb_show('','FNCConsulter.php?height=600&width='+widthScreen+'&idFNC='+idFNC+'&varTest='+test+'&curative=1');
      widthScreen = screen.width;
   }

    </script>
  </body>
</html>
