<?php
session_start();
	/**
	 * @author ralainahitra angelo
	 * @copyright 2010
	 * @derniere modification : 2010-05-12 par ralainahitra angelo
    * @ dernière modification : 2015-02-27 par Fulgence
	 */
  
   
	require_once ("DBConnect.php") ;		

		 $idFNC            = $_REQUEST['idFNC'] ;
      //echo "test".$iId2 ;
      /*$slctClientName      = $_REQUEST['slctClientName'] ;
      $slctCode            = $_REQUEST['slctCode'] ;
      $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
      $txtModifDate        = $_REQUEST['txtModifDate'] ;
      $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;
      $s_txtCP             = $_REQUEST['s_txtCP'] ;
      $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
      $slctType            = $_REQUEST['slctType'] ;
      $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;
      print_r()
      $motif_fnc           = $_REQUEST['motif_fnc'] ;*/
      // print_r($_REQUEST);
      if(isset($_REQUEST['txtRef']) && $idFNC == '')
      {
         $zrref = $_REQUEST['txtRef'];
         $zSqlFNCviaConsulterREf = "SELECT * FROM nc_fiche WHERE fnc_ref='".$zrref."'" ;
         $reqrref = @pg_query ($conn, $zSqlFNCviaConsulterREf) ;
         $getFNCviaConsulteref  = @pg_fetch_array($reqrref) ;
         $idFNC = $getFNCviaConsulteref['fnc_id'];
         $testRef = 1;
      }
      else
      {
          $idFNC            = $_REQUEST['idFNC'] ;
      }
      $zSqlFNCviaConsulter = "SELECT * FROM nc_fiche WHERE fnc_id=$idFNC" ;
      $queryZsqlFNCviaConsulter = @pg_query ($conn, $zSqlFNCviaConsulter) ;
      $getFNCviaConsulter  = @pg_fetch_array($queryZsqlFNCviaConsulter) ;
      
      $slctClientName = $getFNCviaConsulter['fnc_client'];
      $slctCode = $getFNCviaConsulter['fnc_code'];
      $txtCrationDate = $getFNCviaConsulter['fnc_modifDate'];
      $txtModifDate = $getFNCviaConsulter['fnc_modifDate'];
      $txtCreateurMatr = $getFNCviaConsulter['fnc_createur'];
      $s_txtCP = $getFNCviaConsulter['fnc_cp'];
      $slctTraitStatut = $getFNCviaConsulter['fnc_statut'];
      $slctType = $getFNCviaConsulter['fnc_type'];
      $slctTraitStatutCor = $getFNCviaConsulter['fnc_type'];
      
      
      $zRef = $getFNCviaConsulter['fnc_ref'];
      
      
      $iId = $_REQUEST["txtId"] ;
      $iId = $idFNC;
		// $zRef = $_REQUEST["txtRef"] ;
//echo "test".$iId."est".$zRef ;
		$iModif = $_REQUEST["txtModif"] ;
		$zRetour = $_REQUEST["txtRetour"];
      
     
      
		// Sélection d'id d'une fiche par rapport au référence sélectionné
		$sSqlFicheId = "SELECT fnc_id FROM nc_fiche WHERE fnc_ref = '{$zRef}' " ;
		//echo $sSqlFicheId ;exit;
		$rQueryFicheId = @pg_query ($conn, $sSqlFicheId) ;
		$aFicheId = @pg_fetch_array($rQueryFicheId) ;
		$iId2 = $aFicheId['fnc_id'] ;
      
      $txtNameClient       = $slctClientName ;
      $txtCode_cli         = $slctCode ;
      $createDate          = $txtCrationDate  ;
      $modifDate           = $txtModifDate ;
      $createMatr          = $txtCreateurMatr ;
      $cp_id               = $s_txtCP ;
      $statut_traitmt      = $slctTraitStatut ;
      $type_slt            = $slctType ;
      $statut_trtt_cor     = $slctTraitStatutCor ;
      
      $nc_motif            = $motif_fnc ;
      
		
	/***********************************************************************************************************/
?>
<!Doctype html>
<html>
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

      <title>Aper&ccedil;u d'une fiche</title>

      <link rel="stylesheet" type="text/css" href="../css/FNCConsulter.css" />
      <link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />

      <script type="text/javascript" src="../js/FNCSearch.js"></script>
      <script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
      <script type="text/javascript" src="../js/thickbox.js"></script>
      <script type="text/javascript" src="../js/ui.core.js"></script>
      <script type="text/javascript" src="../js/ui.draggable.js"></script>
      <script type="text/javascript" src="../../../gpao/js/jquery-block-ui.js"></script>
      <script type="text/javascript" src="../js/ui.dialog.js"></script>

    
      <style type="text/css">
      body, table
      {
         font-family: Verdana ;
         font-size: 11px ;
      }

      table.tab-base td
      {
         border: 1px #323232 solid ;
         font-family: Verdana ;
         font-size: 11px ;
      }
     
      .txtArea {
          border: 1px solid gray;
          color: #666666;
          font-size: 12px;
          height: 40px;
          width: 750px;
      }
      </style>
   </head>

   <body>

   <?php

      /* ******************** recuperation de toutes les donnees dans la table nc_fiche ********************** **/
      $zSqlFNC = "SELECT * FROM nc_fiche WHERE nc_fiche.fnc_id = '$iId2' " ;
      $oReqFNC = @pg_query ($conn, $zSqlFNC) ;
      $iNbFNC = @pg_num_rows($oReqFNC) ;
      $oFNC = @pg_fetch_array($oReqFNC,0) ;
      
      $COQUAL = findGroup($conn) ;
      $retrieveFncStatut = "";
      $valFncStatut = $oFNC['fnc_statut'] ;
       echo $retrieveFncStatut .="<input type='hidden' value = '{$valFncStatut}'  id='fncStatutBoucle'>";
      $retieveSession = "";
      $sessionMatr = $_SESSION["matricule"];
       echo $retieveSession .= "<input type='hidden' value = '{$sessionMatr}'  id='idSessionMatr'>";
      $retrieveCoqual = "";
      echo $retrieveCoqual .= "<input type='hidden' value = '{$COQUAL}'  id='idCoqual'>";
      $valFncValidte = $oFNC['fnc_valide'];
      $retrieveFncValidite = "";
       echo $retrieveFncValidite .= "<input type='hidden' value = '{$valFncValidte}'  id='idFncValidite'>";
       $valZretour = $_REQUEST["txtRetour"];
       
      $sCause1 = $sCause2 = $sCause3 = "";
      $aCause = explode("*#|#*", $oFNC['fnc_cause']);
      
      
      $sqlCause1 = "SELECT libelle FROM nc_cause WHERE id = '{$aCause[0]}';" ;
      $resCause1 = @pg_query($conn, $sqlCause1) ;
      $aCause1 = @pg_fetch_array($resCause1,0);

      $sCause1 = $aCause1['libelle'];
      $sCause2 = $aCause[1];
      $sCause3 = $aCause[2];

      /* ******************** récupération de tous les motifs apparition dans la table nc_motif *************** **/
      $sSqlMotifAppar = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Apparition' AND nc_fiche.fnc_id = '{$iId2}' " ;
      $rQueryMAppar = @pg_query ($conn, $sSqlMotifAppar) ;
      $iNbAppar = @pg_num_rows ($rQueryMAppar) ;

      /* ******************** récupération de tous les motifs détection dans la table nc_motif *************** **/
      $sSqlMotifDetect = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Détection' AND nc_fiche.fnc_id = '{$iId2}' " ;
      $rQueryMDetect = @pg_query ($conn, $sSqlMotifDetect) ;
      $iNbDetect = @pg_num_rows ($rQueryMDetect) ;

      /* ******************** récupération de tous les motifs organisation dans la table nc_motif *************** **/
      $sSqlMotifOrganis = "SELECT libelle FROM nc_motif INNER JOIN nc_fiche ON nc_fiche.fnc_id = nc_motif.fnc_id WHERE type_motif = 'Organisation' AND nc_fiche.fnc_id = '{$iId2}' " ;
      $rQueryMOrganis = @pg_query ($conn, $sSqlMotifOrganis) ;
      $iNbOrganis = @pg_num_rows ($rQueryMOrganis) ;

      /* **************************** recuperation de toutes les actions curative *************************** **/
      
      $zSqlActionCurative = "SELECT * FROM nc_action INNER JOIN nc_fiche ON nc_action.\"action_fncId\" = nc_fiche.fnc_id WHERE nc_fiche.fnc_id = '$iId2' AND nc_action.action_type = 'curative' ORDER BY nc_action.\"action_debDate\" " ;
      
      $oReqActionCurative = @pg_query ($conn, $zSqlActionCurative) ;
      $iNbActionCurative = @pg_num_rows ($oReqActionCurative) ;
      
      // Modif 20121226
      for ($i = 0; $i < $iNbActionCurative; $i ++)
      {
         $toActionCurative = @pg_fetch_array($oReqActionCurative,$i) ;

         $iAC_etat = $toActionCurative['action_etat'] ;
         $iAC_statut = $toActionCurative['fnc_actionCStatut'] ;
      }
               
      // fin modif 26122012

      /* ************************* recuperation de toutes les actions non curative *************************** **/
      
      $zSqlActionNonCurative = "   SELECT * 
                                   FROM 
                                    nc_fiche 
                                    INNER JOIN nc_fnc_action ON nc_fiche.fnc_id = CAST (nc_fnc_action.fnc_id as integer) 
                                    INNER JOIN nc_action_liste ON nc_fnc_action.action_liste_id = nc_action_liste.id 
                                    INNER JOIN nc_fnc_infos ON nc_fnc_infos.id = nc_fnc_action.fnc_info_id
                                   WHERE nc_fiche.fnc_id = '{$iId2}' 
                                   AND nc_action_liste.type != 'curative' 
                                   ORDER BY nc_fnc_infos.date_debut  " ;
      
      $oReqActionNonCurative = @pg_query ($conn, $zSqlActionNonCurative) ;
      $iNbActionNonCurative = @pg_num_rows ($oReqActionNonCurative) ;
      
      // Modif 20121226
      for ($i = 0; $i < $iNbActionNonCurative; $i ++) {
         $toActionNonCurative = @pg_fetch_array($oReqActionNonCurative, $i) ;
         
         $iNC_Etat = $toActionNonCurative['etat'] ;
         $iNC_statut = $toActionNonCurative['fnc_actionNCStatut'] ;
         
      }
      // fin modif 26122012
      
      

      /* **************************************** pilote de l'action ***************************************** **/
      $zSqlPiloteAction = "SELECT action_pilote FROM nc_action, nc_fiche WHERE nc_action.\"action_fncId\" = nc_fiche.fnc_id AND nc_fiche.fnc_id = '$iId'";
      $oReqPiloteAction = @pg_query ($conn, $zSqlPiloteAction) ;
      $toPiloteAction = @pg_fetch_array ($oReqPiloteAction) ;

      /* ************************************ nom du createur de la FNC ************************************** **/
      $sqlCreateur = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$oFNC['fnc_createur'] ;
      $resCreateur = @pg_query($conn, $sqlCreateur) ;
      $oCreateur = @pg_fetch_array($resCreateur) ;

      /* ************************************* nom du CP de la FNC ******************************************** **/
      $sqlCP = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$oFNC['fnc_cp'] ;
      $resCP = @pg_query($conn, $sqlCP) ;
      $oCP = @pg_fetch_array($resCP) ;

      /* ************************************** nom du pilote de l'action ************************************* **/
      $sqlPilote = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$toPiloteAction['action_pilote'] ;
      $resPilote = @pg_query($conn, $sqlPilote) ;
      $oPilote = @pg_fetch_array($resPilote) ;

      /* ************************************** nom du modificateur de la fiche ****************************** **/
      $sqlModificateur = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$oFNC['fnc_modif_Matricule'] ;
      $resModificateur = @pg_query($conn, $sqlModificateur) ;
      $oModificateur = @pg_fetch_array($resModificateur) ;

      echo "<p style = \"color: #999999; padding-left: 10%;\">".$_SESSION['MSGSearch']."</p>";
      /**************************************************************************************************************/
      
            /****************** Modif Fulgence 20150210  ******************/
            // Affichage criticité
            if($oFNC['fnc_grav_cat_id'] != '' && $oFNC['fnc_freq_cat_id'] !='')
            {
               //if($oFNC['fnc_grav_cat_id'] != '')
                  $cat_id_grav = $oFNC['fnc_grav_cat_id'] ;
               /*else
                  $cat_id_grav = 1 ;
                  
               if($oFNC['fnc_freq_cat_id'] != '')*/
                  $cat_id_freq = $oFNC['fnc_freq_cat_id'] ;
               /*else
                  $cat_id_freq = 1 ;*/
               
               // gravité
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$cat_id_grav " ;
               //echo $sqlSltGrv ;
               $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
               $arGrv = pg_fetch_array($resGrv) ;
               $grv_ech = $arGrv['echelle_id_grav'] ;
               
               //Fréquence
               $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $cat_id_freq " ;
               $resFrq = pg_query($conn, $sqlSltFrq) or die (pg_last_error($conn)) ;
               $arrFrq = pg_fetch_array($resFrq) ;
               $frq_ech = $arrFrq['echelle_id_freq'] ;
               
               if ($grv_ech == 1)
                  $criticite = "m" ;
               elseif ($grv_ech == 2 && $frq_ech <= 2)
                  $criticite = "m" ;
               elseif ($grv_ech == 2 && $frq_ech >= 3)
                  $criticite = "M" ;
               elseif ($grv_ech == 3 && $frq_ech < 4)   
                  $criticite = "M" ;
               elseif ($grv_ech == 3 && $frq_ech >= 4)   
                  $criticite = "C" ;
               elseif($grv_ech >= 4)
                  $criticite = "C" ;
               /*else
                  $criticite = "p" ;*/
                  
               // test de couleur
               if ($criticite == "m")
               {
                  $color = "style='background-color:#FCF03F;font-weight:bold;'" ;
                  $criticite = "mineure" ;
               }
               elseif ($criticite == "M")
               {
                  $color = "style='background-color:#F28810;font-weight:bold;'" ;
                  $criticite = "Majeure" ;
               }
               elseif ($criticite == "C")
               {
                  $color = "style='background-color:#E71D07;color:#FFFFFF;font-weight:bold;'" ;
                  $criticite = "Critique" ;
               }
            }
            else
            {
               //$color = "style='background-color:#FFFFFF;font-weight:bold;'" ;
               $color = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'") ;
               $criticite = "" ;
            }
            /****************** Fin modif ******************/
      
      

   ?>

   <form id="frmApercu" name="frmApercu" action="" method="POST">

      <fieldset>
         <legend>Aper&ccedil;u de la FNC</legend>
         <p>
            Fiche de non conformit&eacute; version <?php echo $oFNC['fnc_version'] ?>, cr&eacute;&eacute;e par <?php echo $oCreateur['prenompersonnel'] ?><?php
            echo "(".$oFNC['fnc_createur'].")";
            if($oFNC['fnc_modifDate'] != "")
            echo " ; modifi&eacute;e par ".$oModificateur['prenompersonnel']." (".$oFNC['fnc_modif_Matricule']."), le ".$oFNC['fnc_modifDate'].".<br />";

            if($oFNC['fnc_valide'] == "f") echo "Cette fiche n'est pas encore valid&eacute;e." ;
            else echo "<br />Cette fiche est d&eacute;j&agrave; valid&eacute;e." ;
      ?>
         </p>

         <table width="100%" cellspacing="1" cellpadding="1" class="tab-base">
            <tr>
               <!--input type="hiden" id="txtRefe" name="txtRefe" value="<?php echo $zRef ; ?>"-->
               <td width="15%" rowspan="2" class="class1"></td>
               <td width="25%" class="class2">CLIENT</td>
               <td width="180%" class="class2">
               <p>&nbsp;<?php if (trim($oFNC['fnc_client']) == "Autres" ) echo  $oFNC['fnc_autre_cplmnt']; else  echo $oFNC['fnc_client']; ?></p>
               </td>
            </tr>
            <tr>
               <td class="class2">CODE</td>
               <td class="class2">
                  <p>&nbsp;<?php echo $oFNC['fnc_code']; ?></p>
               </td>
            </tr>
            <tr >
               <td rowspan="6" class="class1">NON CONFORMITE</td>
               <td class="class3">FICHE OUVERTE PAR</td>
               <td class="class3">
                  <!--p>&nbsp;<?php //if(!empty($oCP['prenompersonnel'])) echo $oCP['prenompersonnel']." (".$oFNC['fnc_cp'].")"; ?></p-->
                  <p>&nbsp;<?php if(!empty($oCreateur['prenompersonnel'])) echo $oCreateur['prenompersonnel']." (".$oFNC['fnc_createur'].")"; ?></p>
               </td>
            </tr>

            <tr>
               <td class="class3">DATE D'OUVERTURE DE LA FICHE</td>
               <td class="class3">
                  <p>&nbsp;<?php echo $oFNC['fnc_creationDate']; ?></p>
               </td>

               <input type="hidden" id="ID" value="<?php echo $iId ;?>">
               <input type="hidden" id="REF" value="<?php echo $zRef; ?>">
            </tr>
            <tr>
               <td class="class3">TYPE</td>
               <td class="class3">
                  <p>&nbsp;<?php echo $oFNC['fnc_type']; ?></p>
               </td>
            </tr>
            <tr>
               <td class="class3">MOTIF DE CREATION DE LA FICHE</td>
               <td class="class3">
                  <p>&nbsp;<?php echo $oFNC['fnc_motif']; ?></p>
               </td>
            </tr>
            <tr>
               <td class="class3">EXIGENCES CLIENT / REFERENTIEL</td>
               <td class="class3">
                  <p>&nbsp;<?php echo $oFNC['fnc_exigence']; ?></p>
               </td>
            </tr>
            
            <!-- Ajout fulgence 20150211 -->
            <tr>
               <td class="class3">CRITICITE</td>
               <td <?php echo $color ; ?>>
                  <p>&nbsp;<?php echo $criticite ; ?></p>
               </td>
            </tr>
            <!-- Fin ajout Fulgence -->
            
            <tr>
               <?php 
                  
                  if ($oFNC['fnc_type'] == "interne")
                     echo "<td rowspan=\"5\" class=\"class1\"><div class=\"i_image\" id=\"iamg\" style=\"background:#FFFFFF;color:#666666;height:50px; \"><a href=\"FNCConsulter2.php?txtId=".$oFNC['fnc_id']."&txtRef=".$oFNC['fnc_ref']."\" style=\"color:red;font-size:11px; \" onclick=\"javascript: $('#iamg').children('image').removeAttr ('src'); $('#iamg').children('image').attr ('src','FNCConsulter2.php'); $.blockUI ({message:$('.i_image') }); $('.blockOverlay').attr('title','Click pour sortir').click($.unblockUI);\" >Logigramme d&eacute;cisionnel</a></div>ANALYSE NC</td>" ;
                  else
                     echo "<td rowspan=\"5\" class=\"class1\"><div class=\"i_image\" id=\"iamg\" style=\"background:#FFFFFF;color:#666666;height:50px; \"><a href=\"FNCConsulter2.php?txtId=".$oFNC['fnc_id']."&txtRef=".$oFNC['fnc_ref']." \" id=\"image\" style=\"color:red;font-size:11px; \" onclick=\"javascript: $('#iamg').children('image').removeAttr ('src'); $('#iamg').children('image').attr ('src','FNCConsulter2.php'); $.blockUI ({message:$('.i_image') }); \" >Logigramme d&eacute;cisionnel</a></div>ANALYSE NC</td>" ;
               ?>
            </tr>
            <tr>

               <td class="class4">IMPUTATION</td>
               <td class="class4">
                  <?php
                     $sqlImputation = "SELECT imputation_libelle FROM nc_imputation WHERE imputation_id = '".$oFNC['fnc_imputation']."' " ;
                     $resImputation = @pg_query($conn, $sqlImputation) ;
                     $aImputation = @pg_fetch_array($resImputation) ;
                     echo "&nbsp;".$aImputation['imputation_libelle'] ;
                  ?>
               </td>
            </tr>
            <tr>
               <td class="class4">MOTIF APPARITION</td>
               <td class="class4">
                  <table width="100%"  cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
                     if ($iNbAppar == 0)
                     {
                        echo "&nbsp;" ;
                     }
                     else
                     {
                        for($i = 0; $i < $iNbAppar; $i ++)
                        {
                           $toMotAppar = @pg_fetch_array ($rQueryMAppar,$i) ;
                           echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifAppar\" name = \"txtMotifAppar\" class = \"txtArea\" readonly=\"readonly\">". $toMotAppar['libelle'] ."</textarea>
                                 </td>
                              </tr>" ;
                        }
                     }
                  ?>
                     <tr>
                        <td align="right">
                           <input type="button" id="btnAjoutMot" name="btnAjoutMot" value="Ajout motif" class = "ui-state-default" onclick="javascript: document.location.href = 'FNCMotifAjout.php?txtMotif=Apparition&txtRef=<?php echo $zRef ; ?>' ">
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class4">MOTIF NON DETECTION</td>
               <td class="class4">
                  <table width="100%"  cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
                     if ($iNbDetect == 0)
                     {
                        echo "&nbsp;" ;
                     }
                     else
                     {
                        for($i = 0; $i < $iNbDetect; $i ++)
                        {
                           $toMotDetect = @pg_fetch_array ($rQueryMDetect,$i) ;
                           echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifDetect\" name = \"txtMotifDetect\" class = \"txtArea\" readonly=\"readonly\">". $toMotDetect['libelle'] ."</textarea>
                                 </td>
                              </tr>" ;
                        }
                     }
                  ?>
                     <tr>
                        <td align="right">
                           <input type="button" id="btnAjoutDetect" name="btnAjoutDetect" value="Ajout motif" class = "ui-state-default" onclick="javascript: document.location.href = 'FNCMotifAjout.php?txtMotif=Détection&txtRef=<?php echo $zRef ; ?>' ">
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class4">MOTIF ORGANISATION</td>
               <td class="class4">
                  <table width="100%" cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
                     if ($iNbOrganis == 0)
                     {
                        echo "&nbsp;" ;
                     }
                     else
                     {
                        for($i = 0; $i < $iNbOrganis; $i ++)
                        {
                           $toMotOrganis = @pg_fetch_array ($rQueryMOrganis,$i) ;
                           echo "<tr>
                                 <td>
                                    <textarea id = \"txtMotifOrganis\" name = \"txtMotifOrganis\" class = \"txtArea\" readonly=\"readonly\">". $toMotOrganis['libelle'] ."</textarea>
                                 </td>
                              </tr>" ;
                        }
                     }
                  ?>
                     <tr>
                        <td align="right">
                           <input type="button" id="btnAjoutOrgan" name="btnAjoutOrgan" value="Ajout motif" class = "ui-state-default" onclick="javascript: document.location.href = 'FNCMotifAjout.php?txtMotif=Organisation&txtRef=<?php echo $zRef ; ?>' ">
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>

            <?php
               $sqlSlctTypologie = "SELECT 
                                     typologie_libelle 
                                    FROM 
                                     nc_typologie 
                                    WHERE typologie_id = '".$oFNC['fnc_typologie']."'" ;
               
               $rQueryTypo = @pg_query($conn, $sqlSlctTypologie) ;
               
               $aResTypologie = @pg_fetch_array($rQueryTypo) ;
               
               echo "&nbsp;".$aResTypologie['typologie_libelle'] ;
            ?>
      

            <tr>
               <td rowspan="5" class="class1">ACTIONS</td>
            </tr>
            <tr>
                <td class="class3">STATUT</td>
                <td class="class3">
                  <p>
                  <?php
                     echo "&nbsp;";
                     /* ******************************************* statut de la fnc ************************************************ */
                        $zStatutFNC = $oFNC['fnc_statut'];
                        $zStatutAC = $oFNC['fnc_actionCStatut'];
                        $zStatutANC = $oFNC['fnc_actionNCStatut'];
                        //echo $zStatutAC ;
                        if (($iAC_statut == 'en attente') || ($iAC_statut == ''))
                           echo "actions non d&eacute;finies" ;
                           
                        elseif ($iAC_statut == 'en cours')
                           echo "action en cours" ;
                        
                        elseif ($iAC_statut == 'ok')
                           echo "&agrave; cl&ocirc;turer" ;
                           
                        else 
                           echo "Cl&ocirc;tur&eacute;es" ;
                           
                     /*****************************************************************************************************************/
                  ?>
                  </p>
               </td>
            </tr>
            <tr>
               <td class="class3">ACTIONS CURATIVES</td>
               <td>
                  <table width="100%"  cellspacing="1" cellpadding="1" class="tab-base">
                  <?php
                        if ($iNbActionCurative == 0)
                        {
                           echo "&nbsp;" ;
                        }
                        else
                        {
                  ?>
                     <tr>
                        <td width="15%" class="class0">DATE BEBUT </td>
                        <td width="40%" class="class0">ACTIONS</td>
                        <td width="15%" class="class0">RESPONSABLE</td>
                        <td width="15%" class="class0">DATE FIN </td>
                        <td width="15%" class="class0">ETAT</td>
                     </tr>
                     <?php
                        for ($i = 0; $i < $iNbActionCurative; $i ++)
                        {
                           $toActionCurative = @pg_fetch_array($oReqActionCurative,$i) ;
                           echo "<tr>
                           <td class=\"class0\">&nbsp;".$toActionCurative['action_debDate']."</td>
                           <td class=\"class0\">&nbsp;".$toActionCurative['action_description']."</td>
                           <td class=\"class0\">&nbsp;".$toActionCurative['action_responsable']."</td>
                           <td class=\"class0\">&nbsp;" ;
                              if ($toActionCurative['action_finDate'] == '') 
                                 echo "&nbsp;" ;
                              else 
                                 echo $toActionCurative['action_finDate'] ;
                           echo "</td>
                           <td class=\"class0\">&nbsp;".$toActionCurative['action_etat']."</td>
                          </tr> " ;
                        }
                     }
                     ?>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="class3">STATUT</td>
               <td class="class3">
                  <p>
                  <?php
                        echo "&nbsp;";
                        /* ******************************************* statut de la fnc ************************************************ */
                           $zStatutFNC = $oFNC['fnc_statut'];
                           $zStatutAC = $oFNC['fnc_actionCStatut'];
                           $zStatutANC = $oFNC['fnc_actionNCStatut'];
                                          
                           if (($iNC_Etat == 'en attente') || ($iNC_Etat == ''))
                              echo "actions non d&eacute;finies" ;
                              
                           elseif ($iNC_Etat == 'en cours')
                              echo "action en cours" ;
                              
                           elseif ($iNC_Etat == 'ok')
                              echo "&agrave; cl&ocirc;turer" ;
                              
                           else
                              echo "Cl&ocirc;tur&eacute;es" ;
                                 
                        /**************************************************************************************************/
                        
                        
                        
                  ?>
                  </p>
               </td>
            </tr>
            <tr>
               <td class="class3">ACTIONS CORRECTIVES</td>
               <td class="class3">
                  <table width="100%"  class="tab-base" cellspacing="1" cellpadding="1">
                  <?php 
                        if ($iNbActionNonCurative == 0)	echo "&nbsp;" ;
                        else
                        {
                  ?>
                     <tr>
                        <td width="15%" class="class0">DATE BEBUT </td>
                        <td width="40%" class="class0">ACTIONS</td>
                        <td width="15%" class="class0">RESPONSABLE</td>
                        <td width="15%" class="class0">DATE FIN </td>
                        <td width="15%" class="class0">ETAT</td>
                     </tr>
                     <?php
                        for ($i = 0; $i < $iNbActionNonCurative; $i ++) {
                           $toActionNonCurative = @pg_fetch_array($oReqActionNonCurative, $i) ;
                           $aActionDesctiption = explode("*#|#*", $toActionNonCurative['libelle']);
                           echo "<tr>
                           <td class=\"class0\">&nbsp;".$toActionNonCurative['date_debut']."</td>
                           <td class=\"class0\">&nbsp;{$aActionDesctiption[0]}</td>
                           <td class=\"class0\">&nbsp;".$toActionNonCurative['responsable']."</td>
                           <td class=\"class0\">&nbsp;" ;
                              if ($toActionNonCurative['date_fin'] == '') 
                                 echo "&nbsp;" ;
                              else 
                                 echo $toActionNonCurative['date_fin'] ;
                           echo "</td>
                           <td class=\"class0\">&nbsp;".$toActionNonCurative['etat']."</td>
                          </tr> " ;
                        }
                     }
                     //$createDate    = $_REQUEST['createDate'] ;
                     
                     ?>
                  </table>
                  <input type="hidden" id="txtNameClient" name="txtNameClient" value="<?php echo $slctClientName ; ?>" />
                  <input type="hidden" id="txtCode_cli" name="txtCode_cli" value="<?php echo $slctCode ; ?>" />
                  <input type="hidden" id="createDate" name="createDate" value="<?php echo $txtCrationDate ; ?>" />
                  <input type="hidden" id="modifDate" name="modifDate" value="<?php echo $txtModifDate ; ?>" />
                  <input type="hidden" id="createMatr" name="createMatr" value="<?php echo $txtCreateurMatr ; ?>" />
                  <input type="hidden" id="cp_id" name="cp_id" value="<?php echo $s_txtCP ; ?>" />
                  <input type="hidden" id="statut_traitmt" name="statut_traitmt" value="<?php echo $slctTraitStatut ; ?>" />
                  <input type="hidden" id="type_slt" name="type_slt" value="<?php echo $slctType ; ?>" />
                  <input type="hidden" id="statut_trtt_cor" name="statut_trtt_cor" value="<?php echo $slctTraitStatutCor ; ?>" />
                  <!--**************************--->
                  <input type="hidden" id="bId2" name="bId2" value="<?php echo $iId2 ; ?>" />
                  <input type="hidden" id="bzRef" name="bzRef" value="<?php echo $zRef ; ?>" />
                  <input type="hidden" id="bnc_motif" name="bnc_motif" value="<?php echo $nc_motif ; ?>" />
                  <!--**************************--->
                  
               </td>
            </tr>     
         </table>

         <table width="100%"  border="0" cellspacing="1" cellpadding="1">
            <tr>
               <td width="25%"><input type="button" id="btnModifier" name="btnModifier" value="Modifier" class = "ui-state-default" onclick="javascript:document.location.href ='FNCModifier_.php?txtId=<?php echo $iId2 ; ?>&txtRef=<?php echo $zRef ; ?>&slctClientName=<?php echo $txtNameClient; ?>&txtCrationDate=<?php echo $createDate ; ?>&txtModifDate=<?php echo $modifDate ; ?>&txtCreateurMatr=<?php echo $createMatr ; ?>&motif_fnc=<?php echo $nc_motif ; ?>' "></td>

               <td width="25%"><input type="button" id="btnValider" name="btnValider" value="Valider" class = "ui-state-default" onclick="valider()"></td>
               <td width="25%"><input type="button" id="btnSupprimer" name="btnSupprimer" value="Supprimer" class = "ui-state-default" onclick="supprimer()"></td>
               <td width="25%">
               <?php if($testRef == 1) 
                     {
                           echo " <input type='button'name='btnRetour2' id='btnRetour2' value='Fermer' onclick='myReload2();'class = 'ui-state-default'>";
                     }  
                     else
                     {
                         echo " <input type='button'name='btnRetour' id='btnRetour' value='Fermer' onclick='myReload();'class = 'ui-state-default'>";
                     }
               ?>
               
                 <!-- <input type="button" name="btnRetour" id="btnRetour" value="Fermer"  class = "ui-state-default"> -->
               </td>
            </tr>
         </table>

      </fieldset>
   </form>


   <?php
      // $txtCode_cli $createDate $modifDate $createMatr $cp_id $statut_traitmt $type_slt $statut_trtt_cor 
   
   /* *************** changement d'attibut du bouton selon l'utilisateur **************** */
    
	if(isset($_REQUEST['varTest']))
	{
       
        echo "<input type='hidden' value='curative' id='curative' />";
        if(isset($_REQUEST['curative']))
        {
         echo "
            <script type='text/javascript'>
               var varId2 = $('#bId2').val();
               var varZRef = $('#bzRef').val();
               var slctClientName = $('#txtNameClient').val();
               var txtCrationDate = $('#createDate').val();
               var txtModifDate = $('#modifDate').val();
               var txtCreateurMatr = $('#createMatr').val();
               var motif_fnc = $('#bnc_motif').val();
               var curative = $('#curative').val();
              document.location.replace('FNCModifier_.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc+'&curative='+curative);
            </script>";
        }
        if(isset($_REQUEST['corrective']))
        {
          echo "
            <script type='text/javascript'>
               var varId2           = $('#bId2').val();
               var varZRef          = $('#bzRef').val();
               var slctClientName   = $('#txtNameClient').val();
               var txtCrationDate   = $('#createDate').val();
               var txtModifDate     = $('#modifDate').val();
               var txtCreateurMatr  = $('#createMatr').val();
               var motif_fnc        = $('#bnc_motif').val();
               var curative         = $('#curative').val();
              document.location.replace('FNCModifier_.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc+'&corrective=corrective');
            </script>";
        }
        else
		{
         echo "
		<script type='text/javascript'>
            var varId2           = $('#bId2').val();
            var varZRef          = $('#bzRef').val();
            var slctClientName   = $('#txtNameClient').val();
            var txtCrationDate   = $('#createDate').val();
            var txtModifDate     = $('#modifDate').val();
            var txtCreateurMatr  = $('#createMatr').val();
            var motif_fnc        = $('#bnc_motif').val();
           document.location.replace('FNCModifier_.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc);
         </script>";
		}
   
	}     
?>

   </body>
</html>
	<script type="text/javascript">
	function myReload()
	{ 
		alert('999999');
		tb_remove();
		// $("").tb_remove(); 
		//window.location = "FNCSearch.php" ;
		var txtCrationDate = $("#txtCrationDate").val();
		var txtModifDate = $("#txtModifDate").val();
		//var slctClientName = $("#slctClientName:selected").val();
		var slctClientName = $("#slctClientName").val();

		// alert('xx'+slctClientName);
		if(slctClientName == undefined || slctClientName == '' || slctClientName == 'undefined')
		slctClientName = "";
		else
		var slctClientName = $('#slctClientName').find('option:selected').text();

		// alert(slctClientName);
		var slctCode = $("#slctCode").val();
		var txtCreateurMatr = $("#txtCreateurMatr").val();
		var txtCP = $("#txtCP").val();
		var slctTraitStatut = $("#slctTraitStatut").val();
		var slctType = $("#slctType").val();
		var slctTraitStatutCor = $("#slctTraitStatutCor").val();

		/*  $.post('ajax_FNCSearch.php?txtCrationDate='+ txtCrationDate +'&txtModifDate='+txtModifDate + '&slctClientName='+slctClientName +'&slctCode='+slctCode+'&txtCreateurMatr='+txtCreateurMatr+'&txtCP='+txtCP+'&slctTraitStatut='+slctTraitStatut+'&slctType='+slctType+'&slctTraitStatutCor='+slctTraitStatutCor+'&onsearch=1', 
		{ } , function(msg){
		$("#table2").html (msg) ;
		}); */
	}
	function myReload2()
	{
		window.location = "FNCSuiviActionNC.php" ;
	}
    
        var matrSession = $("#idSessionMatr").val();
        var userAcces = new Array(6548,7530,8108,5270);
        var idCoqual = $("#idCoqual").val();
        var fncStatutBoucle = $("#fncStatutBoucle").val();
        var idFncValidite = $("#idFncValidite").val();
        // alert('ds'+idFncValidite);
        if(fncStatutBoucle == 'boucl\351')
        {
            disableInput('btnSupprimer');
            // alert('1');
        }
        if(matrSession != 6548 && matrSession != 7530 && matrSession != 42 && matrSession != 5270 && (fncStatutBoucle == 'boucl\351'))
        {
             disableInput('btnSupprimer');
             disableInput('btnModifier');
             // alert('2'+fncStatutBoucle);
             // alert(fncStatutBoucle);
             // alert(matrSession);
        }
        // if(($COQUAL != 78) || ($oFNC['fnc_valide'] == 't'))
        if(idCoqual != 78 || idFncValidite == 't')
        {
             disableInput('btnValider');
              // alert('3');
        }
        if(matrSession != 6548 && matrSession != 7530 && matrSession != 42 && matrSession != 5270)
        {
          disableInput('btnSupprimer');
          // alert('4');
        }
        // alert(matrSession);
        // alert('c');
        function disableInput(sel)
        {
            $('#'+sel).attr({'disabled':'disabled'});
            $('#'+sel).css({background: 'white',cursor: 'default'});
        
        }
        
        
      </script>
 