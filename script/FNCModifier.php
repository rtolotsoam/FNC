<?php
//session_start() ;

/*****************************************************************/
//                                                              //
//  fichier de cofiguration admin FNC, ajouter par mle 9092     //
/****************************************************************/
include "FNCUser_admin.php";

//----------------------------------- les donnees utilisées pour la table log ---------------------------------
list($usec, $sec) = explode(" ", microtime());
$time_start       = (float) $usec + (float) $sec;
$zFileNameLog     = "gpao2/fnc/FNCModifier.php";
//---------------------------------------------------------------------------------------------------------------

/**
 * @author ralainahitra angelo
 * @copyright 2010
 * @derniere modification : 2010-05-12 par ralainahitra angelo
 * @derniere modification : 2012-07-02 par Fulgence
 * @derniere modification : 2015-02-26 par Fulgence
 */
// echo 'xx';
/*
Array ( [matricule] => 878 [niveauacces] => 3 [pwdindb] => MIMI [prenom_user] => Mirana Simone [groupebd] => coach [bIPExt] => 0 [zEquipe] => AP [zDept1] => BATCH 1 [zFonction] => MANAGER [zDept2] => BATCH 1 [conn] => Resource id #4 [MSGAjout] => Dernière action : ouverture de la fiche ayant la référence : NC_INTERNETSI_161111
Statut : fiche ouverte [MSGSearch] => Dernière action : modification de la fiche ayant la référence : NC_INTERNETSI_161111
Statut : une erreur s'est produite lors de la modification, vérifiez que votre session est toujours active! )
 */

require_once "DBConnect.php";

$disabled = 'disabled style="cursor:not-allowed"';

if (isset($_REQUEST["txtId"])) {
    $iId = $_REQUEST["txtId"];
}

if (isset($_REQUEST["txtRef"])) {
    $zRef = $_REQUEST["txtRef"];
} else {

    $bsqlFNC = "select * from nc_fiche where fnc_id =$iId";

    $oReqbsqlFNC = @pg_query($conn, $bsqlFNC);
    $row         = pg_fetch_assoc($oReqbsqlFNC);

    $slctClientName     = $row['fnc_client'];
    $slctCode           = $row['fnc_code'];
    $txtCrationDate     = $row['fnc_creationDate'];
    $txtModifDate       = $row['fnc_modifDate'];
    $txtCreateurMatr    = $row['fnc_createur'];
    $s_txtCP            = $row['s_txtCP'];
    $slctTraitStatut    = $row['slctTraitStatut'];
    $slctType           = $row['slctType'];
    $slctTraitStatutCor = $row['slctTraitStatutCor'];
    $motif_fnc          = $row['motif_fnc'];
    // print_r($row);
}

$slctClientName     = $_REQUEST['slctClientName'];
$slctCode           = $_REQUEST['slctCode'];
$txtCrationDate     = $_REQUEST['txtCrationDate'];
$txtModifDate       = $_REQUEST['txtModifDate'];
$txtCreateurMatr    = $_REQUEST['txtCreateurMatr'];
$s_txtCP            = $_REQUEST['s_txtCP'];
$slctTraitStatut    = $_REQUEST['slctTraitStatut'];
$slctType           = $_REQUEST['slctType'];
$slctTraitStatutCor = $_REQUEST['slctTraitStatutCor'];

$motif_fnc = $_REQUEST['motif_fnc'];

$txtNameClient   = $slctClientName;
$txtCode_cli     = $slctCode;
$createDate      = $txtCrationDate;
$modifDate       = $txtModifDate;
$createMatr      = $txtCreateurMatr;
$cp_id           = $s_txtCP;
$statut_traitmt  = $slctTraitStatut;
$type_slt        = $slctType;
$statut_trtt_cor = $slctTraitStatutCor;

$nc_motif = $motif_fnc;

?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>Aper&ccedil;u d'une fiche</title>

      <link rel="stylesheet" type="text/css" href="../css/FNCConsulter.css" />
      <link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
      <link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
      <link type="text/css" href="../css/FNCModif.css" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />

      <script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
      <script type="text/javascript" src="../js/thickbox.js"></script>
      <script type="text/javascript" src="../js/ui.core.js"></script>
      <script type="text/javascript" src="../js/ui.draggable.js"></script>
      <script type="text/javascript" src="../js/ui.datepicker.js"></script>
      <script type="text/javascript" src="../js/ui.dialog.js"></script>
      <script type="text/javascript" src="../js/FNCAjout.js"></script>
      <script type="text/javascript" src="../js/FNCModif.js"></script>
      <script type="text/javascript">
      function addAction()
      {
        // tb_show('','FNCAssignationCorreciteViaSearch.php');
        tb_show('','FNCAssignationCorreciteViaSearch.php?height=550&width=850');
      }



         $(function(){

              $("#txtDate").datepicker();
              $("#txtDateRep").datepicker();
              $(".classs").datepicker({ minDate: 0 });
             // charger();

              $("#slctGravite").change(function() {

                    var grv = $("#slctGravite").val() ;
                    var frq = $("#slctFrequence").val() ;
                    //alert(grv);
                    $.post(
                       "modif_criticite.php",
                       {
                          grav_id : grv,
                          freq_id : frq
                       },
                       function(_result){
                          //alert(_result);
                       var i = _result ;
                       //alert(i);
                       if(i == 'mineure')
                          $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
                       else if(i == 'Majeure')
                          $("#critq").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
                       else //if(i == 'Critique')
                          $("#critq").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );/*
                       else
                          $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                          $("#critq").html(_result);

                       }
                    );
                 });



               $("#slctFrequence").change(function() {
                    var frq = $("#slctFrequence").val() ;
                    var grv = $("#slctGravite").val() ;
                    //alert(grv);
                    $.post(
                       "modif_criticite.php",
                       {
                          grav_id : grv,
                          freq_id : frq
                       },
                       function(_res){
                       //alert(_res);
                          var i = _res ;
                          //alert(i);
                          if(i == 'mineure')
                             $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#000000"},{"font-weight":"bold" } );
                       else if(i == 'Majeure')
                          $("#critq").css ( { "backgroundColor":"#FB9228"},{"font-weight":"bold"} );
                       else
                          $("#critq").css ( { "backgroundColor":"#FA1D05"},{"color":"#FFFFFF"},{"font-weight":"bold" } );
                       /*else
                          $("#critq").css ( { "backgroundColor":"yellow"},{"color":"#FFFFFF"},{"font-weight":"bold" } );*/

                          $("#critq").html(_res);


                       }
                    );

               });


         });

    function verifyBeforeSubmit(){

           var idC = $("#hideCorrective").val();

           if(idC != undefined )
           {
                      idCorr = 'id_'+idC;
                      var idCorr = 'id_'+idC;

                  if(idC.length > 3 )
                  {
                      var myarr = idC.split(";");
                      var l_myarr = myarr.length;


                      var flag = 0;
                      var stock = "0";

                         for(i=1 ;i < l_myarr;i++)
                         {
                             idTmp = myarr[i];
                             if (document.getElementById(idTmp).checked) {
                               var my_id = idTmp.split("_");
                               stock +=","+my_id[1];
                               flag = 1;

                              }
                         }

                        if(flag == 1)
                        {
                           // $.post("FNCDeleteCorrective.php",
                               // {
                                  // list_id : stock
                               // },
                               // function(_result){ }
                            // );


                               $.ajax({
                                     type: "POST",
                                     url: "FNCDeleteCorrective.php",
                                     data: {
                                               list_id : stock

                                           },
                                     success: function(rslt){},
                                     async: false
                               });


                        }
                  }
           }

            var id_fnc = $("#txtIdFnc").val();
            var lib_bu_opt_ = $("#lib_bu_opt").val();

               // alert(id_fnc+'x'+lib_bu_opt_);
               $.ajax({
                           type: "POST",
                           url: "FNC_up_bu.php",
                           data: {
                                     id_fnc : id_fnc,
                                     lib_bu_opt : lib_bu_opt_

                                 },
                           success: function(rslt){},
                           async: false
                     });


            $("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

            $("#hdnSubmit").val("submit");

            var slctGravite = $("#slctGravite").val();
            var slctFrequence = $("#slctFrequence").val();

           var slctType = $(".slct_grav");
           var bError = 0;

           $.each(slctType,function()
           {
                // console.log("xxx:"+ $(this).val() );
               if( $(this).val() =='' )
               {
                   $(this).addClass('error'); bError=1;
               }else{
                 $(this).removeClass('error');

               }

          });

          if( bError==1 ){ return false; }

            if (($("#slctCause1").val() == "") || ($("#txtCause2").val() == "")) {

                   $("div.dialog").removeAttr("title") ;
                   $("div.dialog").attr({title: "Champ vide"}) ;
                   $("p.pMessage").html("Les champs suivis d'une \351toile sont obligatoires!") ;
                   $("div.dialog").dialog({
                      modal: true,
                      buttons: {
                         Fermer: function() {

                              myReload();
                         }
                      }
                   });

            }else {

                // alert('c');
                 $("#frmModif").removeAttr("action");
                 $("#frmModif").attr({
                    action: ""
                 });

                 // alert('xxx');
                 // return 0;
                $("#frmModif").submit();

            }

                var id_fnc = $("#txtIdFnc").val();

               var lib_bu_opt_ = $("#lib_bu_opt").val();
               // alert(id_fnc+'x'+lib_bu_opt_);
                      $.ajax({
                           type: "POST",
                           url: "FNC_up_bu.php",
                           data: {
                                     id_fnc : id_fnc,
                                     lib_bu_opt : lib_bu_opt_

                                 },
                           success: function(rslt){},
                           async: false
                     });
    }


      </script>
      <style>
         .slct_grav {
            width : 175px;
         }
     .error
     {
       border-color:red;
     }
      </style>

   </head>

   <body>
   <?php
// print_r($_REQUEST);
/* ********************* recuperation de toutes les donnees dans la table nc_fiche ******************** **/

if (isset($iId)) {
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
                        FNC_ID  =   " . $iId;

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

$zSqlFNC = "SELECT * FROM nc_fiche WHERE fnc_id = '$iId' ";
$oReqFNC = @pg_query($conn, $zSqlFNC);
$iNbFNC  = @pg_num_rows($oReqFNC);
$oFNC    = @pg_fetch_array($oReqFNC, 0);

// print_r($oFNC);

$sCause1   = $sCause2   = $sCause3   = "";
$aCause    = explode("*#|#*", $oFNC['fnc_cause']);
$sqlCause1 = "SELECT libelle FROM nc_cause WHERE id = '{$aCause[0]}';";
$resCause1 = @pg_query($conn, $sqlCause1);
$aCause1   = @pg_fetch_array($resCause1, 0);

$sCause1 = $aCause1['libelle'];
$sCause2 = $aCause[1];
$sCause3 = $aCause[2];

/* ******************** récupération de tous les motifs apparition dans la table nc_motif *************** **/
$sSqlMotifAppar = "  SELECT
                             *
                           FROM
                              nc_motif
                           INNER JOIN
                              nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id
                           WHERE type_motif = 'Apparition' AND nc_fiche.fnc_id = '{$iId}' ";

$rQueryMAppar = @pg_query($conn, $sSqlMotifAppar);
$iNbAppar     = @pg_num_rows($rQueryMAppar);

/* ******************** récupération de tous les motifs détection dans la table nc_motif *************** **/
$sSqlMotifDetect = "   SELECT
                              *
                             FROM
                              nc_motif
                             INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id
                             WHERE type_motif = 'Détection' AND nc_fiche.fnc_id = '{$iId}' ";
$rQueryMDetect = @pg_query($conn, $sSqlMotifDetect);
$iNbDetect     = @pg_num_rows($rQueryMDetect);

/* ******************** récupération de tous les motifs organisation dans la table nc_motif *************** **/
$sSqlMotifOrganis = "SELECT * FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Organisation' AND nc_fiche.fnc_id = '{$iId}' ";
$rQueryMOrganis   = @pg_query($conn, $sSqlMotifOrganis);
$iNbOrganis       = @pg_num_rows($rQueryMOrganis);

/* **************************** recuperation de toutes les actions curatives ************************* **/
$zSqlActionCurative = "    SELECT *
                                 FROM
                                    nc_action INNER JOIN nc_fiche ON nc_action.\"action_fncId\" = nc_fiche.fnc_id
                                 WHERE
                                 nc_fiche.fnc_id = '$iId'
                                 AND nc_action.action_type = 'curative'
                                 ORDER BY nc_action.\"action_debDate\" ";

$oReqActionCurative = @pg_query($conn, $zSqlActionCurative);
$iNbActionCurative  = @pg_num_rows($oReqActionCurative);

/* ************************* recuperation de toutes les actions non curatives ************************* **/

$zSqlActionNonCurative = "   SELECT
                                      nc_fnc_infos.id as info_id,
                                      nc_action_liste.id as liste_id,
                                      nc_fiche.fnc_id,
                                      nc_fnc_action.id as id_action,
                                      date_debut,
                                      libelle,
                                      responsable,
                                      date_fin,
                                      etat,\"fnc_actionNCStatut\"
                                   FROM
                                      nc_fnc_action, nc_fiche, nc_action_liste, nc_fnc_infos
                                   WHERE
                                      nc_fnc_infos.id = nc_fnc_action.fnc_info_id
                                   AND nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
                                   AND nc_fiche.fnc_id = '{$iId}'
                                   AND nc_fnc_action.action_liste_id = nc_action_liste.id
                                   AND nc_action_liste.type != 'curative'
                                   ORDER BY nc_fnc_infos.date_debut  ";

$oReqActionNonCurative = @pg_query($conn, $zSqlActionNonCurative);
$iNbActionNonCurative  = @pg_num_rows($oReqActionNonCurative);

/* **************************************** pilote de l'action *************************************** **/
$zSqlPiloteAction = "  SELECT
                              action_pilote
                             FROM
                              nc_action, nc_fiche
                             WHERE
                              nc_action.\"action_fncId\" = nc_fiche.fnc_id
                             AND nc_fiche.fnc_id = '$iId'";

$oReqPiloteAction = @pg_query($conn, $zSqlPiloteAction);
$toPiloteAction   = @pg_fetch_array($oReqPiloteAction);

/* ************************************ cnom du createur de la FNC ************************************ **/
$sqlCreateur = "SELECT prenompersonnel FROM personnel WHERE matricule = " . $oFNC['fnc_createur'];
$resCreateur = @pg_query($conn, $sqlCreateur);
$oCreateur   = @pg_fetch_array($resCreateur);

/* ************************************* nom du CP de la FNC ****************************************** **/
$sqlNomCP = "SELECT prenompersonnel FROM personnel WHERE matricule = " . $oFNC['fnc_cp'];

$rQueryNomCp = @pg_query($conn, $sqlNomCP);

$oCP = @pg_fetch_array($rQueryNomCp);

/* ************************************** nom du pilote de l'action *********************************** **/
$sqlPilote = "SELECT prenompersonnel FROM personnel WHERE matricule = " . $toPiloteAction['action_pilote'];
$resPilote = @pg_query($conn, $sqlPilote);
$oPilote   = @pg_fetch_array($resPilote);

$sqlImputationFNC = "SELECT imputation_id, imputation_libelle FROM nc_imputation WHERE  imputation_id = '" . $oFNC['fnc_imputation'] . "' ";
$resImputation    = @pg_query($conn, $sqlImputationFNC);
$oImputationFNC   = @pg_fetch_array($resImputation);

$sqlImputationTout = "SELECT imputation_id, imputation_libelle FROM nc_imputation WHERE imputation_actif IS NULL ORDER BY imputation_libelle ASC";
$oImputationTout   = @pg_query($conn, $sqlImputationTout);

$nTest = 0;
// print_r($oFNC['fnc_typologie']);
$nTest = $oFNC['fnc_typologie'];
/* if(isset($oFNC['fnc_typologie']) && $nTest > 0 )
{
$sqlTypologie = "SELECT typologie_id, typologie_libelle FROM nc_typologie WHERE typologie_id = '".$nTest."'  order by typologie_libelle" ;
}
else
{*/
// echo '*********</br>';
$sqlTypologie = "SELECT typologie_id, typologie_libelle FROM nc_typologie order by typologie_libelle";
// echo '*********</br>';
// }

$resTypologie = @pg_query($conn, $sqlTypologie);
$oTypologie   = @pg_fetch_array($resTypologie);

/* ************************************ verrouillage des champs ************************************* **/
$COQUAL = findGroup($conn);

/********   GET BU*********/
$code_fnc = trim($oFNC['fnc_code']);

$sql_find_bu_claus = " buss_u.id_bu = res_code3.fnc_bu ";

if ($code_fnc != '0VVT001') {
    $sql_find_bu_claus = "buss_u.id_bu = gua.id_bu";
}
// A la demande du chef projet qualite Mle 606 semaine 24 2016
if ($code_fnc == 'QUAL') {

    $sql_find_bu = "
                    SELECT
                        DISTINCT
                        RES_CODE3.FNC_CODE
                    ,   RES_CODE3.CODE3
                    ,   BUSS_U.LIB_BU
                    FROM
                        (
                            SELECT DISTINCT
                                FNC_CODE
                            ,   FNC_BU
                            ,   CASE
                                    WHEN
                                        CHAR_LENGTH(FNC_CODE)   >   6
                                    THEN
                                        SUBSTR(FNC_CODE, 2, 3)
                                    ELSE
                                        SUBSTR(FNC_CODE, 0, 4)
                                END         AS  CODE3
                            FROM
                                NC_FICHE
                            WHERE
                                FNC_CODE    !=  ''
                            AND FNC_ID      =   '" . $iId . "'
                            ORDER BY
                                CODE3   ASC
                        )   AS  RES_CODE3
                    LEFT JOIN
                        BUSINESS_UNIT   BUSS_U
                    ON
                        (
                            BUSS_U.ID_BU    =   RES_CODE3.FNC_BU
                        )
                    WHERE
                        FNC_CODE    =   '" . $code_fnc . "'
                    ORDER BY
                        RES_CODE3.CODE3 ASC
                    LIMIT 1";

    // echo '<pre>';
    //   print_r($sql_find_bu);
    // echo '</pre>';

} else {

    $sql_find_bu = "
                    SELECT DISTINCT
                        RES_CODE3.FNC_CODE
                    ,   RES_CODE3.CODE3
                    ,   BUSS_U.LIB_BU
                    FROM
                        (
                            SELECT
                                DISTINCT
                                FNC_CODE
                            ,   FNC_BU
                            ,   CASE
                                    WHEN
                                        CHAR_LENGTH(FNC_CODE)   >   6
                                    THEN
                                        SUBSTR(FNC_CODE, 2, 3)
                                    ELSE
                                        SUBSTR(FNC_CODE, 0, 4)
                                END         AS  CODE3
                            FROM
                                NC_FICHE
                            WHERE
                                FNC_CODE    !=  ''
                            AND FNC_ID      =   '" . $iId . "'
                            ORDER BY
                                CODE3   ASC
                        )   AS  RES_CODE3
                    LEFT JOIN
                        GU_APPLICATION  GUA
                    ON
                        GUA.CODE    =   RES_CODE3.CODE3
                    LEFT JOIN
                        BUSINESS_UNIT   BUSS_U
                    ON
                        (" . $sql_find_bu_claus . ")
                    WHERE
                        FNC_CODE    =   '" . $code_fnc . "'
                    ORDER BY
                        RES_CODE3.CODE3 ASC
                    LIMIT 1";

    // echo '<pre>';
    //   print_r($sql_find_bu);
    // echo '</pre>';

}

$query_sql_find_bu = @pg_query($conn, $sql_find_bu);
$nb_row_bu         = @pg_num_rows($query_sql_find_bu);
$res_f_bu          = @pg_fetch_array($query_sql_find_bu);
$arr_sql_f_bu      = $res_f_bu['lib_bu'];

$all_bu       = "SELECT distinct lib_bu  FROM business_unit order by lib_bu asc";
$query_all_bu = @pg_query($conn, $all_bu);
/* $res_all_bu = @pg_fetch_array($query_all_bu);*/

$disable_etat = " style='display:none;";
$etat__       = '';
if (in_array($_SESSION['matricule'], $array_admin_bu)) {
    $disabled     = '';
    $disable_etat = "";
    $etat__       = 'ETAT';
}

$option_bu            = "";
echo $option_bu_input = "<input type ='hidden' value='" . $arr_sql_f_bu . "' id='hidden_bu'>";
echo $option_bu_input = "<input type ='hidden' value='" . $code_fnc . "' id='hidden_fnc_code'>";

$option_bu .= "<select " . $disabled . " id='lib_bu_opt' onchange ='check_bu();'>";
$option_bu_sele = "";
$option_bu .= "<option value='' selected>Aucun</option>";

/*print_r($_REQUEST);*/
// print_r($arr_sql_f_bu);
while ($row_res_all_bu = pg_fetch_array($query_all_bu)) {

    $row_all_bu_ = $row_res_all_bu['lib_bu'];
    if ($arr_sql_f_bu == $row_all_bu_) {
        $option_bu .= "<option value='" . $row_all_bu_ . "' selected>" . $row_all_bu_ . "</option>";
    } else {
        $option_bu .= "<option value='" . $row_all_bu_ . "'>" . $row_all_bu_ . "</option>";
    }

}
$option_bu .= "</select>";
/********** GET BU Mahefarivo***************/

?>
  <div style="height:650px; overflow-y : scroll;">
   <form id="frmModif" name="frmModif" method="post" action="" >
      <fieldset>
         <legend>Modification de la FNC</legend>

         <p>
            Modification de la fiche ayant la r&eacute;f&eacute;rence <?php echo $zRef; ?><br />
         </p>

         <table width="100%" cellspacing="0" cellpadding="5" border="1">
            <input type="hidden" id="txtIdFnc" name="txtIdFnc" value="<?php echo $iId; ?>" >
            <input type="hidden" id="txtRefFnc" name="txtRefFnc" value="<?php echo $zRef; ?>" >
            <tr>
               <td width="13%" rowspan="2" class="class1">&nbsp;</td>
               <td width="21%" class="class2">CLIENT</td>
               <td class="class2" colspan="2">
               &nbsp;<?php echo $oFNC['fnc_client']; ?>
               </td>
            </tr>
            <tr>
               <td class="class2">CODE</td>
               <td class="class2" colspan="2">
               &nbsp;<?php echo $oFNC['fnc_code']; ?>
               </td>
            </tr>

            <tr >
               <td rowspan="8" class="class1">NON CONFORMITE</td>
               <td class="class3">CP / CPM</td>
               <td class="class3" colspan="2">
               <?php
if ($oFNC['fnc_statut'] != "bouclé") {

    echo "Matricule : <input type=\"text\" id=\"txtCP\" name=\"txtCP\" value=\"" . $oFNC['fnc_cp'] . "\" class=\"txtInput\" onkeypress=\"return filtrerTouche(event)\" >
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pr&eacute;nom : <input type=\"text\" class=\"txtInput\" id=\"txtPrenom\" name=\"txtPrenom\" value=\"" . $oCP['prenompersonnel'] . "\" onfocus=\"remplirPrenom()\" />";
    $bu_a_modif = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    $bu_a_modif .= "BU ";
    $bu_a_modif .= $option_bu;

    echo $bu_a_modif;
} else {

    echo "Matricule : " . $oFNC['fnc_cp'] . "
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pr&eacute;nom : " . $oCP['prenompersonnel'];
}
?>
               </td>
            </tr>
            <tr>
               <td class="class3">COMM</td>
               <td class="class3" colspan="3">
               <?php
if ($oFNC['fnc_statut'] != "bouclé") {
    echo "<select id = \"txtComm\" name = \"txtComm\" class = \"slct\">";
    if (empty($oFNC['fnc_comm'])) {
        echo "<option value = \"\" selected>***** Fa&icirc;tes votre choix *****</option>";

        $txtComm    = $oFNC['fnc_comm'];
        $sqlSltComm = "SELECT comm_libelle FROM nc_comm ORDER BY comm_libelle ASC ; ";
        $zSqlComm   = @pg_query($conn, $sqlSltComm);
        for ($i = 0; $i < @pg_num_rows($zSqlComm); $i++) {
            $toData    = @pg_fetch_array($zSqlComm, $i);
            $zValue    = $toData['comm_libelle'];
            $zSelected = ""; // intialisation de la variable de selection
            if (isset($txtComm) && $zValue == $txtComm) {
                $zSelected = "selected";
            }

            echo "<option value=\"" . $zValue . "\" $zSelected>" . $zValue . "</option>";
        }
    } else {
        $txtComm   = $oFNC['fnc_comm'];
        $sqlStComm = "SELECT comm_libelle FROM nc_comm ORDER BY comm_libelle ASC ; ";
        $zSqlComm  = @pg_query($conn, $sqlStComm);
        $iNumComm  = @pg_num_rows($zSqlComm);
        for ($i = 0; $i < $iNumComm; $i++) {
            $toData    = @pg_fetch_array($zSqlComm, $i);
            $zValue    = $toData['comm_libelle'];
            $zSelected = ""; // intialisation de la variable de selection
            if (isset($txtComm) && $zValue == $txtComm) {
                $zSelected = "selected";
            }

            echo "<option value=\"" . $zValue . "\" $zSelected>" . $zValue . "</option>";
        }
    }
    echo "</select>";
} else {
    echo $oFNC['fnc_comm'];
}
?>
               </td>
            </tr>
            <tr>
               <td class="class3">DATE DE CREATION FNC </td>
               <td class="class3" colspan="2">
                 <?php echo $oFNC['fnc_creationDate']; ?>
               </td>
            </tr>
            <tr>
               <td class="class3">TYPE</td>
               <td class="class3" colspan="2">
               <input type = "text" value = "<?php echo $oFNC['fnc_type']; ?>" name = "slctTypeFNC" readonly style="border: none; color: black; font-size: 14px" />
               </td>
            </tr>
            <tr>
               <td class="class3">MOTIF</td>
               <td class="class3" colspan="2">
                  <?php

/*
Array ( [txtId] => 10531 [txtRef] => NC_INTERNEZZS_161114 [txtNameClient] => TEST SI [txtCrationDate] => 2016-11-14 [txtModifDate] => 2016-11-14 [txtCreateurMatr] => 9420 [motif_fnc] => [PHPSESSID] => nil99ick6n7trs8sjcdnjhicj5 )
$oFNC['fnc_motif']
 */
// print_r($oFNC);
// echo $_REQUEST['txtAreaMotif'].'--</br>';
// echo $txtAreaMotif = $oFNC['fnc_motif'];

$txtAreaMotif = $_REQUEST['txtAreaMotif'];

/*elseif($_REQUEST['txtAreaMotif'] == '' &&  $oFNC['fnc_motif'] !='' && $_REQUEST['deb'] != '0')
$txtAreaMotif =  '&nbsp;';
elseif($_REQUEST['txtAreaMotif'] == '' &&  $oFNC['fnc_motif'] !='' && $_REQUEST['deb'] == '0')
$txtAreaMotif =  $_REQUEST['txtAreaMotif'];
elseif($_REQUEST['txtAreaMotif'] == '' &&  $oFNC['fnc_motif'] =='')
$txtAreaMotif = '&nbsp;';*/
// print_r($_REQUEST);
// if($_REQUEST['deb'] == '0' && $oFNC['fnc_motif'] !='' )
$txtAreaMotif = $oFNC['fnc_motif'];

if ($oFNC['fnc_statut'] != "bouclé") {
    echo "<textarea id=\"txtAreaMotif\" name=\"txtAreaMotif\" class=\"txtAreaa\" >" . $txtAreaMotif . "</textarea>";
} else {
    echo $txtAreaMotif;
}
?>
               </td>
            </tr>
            <tr>
               <td class="class3">EXIGENCES DU CLIENT</td>
               <td class="class3" colspan="2">
               <?php

$txtAreaExegence = $oFNC['fnc_exigence'];

if ($oFNC['fnc_statut'] != "bouclé") {
    echo "<textarea id=\"txtAreaExegence\" name=\"txtAreaExegence\" class=\"txtAreaa\" >" . $txtAreaExegence . "</textarea>";
} else {
    echo $txtAreaExegence;
}
?>
               </td>
            </tr>

            <tr>
               <td class="class3">CRITICITE</td>

                  <?php

// if ($_SESSION['matricule'] == 7530 || $_SESSION['matricule'] == 6548)

//$_SESSION['matricule'] == $txtCreateurMatr

if (in_array($_SESSION['matricule'], $array_admin_CQ)) {

    echo "<td class=\"class3\">";
    echo "Gravit&eacute; : <select id=\"slctGravite\" name=\"slctGravite\" class=\"slct_grav\"> ";

    if (empty($oFNC['fnc_gravite_id'])) {

        echo "<option value = \"\" selected>***** Fa&icirc;tes votre choix *****</option>";

        $sltGravite = $oFNC['fnc_gravite_id'];

        $sqlGravite = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav ";
        $resGravite = pg_query($conn, $sqlGravite) or die(pg_last_error($conn));

        $iNumGrav = @pg_num_rows($resGravite);
        for ($i = 0; $i < $iNumGrav; $i++) {
            $toGravite   = @pg_fetch_array($resGravite, $i);
            $cat_grav_id = $toGravite['id_categorie_grav'];
            $ech_grav    = $toGravite['echelle_id_grav'];
            $lib_grav    = $toGravite['libelle_gravite'];
            $zSelected   = ""; // intialisation de la variable de selection
            // if(isset($sltGravite) && $cat_grav_id == $sltGravite) $zSelected = "selected" ;
            echo "<option value='{$cat_grav_id}' $zSelected>" . $ech_grav . "_" . $lib_grav . "</option>";
        }

    } else {
        $sltGravite = $oFNC['fnc_gravite_id'];

        if ($_REQUEST['slctGravite']) {
            $sltGravite = $_REQUEST['slctGravite'];
        }

        $sqlGravite = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav ";
        $resGravite = pg_query($conn, $sqlGravite) or die(pg_last_error($conn));

        $iNumGrav = @pg_num_rows($resGravite);
        for ($i = 0; $i < $iNumGrav; $i++) {
            $toGravite   = @pg_fetch_array($resGravite, $i);
            $cat_grav_id = $toGravite['id_categorie_grav'];
            $ech_grav    = $toGravite['echelle_id_grav'];
            $lib_grav    = $toGravite['libelle_gravite'];
            $zSelected   = ""; // intialisation de la variable de selection
            if (isset($sltGravite) && $cat_grav_id == $sltGravite) {
                $zSelected = "selected";
            }

            echo "<option value='{$cat_grav_id}' $zSelected>" . $ech_grav . "_" . $lib_grav . "</option>";
        }

    }

    echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    echo "Fr&eacute;quence : <select id=\"slctFrequence\" name=\"slctFrequence\" class=\"slct_grav\"> ";

    if (empty($oFNC['fnc_frequence_id'])) {
        echo "<option value = \"\" selected>***** Fa&icirc;tes votre choix *****</option>";

        $sltFrequence = $oFNC['fnc_frequence_id'];

        $sqlFrequence = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq ";
        $resFrequence = pg_query($conn, $sqlFrequence) or die(pg_last_error($conn));

        $iNumFreq = @pg_num_rows($resFrequence);
        for ($i = 0; $i < $iNumFreq; $i++) {
            $toFrequence = @pg_fetch_array($resFrequence, $i);
            $cat_freq_id = $toFrequence['id_categorie_freq'];
            $ech_freq    = $toFrequence['echelle_id_freq'];
            $lib_freq    = $toFrequence['libelle_frequence'];
            $zSelected   = ""; // intialisation de la variable de selection
            if (isset($sltFrequence) && $cat_freq_id == $sltFrequence) {
                $zSelected = "selected";
            }
            echo "<option value='{$cat_freq_id}'>" . $ech_freq . "_" . $lib_freq . "</option>";
        }
    } else {
        $sltFrequence = $oFNC['fnc_frequence_id'];

        if ($_REQUEST['slctFrequence']) {
            $sltFrequence = $_REQUEST['slctFrequence'];
        }

        $sqlFrequence = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq ";

        $resFrequence = pg_query($conn, $sqlFrequence) or die(pg_last_error($conn));

        $iNumFreq = @pg_num_rows($resFrequence);

        for ($j = 0; $j < $iNumFreq; $j++) {

            $toFrequence = @pg_fetch_array($resFrequence, $j);
            $cat_freq_id = $toFrequence['id_categorie_freq'];

            $ech_freq  = $toFrequence['echelle_id_freq'];
            $lib_freq  = $toFrequence['libelle_frequence'];
            $zSelected = ""; // intialisation de la variable de selection
            if (isset($sltFrequence) && $cat_freq_id == $sltFrequence) {
                $zSelected = "selected";
            }
            echo "<option value='$cat_freq_id' $zSelected>" . $ech_freq . "_" . $lib_freq . "</option>";
        }
    }

    echo "</select></td>";

    echo "<td id=\"critq\" width=\"150px;\"> </td>";

} else {

    /********************************************************************************/
    //
    //           Ajout par mle 9092 pour une lecture seule gravite et frequence
    //
    /*********************************************************************************/

    echo "<td class=\"class3\">";
    echo "Gravit&eacute; : <select id=\"slctGravite\" name=\"slctGravite\" class=\"slct_grav\" disabled> ";

    if (empty($oFNC['fnc_gravite_id'])) {

        echo "<option value = \"\" selected>***** Fa&icirc;tes votre choix *****</option>";

        $sltGravite = $oFNC['fnc_gravite_id'];

        $sqlGravite = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav ";
        $resGravite = pg_query($conn, $sqlGravite) or die(pg_last_error($conn));

        $iNumGrav = @pg_num_rows($resGravite);
        for ($i = 0; $i < $iNumGrav; $i++) {
            $toGravite   = @pg_fetch_array($resGravite, $i);
            $cat_grav_id = $toGravite['id_categorie_grav'];
            $ech_grav    = $toGravite['echelle_id_grav'];
            $lib_grav    = $toGravite['libelle_gravite'];
            $zSelected   = ""; // intialisation de la variable de selection
            // if(isset($sltGravite) && $cat_grav_id == $sltGravite) $zSelected = "selected" ;
            echo "<option value='{$cat_grav_id}' $zSelected>" . $ech_grav . "_" . $lib_grav . "</option>";
        }

    } else {
        $sltGravite = $oFNC['fnc_gravite_id'];

        if ($_REQUEST['slctGravite']) {
            $sltGravite = $_REQUEST['slctGravite'];
        }

        $sqlGravite = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav ";
        $resGravite = pg_query($conn, $sqlGravite) or die(pg_last_error($conn));

        $iNumGrav = @pg_num_rows($resGravite);
        for ($i = 0; $i < $iNumGrav; $i++) {
            $toGravite   = @pg_fetch_array($resGravite, $i);
            $cat_grav_id = $toGravite['id_categorie_grav'];
            $ech_grav    = $toGravite['echelle_id_grav'];
            $lib_grav    = $toGravite['libelle_gravite'];
            $zSelected   = ""; // intialisation de la variable de selection
            if (isset($sltGravite) && $cat_grav_id == $sltGravite) {
                $zSelected = "selected";
            }

            echo "<option value='{$cat_grav_id}' $zSelected>" . $ech_grav . "_" . $lib_grav . "</option>";
        }

    }

    echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    echo "Fr&eacute;quence : <select id=\"slctFrequence\" name=\"slctFrequence\" class=\"slct_grav\" disabled> ";

    if (empty($oFNC['fnc_frequence_id'])) {
        echo "<option value = \"\" selected>***** Fa&icirc;tes votre choix *****</option>";

        $sltFrequence = $oFNC['fnc_frequence_id'];

        $sqlFrequence = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq ";
        $resFrequence = pg_query($conn, $sqlFrequence) or die(pg_last_error($conn));

        $iNumFreq = @pg_num_rows($resFrequence);
        for ($i = 0; $i < $iNumFreq; $i++) {
            $toFrequence = @pg_fetch_array($resFrequence, $i);
            $cat_freq_id = $toFrequence['id_categorie_freq'];
            $ech_freq    = $toFrequence['echelle_id_freq'];
            $lib_freq    = $toFrequence['libelle_frequence'];
            $zSelected   = ""; // intialisation de la variable de selection
            if (isset($sltFrequence) && $cat_freq_id == $sltFrequence) {
                $zSelected = "selected";
            }

            echo "<option value='{$cat_freq_id}'>" . $ech_freq . "_" . $lib_freq . "</option>";
        }
    } else {
        $sltFrequence = $oFNC['fnc_frequence_id'];
        if ($_REQUEST['slctFrequence']) {
            $sltFrequence = $_REQUEST['slctFrequence'];
        }

        $sqlFrequence = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq ";

        $resFrequence = pg_query($conn, $sqlFrequence) or die(pg_last_error($conn));

        $iNumFreq = @pg_num_rows($resFrequence);

        for ($j = 0; $j < $iNumFreq; $j++) {

            $toFrequence = @pg_fetch_array($resFrequence, $j);
            $cat_freq_id = $toFrequence['id_categorie_freq'];

            $ech_freq  = $toFrequence['echelle_id_freq'];
            $lib_freq  = $toFrequence['libelle_frequence'];
            $zSelected = ""; // intialisation de la variable de selection
            if (isset($sltFrequence) && $cat_freq_id == $sltFrequence) {
                $zSelected = "selected";
            }

            echo "<option value='$cat_freq_id' $zSelected>" . $ech_freq . "_" . $lib_freq . "</option>";
        }
    }

    echo "</select></td>";

    echo "<td id=\"critq\" width=\"150px;\"> </td>";

    /********************************************************************************/
    //
    //           fin Ajout par mle 9092 pour une lecture seule gravite et frequence
    //
    /*********************************************************************************/

}
?>

            </tr>
           <tr>
            <td ></td>
            <td>&nbsp;&nbsp;&nbsp;Type d'appel :&nbsp;&nbsp;&nbsp;<input style='background: rgb(237, 241, 243);' type='text' readonly value='<?php echo $typeApp; ?>'></td>
           </tr>
            <tr>
               <?php
if ($oFNC['fnc_type'] == "interne") {
    echo "<td rowspan=\"5\" class=\"class1\">ANALYSE</td>";
} else {
    echo "<td rowspan=\"5\" class=\"class1\">ANALYSE</td>";
}

?>
            </tr>
            <tr>
               <td class="class4">MOTIF APPARITION</td>
               <td class="class4" colspan="2">
                  <table width="100%"  cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
if ($iNbAppar == 0) {
    echo "&nbsp;";
} else {
    for ($i = 0; $i < $iNbAppar; $i++) {
        $toMotAppar = @pg_fetch_array($rQueryMAppar, $i);

        $vartxtMotifAppar = 'txtMotifAppar' . $i;
        $toLibelle        = $toMotAppar['libelle'];
        if ($_REQUEST[$vartxtMotifAppar]) {
            $toLibelle = $_REQUEST[$vartxtMotifAppar];
        }

        echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifAppar" . $i . "\" name = \"txtMotifAppar" . $i . "\" class = \"txtAreaa\">" . $toLibelle . "</textarea>
                                 </td>
                              </tr>";
    }
}
?>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class4">MOTIF NON DETECTION</td>
               <td class="class4" colspan="2">
                  <table width="100%"  cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
if ($iNbDetect == 0) {
    echo "&nbsp;";
} else {
    for ($i = 0; $i < $iNbDetect; $i++) {
        $toMotDetect       = @pg_fetch_array($rQueryMDetect, $i);
        $vartxtMotifDetect = 'txtMotifDetect' . $i;
        $toDetect          = $toMotDetect['libelle'];
        if ($_REQUEST[$vartxtMotifDetect]) {
            $toDetect = $_REQUEST[$vartxtMotifDetect];
        }

        echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifDetect" . $i . "\" name = \"txtMotifDetect" . $i . "\" class = \"txtAreaa\">" . $toDetect . "</textarea>
                                 </td>
                              </tr>";
    }
}
?>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class4">MOTIF ORGANISATION</td>
               <td class="class4" colspan="2">
                  <table width="100%" cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
if ($iNbOrganis == 0) {
    echo "&nbsp;";
} else {
    for ($i = 0; $i < $iNbOrganis; $i++) {
        $toMotOrganis    = @pg_fetch_array($rQueryMOrganis, $i);
        $vartoMotOrganis = 'txtMotifOrganis' . $i;
        $toOrganis       = $toMotOrganis['libelle'];
        if ($_REQUEST[$vartoMotOrganis]) {
            $toOrganis = $_REQUEST[$vartoMotOrganis];
        }

        echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifOrganis" . $i . "\" name = \"txtMotifOrganis" . $i . "\" class = \"txtAreaa\">" . $toOrganis . "</textarea>
                                 </td>
                              </tr>";
    }
}
?>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class4">IMPUTATION</td>
               <td class="class4" colspan="2">

                  <?php
// print_r($oTypologie);
$nbrQueryTypo = pg_num_rows($resTypologie);
// if ($COQUAL != 78){
//  echo $oImputationFNC['imputation_libelle'] ;
// if (isset($oFNC['fnc_typologie']) && $oFNC['fnc_typologie'] !='')
//  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Typologie : &nbsp;".$oTypologie['typologie_libelle'] ;
//  }
//  else{

$txtAreaImputation = $oImputationFNC['imputation_id'];
if ($_REQUEST['txtAreaImputation']) {
    $txtAreaImputation = $_REQUEST['txtAreaImputation'];
}

echo "<select id = \"txtAreaImputation\" name = \"txtAreaImputation\" class = \"slct\" >
                              <option value = \"\">***** fa&icirc;tes votre choix ***** </option>";

for ($i = 0; $i < @pg_num_rows($oImputationTout); $i++) {
    $toData    = @pg_fetch_array($oImputationTout, $i);
    $zValue    = $toData['imputation_id'];
    $zLabel    = $toData['imputation_libelle'];
    $zSelected = "";
    if ($zValue == $txtAreaImputation) {
        $zSelected = "selected";
    }

    echo "<option value=\"" . $zValue . "\"" . $zSelected . " >" . $zLabel . "</option>";
}
echo "</select>";
// nbrQueryTypo
//   }

/*************************************************/
//
//    Ajouter par mle 9092, pour que cette typologie soit vu que par la liste ci-dessous
//    $array_admin_CQ
//
/**************************************************/

$array_typologie = array(10, 7, 6, 11, 5, 21, 1, 8, 9, 4);

echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Typologie : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <select id=\"slctTypologie\" name=\"slctTypologie\" class=\"slct\"  style='width: 300px !important;'>
                            <option value = \"\">***** fa&icirc;tes votre choix ***** </option>
                           ";

$typo_oFNC = $oFNC['fnc_typologie'];
if ($_REQUEST['slctTypologie']) {
    $typo_oFNC = $_REQUEST['slctTypologie'];
}

for ($i = 0; $i < $nbrQueryTypo; $i++) {
    $toDataTypo = @pg_fetch_array($resTypologie, $i);
    echo $bData = $toDataTypo['typologie_libelle'];
    $bValue     = $toDataTypo['typologie_id'];

    if (!in_array($_SESSION['matricule'], $array_admin_CQ) && !in_array($bValue, $array_typologie)) {

        if ($bValue == $typo_oFNC) {
            echo "<option value='" . $bValue . "'  selected>" . $bData . "</option>";
        } else {
            echo "<option value='" . $bValue . "'>" . $bData . "</option>";
        }

    }

    if (in_array($_SESSION['matricule'], $array_admin_CQ)) {

        if ($bValue == $typo_oFNC) {
            echo "<option value='" . $bValue . "'  selected>" . $bData . "</option>";
        } else {
            echo "<option value='" . $bValue . "'>" . $bData . "</option>";
        }

    }

}
echo "</select>";

?>
               </td>
            </tr>

            <!--  Déplacement du champ actions  -->
            <tr>
               <td rowspan="4" class="class1">ACTIONS</td>
               <td class="class3">STATUT</td>
               <td class="class3" colspan="2">
                  <?php
/** ******************************************* statut ********************************************* **/
echo "<p style=\"font-weight: bold\">";
$zStatutFNC = $oFNC['fnc_statut'];
$zStatutAC  = $oFNC['fnc_actionCStatut'];
$zStatutANC = $oFNC['fnc_actionNCStatut'];
$valCur     = "";

$affCurative   = "";
$cur_Encours   = 0;
$cur_EnAttente = 0;
$cur_Ok        = 0;
$cur_cloturee  = 0;
$cur_boucle    = 0;

for ($i1 = 0; $i1 < $iNbActionCurative; $i1++) {
    $toActionCurative1 = @pg_fetch_array($oReqActionCurative, $i1);

    if ($toActionCurative1['action_etat'] == 'en cours') {
        $cur_Encours = 1;
        break;
    }

    if (($toActionCurative1['action_etat'] == 'en attente') || ($toActionCurative1['action_etat'] == '')) {
        $cur_EnAttente++;
    }

    if ($toActionCurative1['action_etat'] == 'ok') {
        $cur_Ok++;
    }

    if ($toActionCurative1['fnc_actionCStatut'] == 'bouclé') {
        $cur_boucle++;
    }
}

if ($cur_Encours >= 1) {
    $affCurative = 'actions en cours';
} else {
    if ($cur_EnAttente == $iNbActionCurative) {
        $affCurative = "actions non d&eacute;finies";
    } else {
        if ($cur_Ok == $iNbActionCurative) {
            $affCurative  = "actions en attente de validation";
            $cur_cloturee = 1;
        }
    }
}

if ($cur_boucle >= 1) {
    $affCurative = '';
}

echo $affCurative;
echo "</p>";

/*********************************************** fin statut ***********************************************/

/** ****************** modification du statut par la cloture des actions ou de la fnc ****************** **/
$AC  = split("_____", searchAction("curative", $iId));
$ANC = split("_____", searchAction_b("corrective / preventive", $iId));

if ((in_array($_SESSION['matricule'], $array_admin_CQ)) && ($zStatutFNC != "bouclé") && $cur_cloturee == 1) {
    if (($AC[1] == 0)) {

    } elseif (($AC[1] != 0) && ($ANC[1] == 0)) {
        echo "<p>";
        if ($AC[0] == 'ok') {
            echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option>
                                    </select>";
        } elseif ($AC[0] == 'ok' && ($zStatutAC == "bouclé")) {
            echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        } else {}
    } else {
        echo "<p>";

        if (($AC[0] == "ok") && ($ANC[0] == "ok")) {
            // toutes les actions sont ok
            if (($oFNC['fnc_actionCStatut'] != "bouclé") && ($oFNC['fnc_actionNCStatut'] != "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\" disabled='disabled'>
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option>
                                       <option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option>
                                       <option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option>
                                       </select>";
            } elseif (($oFNC['fnc_actionCStatut'] == "bouclé") && ($oFNC['fnc_actionNCStatut'] != "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } elseif (($oFNC['fnc_actionCStatut'] != "bouclé") && ($oFNC['fnc_actionNCStatut'] == "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                    <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                    <option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option>
                                    <option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option>
                                 </select>";
            } elseif (($oFNC['fnc_actionNCStatut'] == "bouclé") && ($oFNC['fnc_actionCStatut'] == "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } else {}
        } elseif (($AC[0] == "ok") && ($ANC[0] != "ok")) {
            // toutes les actions ne sont pas ok
            if (($oFNC['fnc_actionCStatut'] != "bouclé") && ($oFNC['fnc_actionNCStatut'] != "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <!--option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option-->
                                       <option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option>
                                       <!--option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option-->
                                       </select>";
            } elseif (($oFNC['fnc_actionCStatut'] == "bouclé") && ($oFNC['fnc_actionNCStatut'] != "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } else {

            }
        }
    }
}
echo "</p>";
/** ******************************************* fin menu cloture ***************************************** **/
?>
               </td>
            </tr>
            <tr>
               <td class="class3">SUIVI ACTIONS CURATIVES</td>
               <td class="class3" colspan="2">
                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                     <?php
if ($iNbActionCurative == 0) {
    echo "&nbsp;";
} else {
    ?>
                     <tr>
                        <td width="5%" class="class0">SUPPRIMER </td>
                          <td width="15%" class="class0">DATE DE BEBUT D'ACTIONS</td>
                          <td width="35%" class="class0">ACTIONS</td>
                          <td width="15%" class="class0">RESPONSABLE</td>
                          <td width="15%" class="class0">DATE FIN </td>
                          <td width="15%" class="class0">ETAT</td>
                     </tr>

                     <?php
$stockAction = "";
    for ($i = 0; $i < $iNbActionCurative; $i++) {
        $toActionCurative = @pg_fetch_array($oReqActionCurative, $i);

        if ($oFNC['fnc_actionCStatut'] != "bouclé") {
            $b_action_id = $toActionNonCurative['action_id'];
            $stockAction .= ";id_" . $toActionCurative['action_id'];
            echo "<tr>
                                    <td>
                                       <input type=\"checkbox\" class=\"class0\" name=\"id_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_id'] . "\">
                                    </td>
                                      <td class=\"class0\">
                                          <input type=\"text\" class=\"classs\" name=\"dateDebCur_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_debDate'] . "\" readonly=\"readonly\" />
                                      </td>
                                      <td class=\"class0\">
                                       <textarea class=\"classinput\" name=\"actDescCur_" . $toActionCurative['action_id'] . "\">" . $toActionCurative['action_description'] . "</textarea>
                                      </td>
                                      <td class=\"class0\">
                                       <input type=\"text\" class=\"classs1\" name=\"actRespCur_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_responsable'] . "\" />
                                      </td>
                                    <td class=\"class0\">
                                       <input type=\"text\" class=\"classs\" name=\"dateFinCur_" . $toActionCurative['action_id'] . "\" value=\"";
            if ($toActionCurative['action_finDate'] == '') {
                echo "";
            } else {
                echo $toActionCurative['action_finDate'];
            }

            echo "\" />
                                    </td>
                                     <td class=\"class0\">
                                          <select id=\"slctTraitStatutAction\" name=\"actEtatCur_" . $toActionCurative['action_id'] . "\" class=\"classslct\">";
            ?>
                                          <option value="en attente" <?php if ($toActionCurative['action_etat'] == "en attente") {
                echo "selected";
            }
            ?>>En attente</option>
                                          <option value="en cours" <?php if ($toActionCurative['action_etat'] == "en cours") {
                echo "selected";
            }
            ?>>En cours</option>
                                          <option value="ok" <?php if ($toActionCurative['action_etat'] == "ok") {
                echo "selected";
            }
            ?>>OK</option>
                                       </select>
                                     </td>
                                    </tr>
                        <?php
} else {
            echo "<tr>
                                 <td>
                                    <input type=\"checkbox\" class=\"class0\" name=\"id_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_id'] . "\" readonly=\"readonly\" >
                                 </td>
                                   <td class=\"class0\">
                                       <input type=\"text\" class=\"classslct\" name=\"dateDebCur_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_debDate'] . "\" readonly=\"readonly\" >
                                   </td>
                                   <td class=\"class0\">
                                    <textarea class=\"classinput\" name=\"actDescCur_" . $toActionCurative['action_id'] . "\" readonly=\"readonly\" >" . $toActionCurative['action_description'] . "</textarea>
                                   </td>
                                   <td class=\"class0\">
                                    <input type=\"text\" class=\"classs1\" name=\"actRespCur_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_responsable'] . "\" readonly=\"readonly\" >
                                   </td>
                                 <td class=\"class0\">
                                    <input type=\"text\" class=\"classslct\" name=\"dateFinCur_" . $toActionCurative['action_id'] . "\" value=\"" . $toActionCurative['action_finDate'] . "\"  readonly=\"readonly\" >
                                 </td>
                                  <td class=\"class0\">
                                       <input type=\"text\" id=\"slctTraitStatutAction\" name=\"actEtatCur_" . $toActionCurative['action_id'] . "\" class=\"classslct\" value=\"" . $toActionCurative['action_etat'] . "\" readonly=\"readonly\" >
                                  </td>
                                 </tr>";

        }
    }
    echo "<input type='hidden' value='" . $stockAction . "' id='hideCurative' />";
}
?>
                  </table>
                  <?php
if (isset($_REQUEST['curative'])) {

} elseif (isset($_REQUEST['corrective'])) {

} else {
    ?>
                     <div align="right">
                    <input type="button" id="btnAjoutActionC" name="btnAjoutActionC" value="Ajouter action" class = "ui-state-default" onClick="javascript: document.location.href = 'FNCMAJAction.php?txtId=<?php echo $iId; ?>&txtRef=<?php echo $zRef; ?>&txtC=C' ">
                  </div>

                  <?php
}

?>

               </td>
            </tr>

         <!-- ---------- Fin déplacement ------------------ -->
         <tr>
            <td class="class3">STATUT</td>
            <td class="class3" colspan="2">
               <?php

/** ******************************************* statut ********************************************* **/
echo "<p style=\"font-weight: bold\">";
$zStatutFNC    = $oFNC['fnc_statut'];
$zStatutAC     = $oFNC['fnc_actionCStatut'];
$zStatutANC    = $oFNC['fnc_actionNCStatut'];
$affCorrective = "";

$cor_EnCours   = 0;
$cor_cloturee  = 0;
$cor_EnAttente = 0;
$cor_Ok        = 0;
$cor_boucle    = 0;

for ($i1 = 0; $i1 < $iNbActionNonCurative; $i1++) {
    $toActionNonCurative1 = @pg_fetch_array($oReqActionNonCurative, $il);

    if ($toActionNonCurative1['etat'] == "en cours") {
        $cor_EnCours = 1;
        break;
    }

    if ($toActionNonCurative1['etat'] == "en attente" || $toActionNonCurative1['etat'] == '') {
        $cor_EnAttente++;
    }

    if ($toActionNonCurative1['etat'] == "ok") {
        $cor_Ok++;
    }

    if ($toActionNonCurative1['fnc_actionNCStatut'] == "bouclé") {
        $cor_boucle++;
    }
}

if ($cor_EnCours >= 1) {
    $affCorrective = 'actions en cours';
} else {
    if ($cor_EnAttente == $iNbActionNonCurative) {
        $affCorrective = 'actions non d&eacute;finies';
    } else {
        if ($cor_Ok == $iNbActionNonCurative) {
            $affCorrective = 'actions en attente de validation';
            $cor_cloturee  = 1;
        }
    }
}

if ($cor_boucle >= 1) {
    $affCorrective = '';
}

echo $affCorrective;
echo "</p>";

/*********************************************** fin statut ***********************************************/

/** ****************** modification du statut par la cloture des actions ou de la fnc ****************** **/
$AC2  = split("_____", searchAction("curative", $iId));
$ANC2 = split("_____", searchAction_b("corrective / preventive", $iId));

if ((in_array($_SESSION['matricule'], $array_admin_CQ)) && ($zStatutFNC != "bouclé") && $cor_cloturee == 1) {
    if (($AC[1] == 0) && ($ANC2[1] == 0)) {

    } elseif (($AC[1] == 0) && ($ANC2[1] != 0)) {
        echo "<p>";
        if ($ANC2[0] == 'ok') {
            echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option>
                                    </select>";
        } else {

        }
    } else {
        echo "<p>";

        //echo $AC;
        if (($AC2[0] == "ok") && ($ANC2[0] == "ok")) {
            // toutes les actions sont ok
            if (($oFNC['fnc_actionCStatut'] != "bouclé") && ($oFNC['fnc_actionNCStatut'] != "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <!--option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option-->
                                       <!--option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option-->
                                       <option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option>
                                       </select>";
            } elseif (($oFNC['fnc_actionNCStatut'] != "bouclé") && ($oFNC['fnc_actionCStatut'] == "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                          <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                          <option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option>
                                          <option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option>
                                       </select>";
            } elseif (($oFNC['fnc_actionNCStatut'] == "bouclé") && ($oFNC['fnc_actionCStatut'] != "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } elseif (($oFNC['fnc_actionNCStatut'] == "bouclé") && ($oFNC['fnc_actionCStatut'] == "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } else {

            }
        } elseif (($ANC2[0] == "ok") && ($AC2[0] != "ok")) {
            // toutes les actions ne sont pas ok
            if (($oFNC['fnc_actionNCStatut'] != "bouclé") && ($oFNC['fnc_actionCStatut'] != "bouclé")) {
                echo "Cl&ocirc;turer : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "  <select id = \"slctTraitStatut\" name = \"slctTraitStatut\" class = \"slct\" style = \"width: 260px\">
                                       <option value = \"\">********** fa&icirc;tes votre choix **********</option>
                                       <option value = \"boucléNC\">Cl&ocirc;turer les actions correctives</option>
                                       <!--option value = \"boucléC\">Cl&ocirc;turer les actions curatives</option-->
                                       <option value = \"boucléFNC\">Cl&ocirc;turer la fiche de non conformit&eacute;</option>
                                       </select>";

            } elseif (($oFNC['fnc_actionNCStatut'] == "bouclé") && ($oFNC['fnc_actionCStatut'] != "bouclé")) {
                echo "Cl&ocirc;tur&eacute;es &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            }

        } else {

        }
    }
}
echo "</p>";
/** ******************************************* fin menu cloture ***************************************** **/
?>
            </td>
         </tr>
         <tr>
            <td class="class3">ACTIONS CORRECTIVES</td>
            <td class="class3" colspan="2">
               <p>

                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                     <?php
if ($iNbActionNonCurative == 0) {
    /*  $ajoutAction ="";
echo $ajoutAction .="<span style='align:left;'><input  id= 'bActCorre' class='ui-state-default' type='button' value='Ajouter action'  onclick='jacascript: addAction()'/></span>";*/

} else {
    ?>
                         <tr>
                            <td width="5%" class="class0">SUPPRIMER </td>
                            <td width="12%" class="class0">DATE DE BEBUT D'ACTIONS </td>
                            <td width="35%" class="class0">ACTIONS</td>
                            <td width="12%" class="class0">RESPONSABLE</td>
                            <td width="12%" class="class0">DATE FIN </td>
                            <!--td width="12%" class="class0">INDICATEUR D'EFFICACITE</td-->
                            <td width="12%" class="class0"><?php echo $etat__; ?></td>
                            <td width="12%" class="class0"> </td>
                         </tr>
                     <?php

    $stock = "";
    for ($i = 0; $i < $iNbActionNonCurative; $i++) {

        $toActionNonCurative = @pg_fetch_array($oReqActionNonCurative, $i);

        $date_d             = $toActionNonCurative['date_debut'];
        $b_info_id          = $toActionNonCurative['info_id'];
        $aActionDesctiption = explode("*#|#*", $toActionNonCurative['libelle']);

        if ($oFNC['fnc_actionNCStatut'] != "bouclé") {

            $stock .= ";id_" . $b_info_id;
            echo "<tr>
                                                   <td>
                                                      <input id=\"id_" . $toActionNonCurative['info_id'] . "\" type=\"checkbox\" class=\"class0\" name=\"id_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['info_id'] . "\">

                                                   </td>
                                                     <td class=\"class0\">
                                                      <input type=\"text\" class=\"classs\" name=\"dateDebNCur_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['date_debut'] . "\" readonly=\"readonly\" />
                                                     </td>
                                                     <td class=\"class0\">
                                                      <textarea class=\"classinput\" name=\"actDescNCur_" . $i . "\" >{$aActionDesctiption[0]}</textarea>
                                                     </td>
                                                     <td class=\"class0\">
                                                      <input type=\"text\" class=\"classs1\" name=\"actRespNCur_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['responsable'] . "\" />
                                                     </td>
                                                   <td class=\"class0\">
                                                      <input type=\"text\" class=\"classs\" name=\"dateFinNCur_" . $toActionNonCurative['info_id'] . "\" value=\"";
            if ($toActionNonCurative['date_fin'] == '') {
                echo "";
            } else {
                echo $toActionNonCurative['date_fin'];
            }

            echo "\" /></td>

                                                   <!--td><input type='text' class='classs1' name='efficacityIndicator_{$toActionNonCurative['info_id']}' value='";
            if ($aActionDesctiption[1] == "") {
                echo "";
            } else {
                echo $aActionDesctiption[1];
            }

            echo "' /></td-->
                                <td class=\"class0\">";

            if (in_array($_SESSION['matricule'], $array_admin_CQ)) {

                /***********************************************************************************/
                //
                //  correction matricule 9092 : name
                //
                /************************************************************************************/

                $name_corrective = "name=\"actEtatNCur_" . $toActionNonCurative['info_id'] . "\"";

                ?>
                                   <select   id="slctTraitStatutAction" <?php echo $name_corrective; ?> class="classslct">
                                    <option value="en attente" <?php if ($toActionNonCurative['etat'] == "en attente") {
                    echo "selected";
                }
                ?> >En attente</option>
                                    <option value="en cours" <?php if ($toActionNonCurative['etat'] == "en cours") {
                    echo "selected";
                }
                ?> >En cours</option>
                                    <option value="ok" <?php if ($toActionNonCurative['etat'] == "ok") {
                    echo "selected";
                }
                ?> >OK</option>
                                   </select>
                                <?php
}
            ?>

                                    </td>
                                    <td class=\"class0\">
                                             <br/>
                                    </td>
                                 </tr>
                                 <?php
} else {
            echo "<tr>
                                    <td>
                                       <input type=\"checkbox\" class=\"class0\" name=\"id_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['info_id'] . "\" readonly=\"readonly\" >
                                    </td>
                                      <td class=\"class0\">
                                          <input type=\"text\" class=\"classslct\" name=\"dateDebNCur_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['date_debut'] . "\" readonly=\"readonly\" >
                                      </td>
                                      <td class=\"class0\">
                                       <textarea class=\"classinput\" name=\"actDescNCur_" . $i . "\" readonly=\"readonly\" >{$aActionDesctiption[0]}</textarea>
                                      </td>
                                      <td class=\"class0\">
                                       <input type=\"text\" class=\"classs1\" name=\"actRespNCur_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['responsable'] . "\" readonly=\"readonly\" >
                                      </td>
                                    <td class=\"class0\">
                                       <input type=\"text\" class=\"classslct\" name=\"dateFinNCur_" . $toActionNonCurative['info_id'] . "\" value=\"" . $toActionNonCurative['date_fin'] . "\"  readonly=\"readonly\" >
                                    </td>
                                    <!--td--><!--input type='text' class='classs1' name='efficacityIndicator_{$toActionNonCurative['info_id']}' value='{$aActionDesctiption[1]}' readonly='readonly' /--><!--/td-->
                                     <td class=\"class0\">
                                          <input type=\"text\" id=\"slctTraitStatutAction\" name=\"actEtatNCur_" . $toActionNonCurative['info_id'] . "\" class=\"classslct\" value=\"" . $toActionNonCurative['etat'] . "\" readonly=\"readonly\" >
                                     </td>
                                    </tr>";
        }
    }
    //$stock
    echo "<input type='hidden' value='" . $stock . "' id='hideCorrective' />";

}
?>
                  </table>
               <input type="hidden" id="txtNameClient" name="txtNameClient" value="<?php echo $slctClientName; ?>" />
               <input type="hidden" id="txtCode_cli" name="txtCode_cli" value="<?php echo $slctCode; ?>" />
               <input type="hidden" id="createDate" name="createDate" value="<?php echo $txtCrationDate; ?>" />
               <input type="hidden" id="modifDate" name="modifDate" value="<?php echo $txtModifDate; ?>" />
               <input type="hidden" id="createMatr" name="createMatr" value="<?php echo $txtCreateurMatr; ?>" />
               <input type="hidden" id="cp_id" name="cp_id" value="<?php echo $s_txtCP; ?>" />
               <input type="hidden" id="statut_traitmt" name="statut_traitmt" value="<?php echo $slctTraitStatut; ?>" />
               <input type="hidden" id="type_slt" name="type_slt" value="<?php echo $slctType; ?>" />
               <input type="hidden" id="statut_trtt_cor" name="statut_trtt_cor" value="<?php echo $slctTraitStatutCor; ?>" />
               <?php
if (isset($_REQUEST['curative'])) {

} elseif (isset($_REQUEST['corrective'])) {

} else {
    // echo getPersAccesNotation($_SESSION['matricule']);
    /*if ((in_array($_SESSION['matricule'], $array_admin_CQ)) && ($iQualite == '') || (isset($iQualite)) || $_SESSION['matricule'] == 5176 || $_SESSION['groupebd'] == 'coach' || $_SESSION['zFonction'] == 'MANAGER' || $_SESSION['groupebd'] == 'cmp' || $_SESSION['niveauacces'] >= 6 || $_SESSION['zFonction'] == 'RESP PLATEAU' || $_SESSION['zFonction'] == 'RP' || $_SESSION['zFonction'] == 'CFQ' || $_SESSION['zFonction'] == 'CHQ' || $_SESSION['zFonction'] == 'MUP' || $_SESSION['zFonction'] == 'SUP CC' )
    {*/
    ?>
                  <div align="right">
                    <input type="button" id="btnAjoutActionC" name="btnAjoutActionC" value="Ajouter action." class = "ui-state-default" onClick="javascript: document.location.href = 'FNCAssignActionCorrective.php?txtId=<?php echo $iId; ?>&txtRef=<?php echo $zRef; ?>&txtC=C&edit=on' ">
                  </div>
               <?php
//}

}
?>

            </td>

         </tr>
      </table>
</p>
<table width="100%"  border="0" cellspacing="1" cellpadding="1">
  <tr>

   <?php

if (isset($_REQUEST['curative'])) {
    ?>
         <td width="25%"><input type="button" id="btnRetour" name="btnRetour" value="Annuler" class = "ui-state-default" onclick="javascript : document.location.href ='FNCSuiviActionC.php' "></td>

      <?php
} elseif (isset($_REQUEST['corrective'])) {
    ?>
         <td width="25%"><input type="button" id="btnRetour" name="btnRetour" value="Annuler" class = "ui-state-default" onclick="javascript : document.location.href ='FNCSuiviActionNC.php' "></td>

      <?php
} else {

    ?>
         <td width="25%"><input type="button" id="btnRetour" name="btnRetour" value="Annuler" class = "ui-state-default" onclick="javascript : document.location.href ='FNCConsulter.php?idFNC=<?php echo $iId; ?>&txtRef=<?php echo $zRef; ?>&slctClientName=<?php echo $txtNameClient; ?>&txtCrationDate=<?php echo $createDate; ?>&txtModifDate=<?php echo $modifDate; ?>&txtCreateurMatr=<?php echo $createMatr; ?>&motif_fnc=<?php echo $nc_motif; ?>' "></td>
      <?php
}
?>

  <?php

/**********************************************************************************/
//
//  Commenter par le mle 9092
//
/***********************************************************************************/

//if(in_array( $_SESSION['matricule'], $array_admin_CQ )){

?>
      <td width="25%"><input type="button" onclick="verifyBeforeSubmit();" id="btnModifier" name="btnModifier" value="Modifier" class = "ui-state-default"></td>

  <?php
//}
?>

      <td width="25%">&nbsp;</td>
      <td>&nbsp;<input type="hidden" id="hdnSubmit" name="hdnSubmit" /></td>

  </tr>
</table>

</fieldset>

</form>

<?php

if (isset($_REQUEST['hdnSubmit'])) {

    /** ************************************ mise a jour des motifs apparitions *********************************** **/
    for ($i = 0; $i < $iNbAppar; $i++) {
        $toMotifAppar = @pg_fetch_array($rQueryMAppar, $i);
        $iIdMotAppar  = $toMotifAppar['id'];
        $motifAppar   = pg_escape_string($_REQUEST["txtMotifAppar" . $i . ""]);

        $sqlUpdateAppar = "UPDATE nc_motif SET libelle = '{$motifAppar}' WHERE id = '{$iIdMotAppar}' ";

        $resAppar = @pg_query($conn, $sqlUpdateAppar);
    }

    /** ************************************ mise a jour des motifs détections *********************************** **/
    for ($i = 0; $i < $iNbDetect; $i++) {
        $toMotifDetect = @pg_fetch_array($rQueryMDetect, $i);
        $iIdMotDetect  = $toMotifDetect['id'];
        $motifDetect   = pg_escape_string($_REQUEST["txtMotifDetect" . $i . ""]);

        $sqlUpdateDetect = "UPDATE nc_motif SET libelle = '{$motifDetect}' WHERE id = '{$iIdMotDetect}' ";

        $resDetect = @pg_query($conn, $sqlUpdateDetect);
    }

    /** ************************************ mise a jour des motifs organisations *********************************** **/
    for ($i = 0; $i < $iNbOrganis; $i++) {
        $toMotifOrganis = @pg_fetch_array($rQueryMOrganis, $i);
        $iIdMotOrganis  = $toMotifOrganis['id'];
        $motifOrganis   = pg_escape_string($_REQUEST["txtMotifOrganis" . $i . ""]);

        $sqlUpdateOrganis = "UPDATE nc_motif SET libelle = '{$motifOrganis}' WHERE id = '{$iIdMotOrganis}' ";

        $resOrganis = @pg_query($conn, $sqlUpdateOrganis);
    }

    /** ************************************* mise a jour des actions curatives ******************** **/
    for ($i = 0; $i < $iNbActionCurative; $i++) {
        $toActionCurative = @pg_fetch_array($oReqActionCurative, $i);

        $iIdActionCurative = $toActionCurative['action_id'];
        $actDateDebCur     = $_REQUEST["dateDebCur_$iIdActionCurative"];
        $actDescCur        = pg_escape_string($_REQUEST["actDescCur_$iIdActionCurative"]);
        $actRespCur        = pg_escape_string($_REQUEST["actRespCur_$iIdActionCurative"]);
        $actDateFinCur     = $_REQUEST["dateFinCur_$iIdActionCurative"];
        $actEtatCur        = $_REQUEST["actEtatCur_$iIdActionCurative"];

        if (isset($_REQUEST["id_$iIdActionCurative"])) {
            $sqlDelete = "DELETE from nc_action WHERE \"action_id\" = '$iIdActionCurative' ";

            $resDelete = @pg_query($conn, $sqlDelete);
        } else {

            // modifier par mle 9092

            if (($toActionCurative['action_debDate'] != $actDateDebCur) || ($toActionCurative['action_description'] != $actDescCur) || ($toActionCurative['action_responsable'] != $actRespCur) || ($toActionCurative['action_finDate'] != $actDateFinCur) || ($toActionCurative['action_etat'] != $actEtatCur)) {

                $sqlUpdateStatutC = "UPDATE nc_fiche SET \"fnc_actionCStatut\"='$actEtatCur' WHERE fnc_id='$iId'";

                $resUpdateStatutC = @pg_query($conn, $sqlUpdateStatutC);

                $zSqlUpdateActionC = "UPDATE nc_action SET ";
                if ($actDateDebCur != '') {
                    $zSqlUpdateActionC .= "\"action_debDate\" = '$actDateDebCur', ";
                }

                $zSqlUpdateActionC .= "action_description = '$actDescCur',  action_responsable = '$actRespCur', ";
                if ($actDateFinCur != '') {
                    $zSqlUpdateActionC .= " \"action_finDate\" = '$actDateFinCur', ";
                } else {
                    $zSqlUpdateActionC .= " \"action_finDate\" = null, ";
                }

                $zSqlUpdateActionC .= " action_etat = '$actEtatCur'  ";

                $zSqlUpdateActionC .= " WHERE \"action_fncId\" = '$iId' AND action_type = 'curative' AND action_id = '$iIdActionCurative' ";

                $oMAJActionC = @pg_query($conn, $zSqlUpdateActionC);

                if (!$oMAJActionC) {

                    $_SESSION["MSGSearch"] = "<font color=\"black\">Derni&egrave;re action :</font> modification de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> une erreur s'est survenue lors de la modification de l'action.";

                    echo "
                        <script type='text/javascript'>
                          $(function()
                          {
                            window.parent.$('#frmSearch, #frmAll').submit () ;
                          }) ;
                        </script>";
                }

            }

        }
    }

    /** ************************************** mise a jour des actions non curatives *************** **/
    for ($i = 0; $i < $iNbActionNonCurative; $i++) {
        $toActionNonCurative = @pg_fetch_array($oReqActionNonCurative, $i);

        $iIdActionNonCurative = $toActionNonCurative['info_id'];
        //echo $iIdActionNonCurative ;exit;
        $iIdListeNonCurative = $toActionNonCurative['liste_id'];
        $actDateDebNCur      = $_REQUEST["dateDebNCur_$iIdActionNonCurative"];
        $actDescNCur         = pg_escape_string($_REQUEST["actDescNCur_" . $i . ""]);
        $actRespNCur         = pg_escape_string($_REQUEST["actRespNCur_$iIdActionNonCurative"]);
        $actDateFinNCur      = $_REQUEST["dateFinNCur_$iIdActionNonCurative"];
        $actEtatNCur         = $_REQUEST["actEtatNCur_$iIdActionNonCurative"];

        $sFullDescription = $actDescNCur . "*#|#*" . $_REQUEST["efficacityIndicator_$iIdActionNonCurative"];

        if (isset($_REQUEST["id_$iIdActionNonCurative"])) {
            $sqlMajACt = "DELETE from nc_action WHERE \"action_id\" = '$iIdActionNonCurative' ; ";
            //echo $sqlMajACt;exit;
            $resDelACt = @pg_query($conn, $sqlMajACt) or die(pg_escape_string($conn));
        } else {

            //modifier par mle 9092
            if (($toActionNonCurative['date_debut'] != $actDateDebNCur) || ($toActionNonCurative["libelle"] != $sFullDescription) || ($toActionNonCurative['responsable'] != $actRespNCur) || ($toActionNonCurative['date_fin'] != $actDateFinNCur) || ($toActionNonCurative['etat'] != $actEtatNCur)) {

                $actEtatNCur = $actEtatNCur;

                $sqlUpdateStatutNC = "UPDATE nc_fiche SET \"fnc_actionNCStatut\"='$actEtatNCur' WHERE fnc_id='$iId'";
                $resMAJNC          = @pg_query($conn, $sqlUpdateStatutNC);

                // Mise à jour des tables
                $zSqlUpdateActionNC = "UPDATE nc_fnc_infos SET date_debut = '$actDateDebNCur', ";
                $zSqlUpdateActionNC .= "responsable = '$actRespNCur', date_fin = '$actDateFinNCur', ";
                $zSqlUpdateActionNC .= " etat = '$actEtatNCur' WHERE id = '$iIdActionNonCurative' ";

                $oMAJActionNC = @pg_query($conn, $zSqlUpdateActionNC);

                // Sélection de l'état de la fiche dans nc_fnc_infos
                $sqlSlctStateFiche = "SELECT
                                                  etat
                                               FROM nc_fnc_infos ni
                                               INNER JOIN nc_fnc_action na ON ni.id=na.fnc_info_id
                                               INNER JOIN nc_fiche nf ON na.fnc_id = nf.fnc_id
                                               WHERE nf.fnc_id='$iId' LIMIT 1
                                               ";

                $rQueryState = @pg_query($conn, $sqlSlctStateFiche);

                $aStateFiche = @pg_fetch_array($rQueryState, 0);

                $etatNC = $aStateFiche['etat'];

                $sqlUpdateStatutNC = "UPDATE nc_fiche SET \"fnc_actionNCStatut\"='$etatNC' WHERE fnc_id='$iId'";
                $resMAJNC          = @pg_query($conn, $sqlUpdateStatutNC);

                $zSqlUpdateActionNC2 = "UPDATE nc_action_liste SET ";
                $zSqlUpdateActionNC2 .= " libelle = '{$actDescNCur}' ";
                $zSqlUpdateActionNC2 .= " WHERE id = '$iIdListeNonCurative' ";
                //echo $zSqlUpdateActionNC2 ;exit;
                $oMAJActionNC2 = @pg_query($conn, $zSqlUpdateActionNC2);

                if (!$oMAJActionNC) {
                    $_SESSION["MSGSearch"] = "<font color=\"black\">Derni&egrave;re action :</font> modification de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> une erreur s'est survenue lors de la modification de l'action.";

                    echo "
                            <script type='text/javascript'>
                              $(function()
                              {
                                window.parent.$('#frmSearch, #frmAll').submit () ;
                              }) ;
                            </script>";
                }

            }

        }
    }

/** ******************************************** recuperation des variables ******************* */

    if ($oFNC['fnc_statut'] == "bouclé") {
        $iCP          = $oFNC['fnc_cp'];
        $zComm        = $oFNC['fnc_comm'];
        $zType        = $oFNC['fnc_type'];
        $zMotif       = pg_escape_string($oFNC['fnc_motif']);
        $zExigence    = pg_escape_string($oFNC['fnc_exigence']);
        $zTraitStatut = $oFNC['fnc_statut'];
        $zCause       = pg_escape_string($oFNC['fnc_cause']);
        $zImputation  = pg_escape_string($oImputationFNC['imputation_id']);

        $zDateRep        = $oFNC['fnc_reponseDate'];
        $zActionCStatut  = $oFNC['fnc_actionCStatut'];
        $zActionNCStatut = $oFNC['fnc_actionNCStatut'];

        $zRefRep       = pg_escape_string($oFNC['fnc_reponseRef']);
        $iPilote       = $toPiloteAction['action_pilote'];
        $zModificateur = $_SESSION["matricule"];
        $zModifDate    = date("Y-m-d");

        $zTypologie = $oTypologie['typologie_id'];

    }

    if ((!in_array($_SESSION['matricule'], $array_admin_CQ)) && ($oFNC['fnc_statut'] != "bouclé")) {
        $iCP       = $_REQUEST["txtCP"];
        $zComm     = $_REQUEST["txtComm"];
        $zType     = $_REQUEST["slctTypeFNC"];
        $zMotif    = pg_escape_string($_REQUEST["txtAreaMotif"]);
        $zExigence = pg_escape_string($_REQUEST["txtAreaExegence"]);

        $curative = setACloturer("curative", $iId);
        if ($curative == "ok") {
            $zActionCStatut = "ok";
        } elseif ($curative == "vide") {
            $zActionCStatut = "";
        }
        // vide
        else {
            $zActionCStatut = "en cours";
        }

        $ncurative = setACloturer_i("corrective / preventive", $iId);
        if ($ncurative == "ok") {
            $zActionNCStatut = "ok";
        } elseif ($ncurative == "vide") {
            $zActionNCStatut = "";
        }
        // vide
        else {
            $zActionNCStatut = "en cours";
        }

        $zTraitStatut = $oFNC['fnc_statut'];

        $zCause = $_REQUEST['slctCause1'] . "*#|#*" . pg_escape_string($_REQUEST["txtCause2"]) . "*#|#*" . pg_escape_string($_REQUEST["txtCause3"]);

        $zImputation   = pg_escape_string($_REQUEST["txtAreaImputation"]);
        $zDateRep      = $_REQUEST["txtDateRep"];
        $zRefRep       = pg_escape_string($_REQUEST["txtAreaRefRep"]);
        $iPilote       = $_REQUEST["txtPilote"];
        $zModificateur = $_SESSION["matricule"]; //    matricule de l'utilisateur courant        ****************** */
        $zModifDate    = date("Y-m-d");

        $zTypologie = $_REQUEST["slctTypologie"];
    }

    if ((in_array($_SESSION['matricule'], $array_admin_CQ)) && ($oFNC['fnc_statut'] != "bouclé")) {

        $iCP       = $_REQUEST["txtCP"];
        $zComm     = $_REQUEST["txtComm"];
        $zType     = $_REQUEST["slctTypeFNC"];
        $zMotif    = pg_escape_string($_REQUEST["txtAreaMotif"]);
        $zExigence = pg_escape_string($_REQUEST['txtAreaExegence']);

        if ($_REQUEST["slctTraitStatut"] == "boucléFNC") {
            $zTraitStatut   = "bouclé";
            $zActionCStatut = "bouclé";
            $etatNC         = $zActionNCStatut         = "bouclé";
            $sqlMajAction   = "UPDATE nc_action SET action_etat = 'ok' WHERE \"action_fncId\" = '$iId' ; ";
            $resMajAction   = @pg_query($conn, $sqlMajAction);
        } else {

            if ($_REQUEST["slctTraitStatut"] == "boucléC") {
                $zActionCStatut  = "bouclé";
                $zTraitStatut    = $oFNC['fnc_statut'];
                $zActionNCStatut = $oFNC['fnc_actionNCStatut'];
            } else {
                if ($_REQUEST["slctTraitStatut"] == "boucléNC") {
                    $etatNC         = $zActionNCStatut         = "bouclé";
                    $zTraitStatut   = $oFNC['fnc_statut'];
                    $zActionCStatut = $oFNC['fnc_actionCStatut'];
                } else {
                    if ($oFNC['fnc_actionCStatut'] != "bouclé") {

                        $curative = setACloturer("curative", $iId);

                        if ($curative == "ok") {
                            $zActionCStatut = "ok";
                        } elseif ($curative == "vide") // vide
                        {
                            $zActionCStatut = "";
                        } else {
                            $zActionCStatut = "en cours";
                        }

                    } else {
                        $ncurative = setACloturer_i("corrective / preventive", $iId);
                        if ($ncurative == "ok") {
                            $zActionNCStatut = "ok ";
                        } elseif ($ncurative == "vide") //vide
                        {
                            $zActionNCStatut = "";
                        } else {
                            $zActionNCStatut = "en cours";
                        }

                    }
                }
            }
        }

        $zTraitStatut = $oFNC['fnc_statut'];

        $zCause = $_REQUEST['slctCause1'] . "*#|#*" . pg_escape_string($_REQUEST["txtCause2"]) . "*#|#*" . pg_escape_string($_REQUEST["txtCause3"]);

        $zImputation = pg_escape_string($_REQUEST["txtAreaImputation"]);

        $zDateRep = $_REQUEST["txtDateRep"];

        $zRefRep       = pg_escape_string($_REQUEST['txtAreaRefRep']);
        $iPilote       = $_REQUEST["txtPilote"];
        $zModificateur = $_SESSION["matricule"];
        $zModifDate    = date("Y-m-d");

        $zTypologie = $_REQUEST["slctTypologie"];
    }

    $zImputation = pg_escape_string($_REQUEST["txtAreaImputation"]);

    $zTypologie = $_REQUEST['slctTypologie'];

    // Ajout de frequence et gravite
    /* ############################# 20150212 Fulgence ###################################*/
    // if ($_SESSION['matricule'] == 7530 || $_SESSION['matricule'] == 6548)
    if (in_array($_SESSION['matricule'], $array_admin_CQ)) {

        $slctGravite   = $_REQUEST['slctGravite'];
        $slctFrequence = $_REQUEST['slctFrequence'];

        /***********************************************************************/
        //
        //        Ajouter par mle 9092 pour controler
        //
        /*************************************************************************/

        if (isset($slctGravite) && isset($slctFrequence)) {

            //echo "tets".$slctGravite."".$slctFrequence."tero<br/>" ;

            // Selection d'id pour la gravité

            if ($slctGravite != null) {
                $sqlGrv = "SELECT id_gravite FROM nc_gravite WHERE \"catId_grav\" = '$slctGravite' ";
            }

            $resGrv  = @pg_query($conn, $sqlGrv) or die(pg_last_error($conn));
            $arGrv   = @pg_fetch_array($resGrv);
            $id_grav = $arGrv['id_gravite'];

            // Selection d'id pour la fréquence
            $sqlFrq  = "SELECT id_frequence FROM nc_frequence WHERE \"catId_freq\" = '$slctFrequence' ";
            $resFrq  = @pg_query($conn, $sqlFrq) or die(pg_last_error($conn));
            $arFrq   = @pg_fetch_array($resFrq);
            $id_freq = $arFrq['id_frequence'];

            /** *************************************** mise a jour de la fnc *********************** */

            $zSql = "UPDATE nc_fiche
                         SET ";
            if (!empty($iCP)) {
                $zSql .= "fnc_cp = '$iCP', ";
            }

            $zSql .= "fnc_comm = '$zComm',
                            fnc_type = '$zType', fnc_motif = '$zMotif', fnc_exigence = '$zExigence', fnc_cause = '$zCause', ";
            $zSql .= "fnc_statut = '$zTraitStatut', ";
            if ($zDateRep != '') {
                $zSql .= "\"fnc_reponseDate\" = '$zDateRep', ";
            }

            $zSql .= "\"fnc_reponseRef\" = '$zRefRep', \"fnc_modif_Matricule\" = '$zModificateur',
                            \"fnc_modifDate\" = '$zModifDate', fnc_version = fnc_version + 1 ";
            if (isset($zImputation) && !empty($zImputation)) {
                $zSql .= ", fnc_imputation = '$zImputation' ";
            }

            if (isset($zTypologie) && !empty($zTypologie)) {
                $zSql .= ", fnc_typologie = '$zTypologie' ";
            }

            if (isset($slctGravite) && !empty($slctGravite)) {
                $zSql .= ", fnc_gravite_id='{$slctGravite}',fnc_frequence_id='{$slctFrequence}',fnc_freq_cat_id='{$id_freq}',fnc_grav_cat_id='{$id_grav}' ";
            }

            $zSql .= ", \"fnc_actionCStatut\" = '$zActionCStatut', \"fnc_actionNCStatut\" = '$etatNC' WHERE fnc_id = '$iId' ; ";

            $resUpdate = @pg_query($conn, $zSql) or die(pg_last_error($conn));

        } else {

            $cat_id_grav = $oFNC['fnc_grav_cat_id'];
            $cat_id_freq = $oFNC['fnc_freq_cat_id'];

            // Selection d'id pour la gravité
            if ($cat_id_grav != '') {
                $sqlGrv    = "SELECT id_gravite,echelle_gravite FROM nc_gravite WHERE \"catId_grav\" = '$cat_id_grav' ";
                $resGrv    = @pg_query($conn, $sqlGrv) or die(pg_last_error($conn));
                $arGrv     = @pg_fetch_array($resGrv);
                $i_id_grav = $arGrv['id_gravite'];
            }

            // Selection d'id pour la fréquence

            if ($cat_id_freq != '') {
                $sqlFrq    = "SELECT id_frequence FROM nc_frequence WHERE \"catId_freq\" = '$cat_id_freq' ";
                $resFrq    = @pg_query($conn, $sqlFrq) or die(pg_last_error($conn));
                $arFrq     = @pg_fetch_array($resFrq);
                $i_id_freq = $arFrq['id_frequence'];
            }
            /** *************************************** mise a jour de la fnc *********************** */
            $zSql = "UPDATE nc_fiche
                             SET ";
            if (!empty($iCP)) {
                $zSql .= "fnc_cp = '$iCP', ";
            }

            $zSql .= "fnc_comm = '$zComm',
                                fnc_type = '$zType', fnc_motif = '$zMotif', fnc_exigence = '$zExigence', fnc_cause = '$zCause', ";
            $zSql .= "fnc_statut = '$zTraitStatut', ";
            if ($zDateRep != '') {
                $zSql .= "\"fnc_reponseDate\" = '$zDateRep', ";
            }

            $zSql .= "\"fnc_reponseRef\" = '$zRefRep', \"fnc_modif_Matricule\" = '$zModificateur',
                                \"fnc_modifDate\" = '$zModifDate', fnc_version = fnc_version + 1 ";
            if (isset($zImputation) && !empty($zImputation)) {
                $zSql .= ", fnc_imputation = '$zImputation' ";
            }

            if (isset($zTypologie) && !empty($zTypologie)) {
                $zSql .= ", fnc_typologie = '$zTypologie' ";
            }

            if (isset($cat_id_grav) && !empty($cat_id_grav)) {
                $zSql .= ", fnc_gravite_id='{$cat_id_grav}',fnc_frequence_id='{$cat_id_freq}',fnc_freq_cat_id='{$i_id_freq}',fnc_grav_cat_id='{$i_id_grav}' ";
            }

            $zSql .= ", \"fnc_actionCStatut\" = '$zActionCStatut', \"fnc_actionNCStatut\" = '$etatNC' WHERE fnc_id = '$iId' ; ";
            //echo $zSql; exit;
            //$resUpdate = @pg_query ($conn, $zSql) or die (pg_last_error($conn));

            $resUpdate = @pg_query($conn, $zSql) or die(pg_last_error($conn));

        }

        // echo $zSql; exit;
        //$resUpdate = @pg_query ($conn, $zSql) or die (pg_last_error($conn));

    } else {
        $cat_id_grav = $oFNC['fnc_grav_cat_id'];
        $cat_id_freq = $oFNC['fnc_freq_cat_id'];

        // Selection d'id pour la gravité
        if ($cat_id_grav != '') {
            $sqlGrv    = "SELECT id_gravite,echelle_gravite FROM nc_gravite WHERE \"catId_grav\" = '$cat_id_grav' ";
            $resGrv    = @pg_query($conn, $sqlGrv) or die(pg_last_error($conn));
            $arGrv     = @pg_fetch_array($resGrv);
            $i_id_grav = $arGrv['id_gravite'];
        }

        // Selection d'id pour la fréquence

        if ($cat_id_freq != '') {
            $sqlFrq    = "SELECT id_frequence FROM nc_frequence WHERE \"catId_freq\" = '$cat_id_freq' ";
            $resFrq    = @pg_query($conn, $sqlFrq) or die(pg_last_error($conn));
            $arFrq     = @pg_fetch_array($resFrq);
            $i_id_freq = $arFrq['id_frequence'];
        }
        /** *************************************** mise a jour de la fnc *********************** */
        $zSql = "UPDATE nc_fiche
                   SET ";
        if (!empty($iCP)) {
            $zSql .= "fnc_cp = '$iCP', ";
        }

        $zSql .= "fnc_comm = '$zComm',
                      fnc_type = '$zType', fnc_motif = '$zMotif', fnc_exigence = '$zExigence', fnc_cause = '$zCause', ";
        $zSql .= "fnc_statut = '$zTraitStatut', ";
        if ($zDateRep != '') {
            $zSql .= "\"fnc_reponseDate\" = '$zDateRep', ";
        }

        $zSql .= "\"fnc_reponseRef\" = '$zRefRep', \"fnc_modif_Matricule\" = '$zModificateur',
                      \"fnc_modifDate\" = '$zModifDate', fnc_version = fnc_version + 1 ";
        if (isset($zImputation) && !empty($zImputation)) {
            $zSql .= ", fnc_imputation = '$zImputation' ";
        }

        if (isset($zTypologie) && !empty($zTypologie)) {
            $zSql .= ", fnc_typologie = '$zTypologie' ";
        }

        if (isset($cat_id_grav) && !empty($cat_id_grav)) {
            $zSql .= ", fnc_gravite_id='{$cat_id_grav}',fnc_frequence_id='{$cat_id_freq}',fnc_freq_cat_id='{$i_id_freq}',fnc_grav_cat_id='{$i_id_grav}' ";
        }

        $zSql .= ", \"fnc_actionCStatut\" = '$zActionCStatut', \"fnc_actionNCStatut\" = '$etatNC' WHERE fnc_id = '$iId' ; ";
        //echo $zSql; exit;
        //$resUpdate = @pg_query ($conn, $zSql) or die (pg_last_error($conn));

        $resUpdate = @pg_query($conn, $zSql) or die(pg_last_error($conn));

    }
    //echo "tets".$slctGravite."-".$slctFrequence."-".$id_grav."-".$id_freq."tero<br/>" ;
    //echo $cat_id_grav."-".$cat_id_freq."-".$i_id_grav."-".$i_id_freq."<br/>" ;

    /* #######################################################################################*/

    /** ************************************ mise a jour du pilote de l'action ************************************* */
    $sqlUpdateAction = "UPDATE nc_action SET action_pilote = '$iPilote' WHERE \"action_fncId\" = '$iId' ";
    $resActionUpdat  = @pg_query($conn, $sqlUpdateAction);

    if (!$sqlUpdate) {
        $_SESSION["MSGSearch"] = "<font color=\"black\">Derni&egrave;re action :</font> modification de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> une erreur s'est produite lors de la modification, v&eacute;rifiez que votre session est toujours active!";
    } else {
        $_SESSION["MSGSearch"] = "<font color=\"black\">Derni&egrave;re action :</font> modification de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> fiche modifi&eacute;e avec succ&egrave;s";
    }

    $iId    = $_REQUEST["txtId"];
    $txtRef = $_REQUEST["txtRef"];

    /*********************************************************************************************/
    //          Ajouter par matricule 9092, dans le lien ajout txtCreateurMatr=$txtCreateurMatr
    /**********************************************************************************************/

    $actual_link = "http://$_SERVER[HTTP_HOST]" . $_SERVER['PHP_SELF'];
    $var_js      = "<script type='text/javascript'>
          document.location = '$actual_link?txtId=$iId+&txtRef=$txtRef+&txtCreateurMatr=$txtCreateurMatr';
        </script>";
    echo $var_js;

}

//----------------------------------- les données utilisées pour la table log ---------------------------------
list($usec, $sec) = explode(" ", microtime());
$time_end         = (float) $usec + (float) $sec;
include "/var/www.cache/logusergpao.inc";
//---------------------------------------------------------------------------------------------------------------
function getPersAccesNotation($sessMat)
{
    global $conn;

    $sql = "
      select matricule from personnel p
      left join cc_sr_droit d on p.matricule = d.matricule_droit
      where p.actifpers = 'Active'
      and matricule = " . $sessMat;

    $query = pg_query($sql) or die(pg_last_error());

    $accesCCsrTab = @pg_fetch_array($query);
    return $accesCCsrTab['matricule'];
}

?>
</div>
<script type="text/javascript">
   function check_bu ()
     {
         var id_fnc = $("#txtIdFnc").val();
         var lib_bu_opt = $("#hidden_bu").val();
         var hidden_fnc_code = $("#hidden_fnc_code").val();
         $.ajax({
               type: "POST",
               url: "FNC_check_bu.php",
               data: {
                         id_fnc : id_fnc
                     },
               success: function(rslt){
                  if(rslt != 1)
                  {
                     alert('La code commande : '+hidden_fnc_code+ ' appartient au BU : '+lib_bu_opt+'.  \nVeuillez rapprocher du BAV pour le changement.');

                     $("#lib_bu_opt").val(lib_bu_opt);
                     return false;
                  }
                  else return true;

               },
               async: false
         });

     }

</script>
</body>
</html>