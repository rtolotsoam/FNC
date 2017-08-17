<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "FNCEnvoiMailCritique.php";

/*****************************************************************/
//                                                                //
//  fichier de cofiguration admin FNC, ajouter par mle 9092       //
/****************************************************************/
include "FNCUser_admin.php";

$sStyle = "";
// not msie
if (strpos($_SERVER['HTTP_USER_AGENT'], '(compatible; MSIE ') == false) {
    $sClass = "slct";
}

/*
if(!empty($_REQUEST['slctGravite']) || $_REQUEST['slctGravite'] != '')
$slctGravite = $_REQUEST['slctGravite'] ;
 */

?>

<html>
<head>
  <meta http-equiv = "Content-Type" content = "text/html; charset = iso-8859-1">
  <title>ajoutFNC</title>
  <link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="../css/FNCAjout.css?213" />
  <script type="text/javascript" src="../js/jquery-1.3.2.js?213"></script>
  <script type="text/javascript" src="../js/ui.core.js?213"></script>
  <script type="text/javascript" src="../js/ui.draggable.js?213"></script>
  <script type="text/javascript" src="../js/ui.dialog.js?213"></script>
  <script type="text/javascript" src="../js/ui.datepicker.js?213"></script>
  <script type="text/javascript" src="../js/FNCAjout.js?213"></script>
  <script type="text/javascript">

  function suppr_text_autres(){

    $("#texte_autre").empty();
    $("#listeBU").hide();
  }

    $(document).ready(function() {



      $("#slctTypeFNC").change(function(){

        fillClient('add', '');
        $("#texte_autre").empty();
            var typeCLient = $("#slctTypeFNC").val();
           if(typeCLient == 'audit')
           {

               $("#listeBU").show();
               $("#slctBU").load('../webservices/fillBu.php');

           }
           else
           {

               $("#listeBU").hide();
           }
      });


         $("#slctGravite").change(function() {
            var grv = $("#slctGravite").val() ;
            var frq = $("#slctFrequence").val() ;
            //alert(grv);
            $.post(
               "new_criticite.php",
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
         }) ;



         $("#slctFrequence").change(function() {
            var frq = $("#slctFrequence").val() ;
            var grv = $("#slctGravite").val() ;
            //alert(grv);
            $.post(
               "new_criticite.php",
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
         }) ;
    });
      /*
      function grvSlt() {
         var grv = $("#slctGravite").val() ;

         var grv_id = $("#txtGrv").val() = grv ;

         //grv_id = $("#txtGrv").val() ;
         alert(grv_id) ;
      }
    */

  </script>
   <style>
      .slct_grav {
         width : 175px;
      }

      .idFrq {
         width: 50px
      }

   </style>

</head>

<body>

<?php
require "DBConnect.php";
echo "<p style = \"color: #999999; padding-left: 10%;\">";
if (isset($_SESSION["MSGAjout"])) {
    echo $_SESSION['MSGAjout'];
}

echo "</p>";

$iMatricule = $_SESSION['matricule'];
if ($iMatricule == "" || empty($iMatricule)) {
    echo "<br/><font color='red'>Session GPAO expir&eacute;. Merci de vous d&eacute;connecter en cliquant sur 'quitter' et de vous reconnecter &agrave; nouveau !</font>";
    exit;
}

$gpe  = 0;
$zSql = "SELECT gpe_id  FROM intranet_personnel_gpe ";
if ($iMatricule != '') {
    $zSql .= " where personnel_id =  '$iMatricule' ";
}

$query_ptg = @pg_query($conn, $zSql) or die(@pg_last_error($conn));
$grp       = @pg_fetch_array($query_ptg, 0);
$gpe       = $grp["gpe_id"];

$COQUAL = findGroup($conn);

$disable_audit = "style='display:none;";
$audit__       = '';
$masqueOption  = "hidden";

//modification 21/02/2017
// if($iMatricule == 606 || $iMatricule == 6550 || $iMatricule == 7122 || $iMatricule == 5029 ||,5066,5196,6431,6211,7530,6548, 6491,5049,42,7257,9507,606,9593,9158)
if (in_array($iMatricule, $array_admin_CQ)) {
    $disabled      = '';
    $disable_audit = "";
    $audit__       = 'audit';
    $masqueOption  = "";
}

?>

  <form id = "frmFNCAjout" name = "frmFNCAjout" method = "post">
  <fieldset id="ajout">
    <legend>Identifiant de la Fiche de Non Conformit&eacute;</legend>
    <br />
    <table width = "100%"  border = "0" cellspacing = "1" cellpadding = "1">
      <tr>
          <td>Type : </td>
          <td colspan="3">
          <select id="slctTypeFNC" name="slctTypeFNC" class="<?php echo $sClass; ?>">
              <option value="">***** fa&icirc;tes votre choix *****</option>
            <option <?php echo $masqueOption; ?>  value="<?php echo $audit__; ?>"> <?php echo $audit__; ?> </option>
            <option value="client">client</option>
              <option value="interne">interne</option>
            </select>
            <span class="style2"> *</span>
                <span id="listeBU" style="display:none;">
               &nbsp;&nbsp;&nbsp;BU:&nbsp;
                  <select id="slctBU" name="slctBU" >
                <option value="">***** fa&icirc;tes votre choix *****</option>
              </select>

               </span>
        </td>
        </tr>

      <tr>
          <td width="25%">Nom du client : </td>
          <td colspan="3">
            <span id="spnClient"  >
              <select id="slctClientName" name="slctClientName" class="<?php echo $sClass; ?>">
                <option value="">***** fa&icirc;tes votre choix *****</option>
              </select>
            </span>
          <span class = "style2">*</span>
        </td>
        </tr>
        <tr>
          <td width="25%"> </td>
          <td colspan="3" id="texte_autre">


        </td>
        </tr>

        <!--input type="text" id="typeProcessus" name="typeProcessus" style="display:none" /-->

        <tr>
          <td>Code de la commande : </td>
          <td colspan="3">
            <span id="spnCommand">
              <select id="slctCode" name="slctCode" class="<?php echo $sClass; ?>">
                <option value="">***** fa&icirc;tes votre choix *****</option>
              </select>
            </span>
            <span class = "style2" id="starCMD">*</span>

        </td>
        </tr>

         <tr>
            <td>Gravit&eacute; / impact : </td>
            <td colspan="3">
               <span id="spnGrv">
                  <select id="slctGravite" name="slctGravite" class="slct_grav">
                     <option value="">***** fa&icirc;tes votre choix *****</option>
                     <?php
$sqlGravite = "SELECT id_categorie_grav,echelle_id_grav,libelle_gravite FROM nc_gravite_categorie ORDER BY id_categorie_grav ";
$resGravite = pg_query($conn, $sqlGravite) or die(pg_last_error($conn));

$iNumGrav = @pg_num_rows($resGravite);
for ($i = 0; $i < $iNumGrav; $i++) {
    $toGravite   = @pg_fetch_array($resGravite, $i);
    $cat_grav_id = $toGravite['id_categorie_grav'];
    $ech_grav    = $toGravite['echelle_id_grav'];
    $lib_grav    = $toGravite['libelle_gravite'];
    echo "<option value='{$cat_grav_id}'>" . $ech_grav . "_" . $lib_grav . "</option>";
}

?>
                  </select>
               </span>
               <span class = "style2" id="">*</span>
            </td>
         </tr>

         <tr>
            <td>Fr&eacute;quence / Probabilit&eacute; d'occurrence : </td>
            <td width="250px;">
               <span id="spnFrq">
                  <select id="slctFrequence" name="slctFrequence" class="slct_grav">
                     <option value="">***** fa&icirc;tes votre choix *****</option>
                     <?php
$sqlFrequence = "SELECT id_categorie_freq,echelle_id_freq,libelle_frequence FROM nc_frequence_categorie ORDER BY id_categorie_freq ";
$resFrequence = pg_query($conn, $sqlFrequence) or die(pg_last_error($conn));

$iNumFreq = @pg_num_rows($resFrequence);
for ($i = 0; $i < $iNumFreq; $i++) {
    $toFrequence = @pg_fetch_array($resFrequence, $i);
    $cat_freq_id = $toFrequence['id_categorie_freq'];
    $ech_freq    = $toFrequence['echelle_id_freq'];
    $lib_freq    = $toFrequence['libelle_frequence'];
    echo "<option value='{$cat_freq_id}'>" . $ech_freq . "_" . $lib_freq . "</option>";
}

?>
                  </select>
               </span>
               <span class = "style2" id="">*</span>
            </td>

            <td width="100px;">Criticit&eacute; :</td>

            <td id="critq" width="100px;">

            </td>
            <td>
               <input type="hidden" id="txtGrv" name="txtGrv"/>
               <input type="hidden" id="txtFrq" name="txtFrq"/>
               <input type="hidden" id="txtCriticite" name="txtCriticite"/>
            </td>

         </tr>

        <tr>
          <td>Commercial : </td>
          <td colspan="3">
          <select id = "slctComm" name = "slctComm" class = "<?php echo $sClass; ?>">
            <option value="">***** fa&icirc;tes votre choix *****</option>
            <?php
$zSqlComm = @pg_query($conn, "SELECT comm_libelle FROM nc_comm where flagaffiche = 0 ORDER BY comm_libelle ASC");
for ($i = 0; $i < @pg_num_rows($zSqlComm); $i++) {
    $toData = @pg_fetch_array($zSqlComm, $i);
    $zValue = $toData['comm_libelle'];
    echo "<option value='{$zValue}'>{$zValue}</option>";
}
?>
            </select>
          </td>
        </tr>

        <tr>
          <td>Motif de cr&eacute;ation de la fiche : </td>
          <td colspan="3"><textarea id = "txtMotif" name = "txtMotif" class = "txtArea"></textarea><span class = "style2"> *</span></td>
        </tr>

        <tr>
          <td>Exigences client / r&eacute;f&eacute;rentiel : </td>
          <td colspan="3">
            <textarea id = "txtExigence" name = "txtExigence" class = "txtArea"></textarea>
            <span class = "style2" id="starCMD">*</span>
          </td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <input type="hidden" id="txtAjoutAction" name="txtAjoutAction">

          <td><input type = "reset" onclick="suppr_text_autres();" id = "btnCancelAjout" name = "btnCancelAjout" value = "R&eacute;initialiser" class = "ui-state-default"></td>
          <td colspan="3"><input type = "button" id = "btnAjoutFNC" name = "btnAjoutFNC" value = "Ouvrir une FNC" class = "ui-state-default" onClick="verifChampAjoutFNC();"></td>
        </tr>
    </table>
  </fieldset>
  </form>

<?php

if (isset($_REQUEST["txtAjoutAction"])) {

    $slctGravite   = $_REQUEST['slctGravite'];
    $slctFrequence = $_REQUEST['slctFrequence'];

    // Selection d'id pour la gravité
    $sqlGrv = "SELECT id_gravite FROM nc_gravite WHERE \"catId_grav\" = '$slctGravite' ";

    $resGrv  = @pg_query($conn, $sqlGrv) or die(pg_last_error($conn));
    $arGrv   = @pg_fetch_array($resGrv);
    $id_grav = $arGrv['id_gravite'];

    // Selection d'id pour la fréquence
    $sqlFrq  = "SELECT id_frequence FROM nc_frequence WHERE \"catId_freq\" = '$slctFrequence' ";
    $resFrq  = @pg_query($conn, $sqlFrq) or die(pg_last_error($conn));
    $arFrq   = @pg_fetch_array($resFrq);
    $id_freq = $arFrq['id_frequence'];

    $autre = "";
    if (!empty($_REQUEST["autre_cplmt"])) {
        $autre = $_REQUEST["autre_cplmt"];
    }

    $zType = $_REQUEST["slctTypeFNC"];

    $zCode = pg_escape_string($_REQUEST["slctCode"]);
    /**
     * modifié
     */
    if ($zType == "audit") {
        $zRef = "NC_" . strtoupper($zCode) . "_" . date("ymd");
    }

    if ($zType == "interne") {
        $zRef = "NC_INTERNE" . strtoupper($zCode[0] . $zCode[1] . $zCode[2]) . "_" . date("ymd");
    }
    // ajout 2012-07-11
    if ($zType == "client") {
        $zRef = "NC_" . strtoupper($zCode[0] . $zCode[1] . $zCode[2]) . "_" . date("ymd");
    }

    /**
     * fin modification
     */

    $zSqlSelect    = "SELECT fnc_ref FROM nc_fiche WHERE fnc_ref LIKE '$zRef%'";
    $oQueryRequete = pg_query($conn, $zSqlSelect);
    if (pg_num_rows($oQueryRequete) == 0) {
        $zRef .= "";
    } else {
        $zRef .= "(" . pg_num_rows($oQueryRequete) . ")";
    }

    //$zClientName = strtoupper(addslashes($_REQUEST["slctClientName"])) ;
    $zIdClient   = $_REQUEST["slctClientName"];
    $arrClient   = @pg_fetch_array(@pg_query($conn, "SELECT nom_client FROM gu_client WHERE id_client  = '$zIdClient ' LIMIT 1"), 0);
    $zClientName = strtoupper(pg_escape_string($arrClient["nom_client"]));

    //if ($zType == "audit") $zClientName .= " " . $zClientName; //strtoupper(addslashes($_REQUEST["txtClientName"])) ;
    if ($zType == "audit") {
        $zClientName .= " " . $zIdClient;
    }
    //strtoupper(addslashes($_REQUEST["txtClientName"])) ;

    $zComm         = pg_escape_string($_REQUEST["slctComm"]);
    $zCreationDate = date("Y-m-d");
    $zCreationHour = date("H:i:s");

    $zMotif    = pg_escape_string($_REQUEST["txtMotif"]);
    $zExigence = pg_escape_string($_REQUEST["txtExigence"]);

    if (($zType == "client") or ($zType == "audit")) {
        $zTraitStatut = "en cours";
        $zACStatut    = "";
    }
    /*
    elseif($zType == "audit"){
    $zTraitStatut = "en cours";
    $zACStatut = "";
    }
     */
    else {
        $zTraitStatut = "en attente";
        $zACStatut    = "";
    }
    $zANCStatut = "";
    $iCreateur  = $_SESSION["matricule"];
    $COQUAL     = findGroup($conn);

    if (($zType == "client") || ($COQUAL == 78) || ($zType == "audit")) {
        $zValide = "true";
    } else {
        $zValide = "false";
    }

    $iVersion = "1";

    /**
     * pour test fnc_ref
     */

    // $zRef = "NC_MCZ_170710";

    $buAudit = $_REQUEST['slctBU'];
    if (!empty($buAudit) && $buAudit != '') {
        $zSqlInsertFNC = "INSERT INTO nc_fiche (";
        if (!empty($zCode)) {
            $zSqlInsertFNC .= "fnc_code, ";
        }

        $zSqlInsertFNC .= "fnc_ref,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id, ";
        $zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\", fnc_autre_cplmnt,fnc_bu) VALUES (";
        if (!empty($zCode)) {
            $zSqlInsertFNC .= "'{$zCode}', ";
        }

        $zSqlInsertFNC .= "'{$zRef}','{$slctGravite}','{$slctFrequence}','{$id_freq}','{$id_grav}', ";
        $zSqlInsertFNC .= "'{$zComm}', '{$zCreationDate}', '{$zType}', '{$zMotif}', '{$zExigence}', '{$zTraitStatut}',  '{$iCreateur}', '{$zValide}', '{$zClientName}', '{$iVersion}', '{$zCreationHour}', '{$zACStatut}', '{$zANCStatut}','{$autre}','{$buAudit}')";
    } else {
        $zSqlInsertFNC = "INSERT INTO nc_fiche (";
        if (!empty($zCode)) {
            $zSqlInsertFNC .= "fnc_code, ";
        }

        $zSqlInsertFNC .= "fnc_ref,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id, ";
        $zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\", fnc_autre_cplmnt) VALUES (";
        if (!empty($zCode)) {
            $zSqlInsertFNC .= "'{$zCode}', ";
        }

        $zSqlInsertFNC .= "'{$zRef}','{$slctGravite}','{$slctFrequence}','{$id_freq}','{$id_grav}', ";
        $zSqlInsertFNC .= "'{$zComm}', '{$zCreationDate}', '{$zType}', '{$zMotif}', '{$zExigence}', '{$zTraitStatut}',  '{$iCreateur}', '{$zValide}', '{$zClientName}', '{$iVersion}', '{$zCreationHour}', '{$zACStatut}', '{$zANCStatut}','{$autre}')";
    }

    /*pg_send_query($conn, $zSqlInsertFNC);
    $res = pg_get_result($dbconn);

    # check for "UNIQUE VIOLATION"
    if(pg_result_error_field($res,PGSQL_DIAG_SQLSTATE) == '23505') {
    echo "
    <script type='text/javascript'>
    alert('".$res."');
    </script>
    ";

    }*/

    pg_send_query($conn, $zSqlInsertFNC);
    $res = pg_get_result($conn);

    # check for "UNIQUE VIOLATION"
    
    $test_oInsertFNC = "";

    if (pg_result_error_field($res, PGSQL_DIAG_SQLSTATE) == "23505") {

        $test_oInsertFNC = "KO";

    } else {

        //echo $zSqlInsertFNC ; exit;
        $oInsertFNC = @pg_query($conn, $zSqlInsertFNC);

        if (!$oInsertFNC) {
            $test_oInsertFNC = "OK";
        } else {
            $test_oInsertFNC = "KO";
        }

        // Récupération des libellé gravité et fréquence
        // ##############################################################################
        // Gravité
        $sqlGrvT  = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$slctGravite ";
        $resGrvT  = pg_query($conn, $sqlGrvT) or die(pg_last_error($conn));
        $arrGrvT  = pg_fetch_array($resGrvT);
        $echl_grv = $arrGrvT['echelle_id_grav'];

        // Frequence
        $sqlFrqc   = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $slctFrequence ";
        $resFrqc   = pg_query($conn, $sqlFrqc) or die(pg_last_error($conn));
        $arrFrqc   = pg_fetch_array($resFrqc);
        $echl_frqc = $arrFrqc['echelle_id_freq'];

        if ($echl_grv == 1) {
            $criticite = "m";
        } elseif ($echl_grv == 2 && $echl_frqc <= 2) {
            $criticite = "m";
        } elseif ($echl_grv == 2 && $echl_frqc >= 3) {
            $criticite = "M";
        } elseif ($echl_grv == 3 && $echl_frqc < 4) {
            $criticite = "M";
        } elseif ($echl_grv == 3 && $echl_frqc >= 4) {
            $criticite = "C";
        } elseif ($echl_grv >= 4) {
            $criticite = "C";
        } else {
            $criticite = "m";
        }

        if ($criticite == "C") {
            mailEnvoiFnc($zIdClient, $zCode, $slctGravite, $slctFrequence, $zMotif);
        }

        // ##############################################################################

        /**    *************************** recuperation de la derniere enregistrement ************************** **/
        $resSelect = @pg_query("SELECT fnc_id, fnc_ref FROM nc_fiche ORDER BY fnc_id DESC LIMIT 1");
        $toDonnee  = @pg_fetch_array($resSelect);
        $iId       = $toDonnee['fnc_id'];
        $zRef      = $toDonnee['fnc_ref'];

        

        /*
        if ($zType == "client") {
        @pg_query ($conn, "INSERT INTO nc_action (\"action_fncId\", \"action_debDate\", action_description, action_responsable, action_etat, action_type) VALUES ('$iId', '$zCreationDate', 'Accus&egrave; de r&eacute;ception de la r&eacute;clamation', '{$iCreateur}', 'en cours', 'curative') ") ;
        }
         */
        /** ************************************************************************************************* **/
    }

    

    if ($test_oInsertFNC == "KO") {
        $_SESSION["MSGAjout"] = "<font color=\"black\">Derni&egrave;re action :</font> ouverture de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> erreur lors de l'ouverture de la FNC, veuillez essayer de nouveau ult&egrave;rieurement.<br />";
        echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php\"</script>";
    } else {
        if ($test_oInsertFNC == "OK") {
            $_SESSION["MSGAjout"] = "<font color=\"black\">Derni&egrave;re action :</font> ouverture de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> fiche ouverte ";
            echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php?txtId={$iId}&txtRef={$zRef}\"</script>";
        } else {
            $_SESSION["MSGAjout"] = "<font color=\"black\">Derni&egrave;re action :</font> ouverture de la fiche ayant la r&eacute;f&eacute;rence : " . $zRef . "<br /><font color=\"black\">Statut :</font> erreur lors de l'ouverture de la FNC, veuillez essayer de nouveau ult&egrave;rieurement.<br />";
            echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php\"</script>";
        }
    }
}

?>
</body>
</html>