<?php
	require_once("DBConnect.php") ;
	
	//----8120----------------------
	session_start();
	if(isset($_SESSION['h_fnc_presence']) && $_SESSION['h_fnc_presence']=="1"){
		$h_fnc_pres = "1";
		$h_fnc_nmclient = $_SESSION['h_fnc_nmclient'];
		$h_fnc_client = $_SESSION['h_fnc_client'];
		$h_fnc_dtc = $_SESSION['h_fnc_dtc'];
		$h_fnc_dtm = $_SESSION['h_fnc_dtm'];
		$h_fnc_code = $_SESSION['h_fnc_code'];
		$h_fnc_creat = $_SESSION['h_fnc_creat'];
		$h_fnc_cp = $_SESSION['h_fnc_cp'];
		$h_fnc_statcur = $_SESSION['h_fnc_statcur'];
		$h_fnc_type = $_SESSION['h_fnc_type'];
		$h_fnc_statcor = $_SESSION['h_fnc_statcor'];
		if((!isset($_REQUEST["onsearch"]) || $_REQUEST["onsearch"]!= 1) && (!isset($_REQUEST["search"]) || $_REQUEST["search"]!= 1)){
			header('Location: FNCSearch.php?txtCrationDate='.$h_fnc_dtc.'&txtModifDate='.$h_fnc_dtm. '&slctClientName='.$h_fnc_nmclient.'&slctCode='.$h_fnc_code.'&txtCreateurMatr='.$h_fnc_creat.'&txtCP='.$h_fnc_cp.'&slctTraitStatut='.$h_fnc_statcur.'&slctType='.$h_fnc_type.'&slctTraitStatutCor='.$h_fnc_statcor.'&onsearch=1');
		}
	}else{
		$h_fnc_pres = "0";
	}
	if(isset($_REQUEST["search"]) && $_REQUEST["search"]== 1){
		$txtvide = '';
		header('Location: FNCSearch.php?txtCrationDate='.$_REQUEST["txtCrationDate"].'&txtModifDate='.$_REQUEST["txtModifDate"]. '&slctClientName='.$_REQUEST["slctClientName"].'&slctCode='.$txtvide.'&txtCreateurMatr='.$txtvide.'&txtCP='.$txtvide.'&slctTraitStatut='.$txtvide.'&slctType='.$txtvide.'&slctTraitStatutCor='.$txtvide.'&onsearch=1');
	}
	//-----8120---------------------
   
	$zSelectRef = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC" ;
	$oQuerySelectRef = @pg_query($conn, $zSelectRef) or die(pg_last_error($conn)) ;
	$iNbSelectRef = @pg_num_rows($oQuerySelectRef) ;

	$zSql = "select *from (select *from (SELECT nc_fiche.fnc_id AS id, nc_fiche.fnc_client AS client, 
nc_fiche.fnc_type AS type, nc_fiche.fnc_ref AS reffnc, 
nc_action.\"action_debDate\" AS datedeb,
nc_action.\"action_finDate\" AS datefin, nc_action.action_description AS description, 
nc_action.action_responsable AS responsable ,
fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id FROM nc_fiche,
 nc_action WHERE nc_action.action_etat != 'ok' 
AND nc_fiche.fnc_id = nc_action.\"action_fncId\" AND 
nc_action.action_type = 'curative' ORDER BY nc_action.\"action_finDate\" ASC, nc_fiche.fnc_ref ASC ) as temp
left join 
(SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON 
            /*substring(f.fnc_code FROM 1 for 3) = a.code */
			 ( (substring(f.fnc_code FROM 1 for 3) = a.code and length(f.fnc_code) = 6 )
             or  (substring(f.fnc_code FROM 2 for 3) = a.code and length(f.fnc_code) = 7 ))
            INNER JOIN  business_unit 
            b ON b.id_bu = a.id_bu ) as temp2 on temp2.fnc_id =temp.id ) as temp3
            order by datedeb asc" ;
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
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css" />	
      
      <script type="text/javascript" src="../js/thickbox.js"></script>
      <script type="text/javascript" src="../js/FNCSearch.js"></script>
      <script type="text/javascript" src="../js/FNCAjout.js"></script>
      
      	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />
      
		<style>
         .titre{border-bottom: 1px solid black;border-right: 1px solid black;font-weight: bold;}
         .contenu{border-right: 1px solid black;}
         table,body{font-family: verdana;font-size: 11px;}
         .date_field {position: relative; z-index:100;}
         .wrapper {position: relative;height: 450px;overflow-y: auto;border:4px solid #1e5799;width:100%;direction:ltr;}
         div.ui-datepicker{font-size:12px;z-index:100000000;}
         #ui-datepicker-div ,.ui-datepicker { z-index: 99999 !important; }
		</style>

</head>

<body>
<form id="frmSearch" name="frmSearch" method="get" action="">
<center><span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /></br><b>Chargement...</b></span></center>
<span id='idWrapperCache' style='display:none;'>
<fieldset >

   <legend style="font-family: verdana;font-size:14px; "><b>Recherche de FNC</b></legend>

   <table>
      <tr>
         <td colspan="4">Veullez remplir l'un des champs suivants </td>
        
      </tr>
      <tr>
         <td>&nbsp;&nbsp;Nom du client : </td>
         <td>
         <span id="spnClient">
			    		<select id="slctClientName" name="slctClientName" class="<?php echo $sClass; ?>">
			    			<option value="">*** fa&icirc;tes votre choix ***</option>
			    		</select>
			    	</span>
         </td>
         <td>&nbsp;&nbsp;Code de la commande : </td>
         <td>
            <span id="spnCommand">
				    	<select id="slctCode" name="slctCode" class="<?php echo $sClass; ?>">
				    		<option value="">*** fa&icirc;tes votre choix ***</option>
				    	</select>
				    </span>
         </td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Fiche ouverte du</td>
         <td><input type="text" id="txtCrationDate" name="txtCrationDate" class="txtInput" value="<?php if(isset($_REQUEST["txtCrationDate"])) $zDateCeation1 = $_REQUEST["txtCrationDate"] ; else $zDateCeation1 = date("Y-m")."-01"; echo $zDateCeation1; ?>" readonly/></td>
         <td>&nbsp;&nbsp;au :</td>
         <td><input type="text" id="txtModifDate" name="txtModifDate" class="txtInput" value="<?php if(isset($_REQUEST["txtModifDate"])) $zDateCeation2 = $_REQUEST["txtModifDate"] ; else $zDateCeation2 = date("Y-m-d"); echo $zDateCeation2 ; ?>" readonly/></td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Cr&eacute;ateur : </td>
         <td>
         <?php
               $sqlListeCreateur = "SELECT DISTINCT prenompersonnel, matricule FROM personnel INNER JOIN nc_fiche ON personnel.matricule = nc_fiche.fnc_createur order by matricule " ;
					$zListeCreateur = @pg_query($conn, $sqlListeCreateur) ;
					$iNumListeCreateur = @pg_num_rows ($zListeCreateur) ;

?>					<select id = "txtCreateurMatr" name = "txtCreateurMatr" class = "slct">
		                <option value = "">
		                	*** fa&icirc;tes votre choix ***
		                </option>
<?php
		                for ($i = 0; $i < $iNumListeCreateur; $i++) {
							$toData = @pg_fetch_array ($zListeCreateur, $i) ;
							$iMatricule = $toData['matricule'] ;
							$zPrenom = $toData['prenompersonnel'] ;
							$zSelected = "" ; // intialisation de la variable de selection
							if(isset($_REQUEST["txtCreateurMatr"])) {
								if ($iMatricule == $_REQUEST["txtCreateurMatr"]) $zSelected = "selected" ;
							}
							echo "<option value='{$iMatricule}' {$zSelected}>{$iMatricule} - {$zPrenom}</option>" ;
						}
?>
					</select>
         </td>
         <td>&nbsp;&nbsp;CP :  </td>
         <td><input name="txtCP" type="text" id="txtCP" class="txtInput" onkeypress="return filtrerTouche(event);" value="<?php if(isset($_REQUEST['txtCP'])) echo $_REQUEST['txtCP']; ?>"></td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Statut du traitement des actions curatives: </td>
         <td>
         <select id="slctTraitStatut" name="slctTraitStatut" class="slct">
<!-- -------------------------------------------------------------------------------------------------------------- -->
						<option value = "" <?php if(isset($_REQUEST["slctTraitStatut"]) == "") echo " selected" ; ?>>
		                	*** fa&icirc;tes votre choix ***
		                </option>
						<option value="aucune_action_corrective"<?php if($_REQUEST["slctTraitStatut"] == "aucune_action_corrective") echo " selected" ; ?>>
							Actions non d&eacute;finies
						</option>
						<option value="avec_action"<?php if($_REQUEST["slctTraitStatut"] == "avec_action") echo " selected" ; ?>>
							Actions en cours
						</option>
						<option value="actions_correctives_terminees"<?php if($_REQUEST["slctTraitStatut"] == "actions_correctives_terminees") echo " selected" ; ?>>
							En attente validation
						</option>
						<option value="fiche_cloturee"<?php if($_REQUEST["slctTraitStatut"] == "fiche_cloturee") echo " selected" ; ?>>
							Cl&ocirc;tur&eacute;es
						</option>
<!-- -------------------------------------------------------------------------------------------------------------- -->
         </td>
         <td>&nbsp;&nbsp;Type :</td>
         <td>
            <select name="slctType" id="slctType" class="slct">
				  		<option value = "" <?php if ($_REQUEST['slctType'] == "") echo " selected" ; ?>>
				  			*** fa&icirc;tes votre choix ***
				  		</option>
				  		<option value = "audit" <?php if($_REQUEST['slctType'] != "audit") echo ""; else echo " selected" ; ?>>
					  		Audit
					  	</option>
					  	<option value = "client" <?php if($_REQUEST['slctType'] != "client") echo ""; else echo " selected" ; ?>>
					  		Client
					  	</option>
					  	<option value = "interne" <?php if($_REQUEST['slctType'] != "interne") echo ""; else echo " selected" ; ?>>
					  		Interne
					  	</option>
			      	</select>&nbsp;&nbsp;
         </td>
      </tr>
        <tr>
         <td>&nbsp;&nbsp;Statut du traitement des actions correctives: </td>
         <td>
            			  		<select id="slctTraitStatutCor" name="slctTraitStatutCor" class="slct">
<!-- -------------------------------------------------------------------------------------------------- -->
						<option value = "" <?php if(isset($_REQUEST["slctTraitStatutCor"]) == "") echo " selected" ; ?>>
		                	*** fa&icirc;tes votre choix ***
		                </option>
						<option value="aucune_action_corrective"<?php if($_REQUEST["slctTraitStatutCor"] == "aucune_action_corrective") echo " selected" ; ?>>
							Actions non d&eacute;finies
						</option>
						<option value="avec_action"<?php if($_REQUEST["slctTraitStatutCor"] == "avec_action") echo " selected" ; ?>>
							Actions en cours
						</option>
						<option value="actions_correctives_terminees"<?php if($_REQUEST["slctTraitStatutCor"] == "actions_correctives_terminees") echo " selected" ; ?>>
							En attente validation
						</option>
						<option value="fiche_cloturee"<?php if($_REQUEST["slctTraitStatutCor"] == "fiche_cloturee") echo " selected" ; ?>>
							Cl&ocirc;tur&eacute;es
						</option>
<!-- --------------------------------------------------------------------------------------------------- -->

					</select>
         </td>
         <td></td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4" align="center">
         </br>
         <input type="button" name="btnReset" id="btnReset" value="R&eacute;initialiser" class = "ui-state-default" onclick="reinitialiser()">
         &nbsp;&nbsp;&nbsp;
         <input type="button" name="btnSubmit" id="btnSubmit" value="Rechercher" class = "ui-state-default">
          </br>
         </td>
      </tr>
      
   </table>
   
</br>
</fieldset>
</br>

</span></br>
   <div class='wrapper' style='display:none;' id='idCache'>
      <?php
      require_once ("DBConnect.php");
      echo "<p style = \"color: #999999; padding-left: 10%; \">".$_SESSION["MSGSearch"]."</p>";

      $slctClientName      = $_REQUEST['slctClientName'] ;
      $slctCode            = $_REQUEST['slctCode'] ;
      $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
      $txtModifDate        = $_REQUEST['txtModifDate'] ;
      $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;   
      $s_txtCP             = $_REQUEST['txtCP'] ;
      $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
      $slctType            = $_REQUEST['slctType'] ;
      $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;

      $fnc_motif = $_REQUEST['motif_fnc'] ;

   if(isset($_REQUEST["search"]) == 1) 
   {
     
   }

	//if (!isset($_REQUEST['bntSubmit']))
	else{
    // echo $_REQUEST["search"]; exit("x");
     
     
      //echo "te ".$txtCrationDate." fef" ;
     /*  $zSqlFNCduJour  = "select *from(";
      $zSqlFNCduJour .= "SELECT * FROM nc_fiche WHERE \"fnc_creationDate\" BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-d")."' ORDER BY \"fnc_creationDate\" DESC, \"fnc_creationHour\" DESC" ;
       $zSqlFNCduJour  .= ") as temp
            left join 
            (
            SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON 
            substring(f.fnc_code FROM 1 for 3) = a.code 
            INNER JOIN  business_unit 
            b ON b.id_bu = a.id_bu 
            ) as temp2 on temp.fnc_id=temp2.fnc_id"; */
     $clauseDate = "";
     if(isset($_REQUEST['txtModifDate']) && isset($_REQUEST['txtCreateurMatr']))
     {
         $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
         $txtModifDate        = $_REQUEST['txtModifDate'] ;
         $clauseDate = "BETWEEN '".$txtCrationDate ."' and '".$txtModifDate."'";
     }
     else
     {
         $txtCrationDate      = date("Y-m")."-01";
         $txtModifDate        = date("Y-m-d");
         $clauseDate = "BETWEEN'".$txtCrationDate ."' and  '".$txtModifDate."'";
     } 
        
      $onsearch = $_REQUEST['onsearch'] ;
       $zSqlFNCduJour = "";
         /*    $zSqlFNCduJour = "select (case when fnc_bu is not null then (select bu.lib_bu as xx from nc_fiche ncf inner join business_unit bu on bu.id_bu= ncf.fnc_bu where ncf.fnc_bu is not null) else 'x' end ) as bu_audit,*from(SELECT * FROM nc_fiche WHERE \"fnc_creationDate\"
            BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-d")."' ORDER BY \"fnc_creationDate\" DESC, 
            \"fnc_creationHour\" DESC) as temp left join ( SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON substring(f.fnc_code FROM 1 for 3) = a.code INNER JOIN business_unit b ON b.id_bu = a.id_bu 
            ) as temp2 on temp.fnc_id=temp2.fnc_id";   */
            
        $zSqlFNCduJour ="select res3.fnc_id, (case when (bu_fnc is  null and lib_bu <>'') then lib_bu else bu_fnc end ) as bu_fnc2,fnc_code,fnc_ref,fnc_cp,fnc_comm,\"fnc_creationDate\",
               fnc_type,fnc_motif,fnc_exigence,fnc_statut,fnc_cause,\"fnc_reponseDate\",\"fnc_reponseRef\",fnc_createur,
               fnc_validateur,\"fnc_modifDate\",\"fnc_modif_Matricule\",
               fnc_valide,fnc_client,fnc_version,fnc_imputation,  fnc_typologie, \"fnc_creationHour\",\"fnc_actionCStatut\",\"fnc_actionNCStatut\",fnc_process,fnc_classement,
               fnc_module,  fnc_typo,fnc_autre_cplmnt,fnc_traitement,fnc_id_grille_application,fnc_id_notation, fnc_gravite_id,fnc_frequence_id,
               fnc_freq_cat_id,fnc_grav_cat_id from (SELECT *FROM nc_fiche WHERE \"fnc_creationDate\"   $clauseDate  ORDER BY \"fnc_creationDate\" DESC, \"fnc_creationHour\" DESC) as res3
               left join (
               SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
               INNER JOIN gu_application a ON 	 ( (substring(f.fnc_code FROM 1 for 3) = a.code and length(f.fnc_code) = 6 )
             or  (substring(f.fnc_code FROM 2 for 3) = a.code and length(f.fnc_code) = 7 )
             or  (f.fnc_code = a.code and length(f.fnc_code) = 3 )
             )
               INNER JOIN business_unit b ON b.id_bu = a.id_bu 
               ) as res4 on res3.fnc_id = res4.fnc_id
               left join (
               select *from (
               select (case when fnc_bu is not null then xx else 'xx' end ) as bu_fnc, *from (
               select bu.lib_bu as xx,fnc_bu,fnc_id from nc_fiche ncf inner join business_unit bu on bu.id_bu= ncf.fnc_bu where ncf.fnc_bu is not null ) as res_bu ) as res2
               ) as res5 on res3.fnc_id = res5.fnc_id
"; /*<<< requete pour rechercher fnc onload*/
         
      if(isset($onsearch) && ($onsearch == 1))
      {
         $slctClientName      = $_REQUEST['slctClientName'] ;
         $slctCode            = $_REQUEST['slctCode'] ;
         $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
         $txtModifDate        = $_REQUEST['txtModifDate'] ;
         $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;   
         $s_txtCP             = $_REQUEST['txtCP'] ;
         $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
         $slctType            = $_REQUEST['slctType'] ;
         $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;
         $fnc_motif = $_REQUEST['motif_fnc'] ;
         
         
         // print_r($_REQUEST);
         $zSqlFNCduJour .= "
         WHERE 1=1";                   
         if (isset($fnc_motif))
            $zSqlFNCduJour .= " AND fnc_motif ilike '%$fnc_motif%' " ;
                        
         if($zCode != "") $zSqlFNCduJour .= " AND (fnc_code LIKE '%$zCode%')" ;
         if($zCreateurMatr != '') $zSqlFNCduJour .= " AND (fnc_createur ILIKE '%$zCreateurMatr%')" ;
         if($slctClientName != "") $zSqlFNCduJour .= " AND (fnc_client ILIKE '%$slctClientName%')" ;
         
         if ($_REQUEST['slctTraitStatut'] == "aucune_action_corrective") {
            $zSqlFNCduJour .= " AND	((\"fnc_actionCStatut\" = '') || (\"fnc_actionCStatut\" = 'en attente')  ";
         } elseif ($_REQUEST['slctTraitStatut'] == "avec_action") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionCStatut\" = 'en cours') ";
         } elseif ($_REQUEST['slctTraitStatut'] == "actions_correctives_terminees") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionCStatut\" = 'ok') ";
         } elseif ($_REQUEST['slctTraitStatut'] == "fiche_cloturee") {
            $zSqlFNCduJour .= " AND fnc_statut = 'bouclé' ";
         } else {
            $zSqlFNCduJour .= "";
         }
         
         
         if ($_REQUEST['slctTraitStatutCor'] == "aucune_action_corrective") {
            $zSqlFNCduJour .= " AND	((\"fnc_actionNCStatut\" = '') || (\"fnc_actionNCStatut\" = 'en attente') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "avec_action") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionNCStatut\" = 'en cours') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "actions_correctives_terminees") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionNCStatut\" = 'ok') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "fiche_cloturee") {
            $zSqlFNCduJour .= " AND fnc_statut = 'bouclé' ";
         } else {
            $zSqlFNCduJour .= "";
         }
         

         if($zClient != '') $zSqlFNCduJour .= " AND (fnc_client LIKE '%$zClient%')" ;
         if($zCP != '') $zSqlFNCduJour .= " AND (fnc_cp LIKE '%$zCP%')" ;
         if($zType != '') $zSqlFNCduJour .= " AND (fnc_type LIKE '%$zType%')" ;
         $zSqlFNCduJour .= " ORDER BY \"fnc_creationDate\", \"fnc_creationHour\" ASC" ;
         
            
      }
    // echo  $zSqlFNCduJour;
		$oFNCduJour = @pg_query($conn, $zSqlFNCduJour) ;
		$iNbFNCduJour = @pg_num_rows($oFNCduJour) ;

		if($iNbFNCduJour != 0)
		{
      
      // $contentFieldset2 ="
      // <fieldset>
			// <legend>R&eacute;sultats</legend>";
      $contentTable2 ="
      
         <table width='100%' cellspacing='1' cellpadding='1' id='table2' >
            <thead>
               <tr >
                  <th width='5%'>Code</th>
                  <th width='9%'>Client</th>
                  <th width='3%'>BU</th>
                  <th width='12%'>R&eacute;f&eacute;rence</th>
                  <th width='7%'>Ouverte le</th>
                  <th width='8%'>Cr&eacute;&eacute;e par</th>
                  <th width='6%'>Valid&eacute;e</th>
                  <th width='9%'>Motif cr&eacute;ation FNC</th>
                  <th width='5%'>Type</th>
                  <th width='6%'>Version</th>
                  <th width='7%'>Criticit&eacute;</th>
                  <th width='6%'>&nbsp;</th>
                  <th width='6%'>&nbsp;</th>
               </tr>
            </thead>
            <tbody>
      ";
      
      for($i = 0; $i < $iNbFNCduJour; $i ++)
			{
				$toFNCduJour = @pg_fetch_array($oFNCduJour) ;
				$sqlCreater = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$toFNCduJour['fnc_createur'] ;
				$resCreater = @pg_query($conn, $sqlCreater) ;
				$oCreateur = @pg_fetch_array($resCreater,0) ;
				$couleur = ($i%2 == 0 ? "odd":"");
            
            $hidden = "";
            $fncIdHidden = $toFNCduJour['fnc_id'];
            $fncRefHidden =$toFNCduJour['fnc_ref'];
            $hidden .= "
            <input type='hidden' id='ID_{$i}'value='{$fncIdHidden}'>
            <input type='hidden' id='REF_{$i}'value='{$fncRefHidden}'>
            ";
            
            echo $hidden;
            
            if ($toFNCduJour['fnc_valide'] == 'f') 
             $fncValide = 'non'; 
            else 
               $fncValide = 'oui' ;
               
            if($toFNCduJour['fnc_freq_cat_id'] !='' && $toFNCduJour['fnc_freq_cat_id'] !='') 
            {
                $cat_id_grav = $toFNCduJour['fnc_grav_cat_id'] ;
                $cat_id_freq = $toFNCduJour['fnc_freq_cat_id'] ;
                
                 // gravité
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav='$cat_id_grav' " ;
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
               elseif ($grv_ech == 3 && $frq_ech == 4)   
                  $criticite = "C" ;
               elseif($grv_ech >= 4)
                  $criticite = "C" ;
               else
                  $criticite = "p" ;
               
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
            $color = ($i%2 == 0 ? "odd" :"style='background-color:#FFFFFF;font-weight:bold;'") ;
            $criticite = "" ;
         }

				$client = $toFNCduJour['fnc_client'] ;
				if (trim($toFNCduJour['fnc_client']) == 'Autres') 
               $client = $toFNCduJour['fnc_autre_cplmnt'];
           
            $toFNCduJourAudit_buTab2 =trim($toFNCduJour['bu_audit']);
           if($toFNCduJourAudit_buTab2 != 'x')
           {
               $toFNCduJourLib_buTab2 =$toFNCduJourAudit_buTab2;
           }
           else
           {
               $toFNCduJourLib_buTab2 =$toFNCduJour['lib_bu'];
           }
           
           $toFNCduJourCodeTab2 = $toFNCduJour['fnc_code'];
           $toFNCduJourLib_buTab2 = $toFNCduJour['bu_fnc2'];
          
           
           $toFNCduJourFnc_refTab2 =$toFNCduJour['fnc_ref'];
           $toFNCduJourFnc_creationDateTab2 =$toFNCduJour['fnc_creationDate'];
           $toFNCduJourFnc_creationDateTab2 =$toFNCduJour['fnc_creationDate'];
           $toFNCduJourFnc_motifTab2 =$toFNCduJour['fnc_motif'];
           $toFNCduJourFnc_typeTab2 =$toFNCduJour['fnc_type'];
           $toFNCduJourFnc_versionTab2 =$toFNCduJour['fnc_version'];
           $toFNCduJourFnc_idTab2 =$toFNCduJour['fnc_id'];
           
           $contentTable2 .="
           <tr id='btnMenu' class='".$couleur."'>
               <td width='5%'>".$toFNCduJourCodeTab2."</td>
               <td width='9%'>".$client."</td>
               <td width='3%'>".$toFNCduJourLib_buTab2."</td>
               <td width='12%'>".$toFNCduJourFnc_refTab2."</td>
               <td width='7%'>".$toFNCduJourFnc_creationDateTab2."</td>
               <td width='8%'>".$oCreateur['prenompersonnel']."&nbsp;(".$toFNCduJour['fnc_createur'].")</td>
               <td width='5%'>".$fncValide."</td>
               <td width='16%'>".$toFNCduJourFnc_motifTab2."</td>
               <td width='6%'>".$toFNCduJourFnc_typeTab2."</td>
               <td width='7%'>".$toFNCduJourFnc_versionTab2."</td>
               <td width='7%' $color >".$criticite."</td>
           ";
			$contentTable2 .="
         <td  align='center' width='7%'>
            <input type='button' id='btnConsulter_".$toFNCduJourFnc_idTab2."' name='btnConsulter' value='consulter' class = 'ui-state-default'  onclick=viewFNC(".$toFNCduJourFnc_idTab2."); >
         </td>
         ";	
         
         
      $bfnc_id = $toFNCduJour['fnc_id'];
      $bfnc_ref = $toFNCduJour['fnc_ref'];
      $bslctClientName = $slctClientName;
      $bslctCode = $slctCode;
      $bzDateCeation1 = $zDateCeation1;
      $bzDateCeation2 = $zDateCeation2;
      $btxtCreateurMatr = $txtCreateurMatr;
      $bs_txtCP = $s_txtCP;
      $bslctTraitStatut = $slctTraitStatut;
      $bslctType = $slctType;
      $bslctTraitStatutCor = $slctTraitStatutCor;
      $bfnc_motif = $fnc_motif;
      
      $contentTable2 .="
      <td  align='center' width='7%'>   
         <input type='button' id='btnModifier_".$bfnc_id."' name='btnModifier' value='Modifier' class = 'ui-state-default'  onclick=editFNC(".$bfnc_id.",0); >
      </td>
      ";
      
       

			}
      $contentTable2 .="
      </tr>";
		}
     $contentTable2 .="</tbody></table>";
	}
   echo $contentTable2;
      ?>
      
   </div>
</form>
<script type="text/javascript">
      
   $(function () {
    $("#idCache").show();
    $("#idWrapperCache").show();
    // $("#idContentCache").show();
    $("#imLoad").hide();
    $('#table2').tablesorter({
    
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
     11: { sorter: false}, 12: { sorter: false} } 
   });});
   fillClient('search', '<?php if (isset($_REQUEST['slctClientName'])) echo $_REQUEST['slctClientName']; ?>');
   fillCommand('search', '<?php if (isset($_REQUEST['slctCode'])) echo $_REQUEST['slctCode']; ?>');

   $(function() {
   $("#txtCrationDate").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
   $("#txtModifDate").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
   });

	function reinitialiser(){
		$("#slctCode").val("");
		$("#txtRef").val("");
		$("#txtComm").val("");
		$("#slctClientName").val("");

		$("#txtCrationDate").val("<?php echo date("Y-m-")."01" ?>");
		$("#txtModifDate").val("<?php echo date('Y-m-d') ?>");

		$("#txtCreateurMatr").val("");
		$("#txtCP").val("");
		$("#slctTraitStatut").val("");
		$("#slctType").val("");
	}
   function viewFNC(idFNC)
   {
      var widthScreen = screen.width;
         widthScreen -= 50;
         // alert('ppp'+idFNC);return 0;
      tb_show('','FNCConsulter.php?height=600&width='+widthScreen+'&idFNC='+idFNC);
      widthScreen = screen.width;
   }
   function editFNC(idFNC,test)
   {
      // alert(idFNC);
      var widthScreen = screen.width;
         widthScreen -= 50;
      tb_show('','FNCConsulter.php?height=600&width='+widthScreen+'&idFNC='+idFNC+'&varTest='+test);
      widthScreen = screen.width;
   }
   $(document).ready(function(){
   $("#btnSubmit").on('click',function(){
  // document.location.replace('FNCModifier.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc);
  /*
    $slctClientName      = $_REQUEST['slctClientName'] ;
      $slctCode            = $_REQUEST['slctCode'] ;
      $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
      $txtModifDate        = $_REQUEST['txtModifDate'] ;
      $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;   
      $s_txtCP             = $_REQUEST['txtCP'] ;
      $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
      $slctType            = $_REQUEST['slctType'] ;
      $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;
      
      $fnc_motif = $_REQUEST['motif_fnc'] ;
  */
      var txtCrationDate = $("#txtCrationDate").val();
      var txtModifDate = $("#txtModifDate").val();
      var slctClientName = $("#slctClientName").val();
      //alert('xx'+slctClientName);
      if(slctClientName == undefined || slctClientName == '' || slctClientName == 'undefined'){
         slctClientName = "";
     }else{
	 	slctClientName = $('#slctClientName').find('option:selected').html();
	 }
      var slctCode = $("#slctCode").val();
      var txtCreateurMatr = $("#txtCreateurMatr").val();
      var txtCP = $("#txtCP").val();
      var slctTraitStatut = $("#slctTraitStatut").val();
      var slctType = $("#slctType").val();
      var slctTraitStatutCor = $("#slctTraitStatutCor").val();
      $.post('injecte_session.php', {nmclient:slctClientName, client:$("#slctClientName").val(), dtc:$("#txtCrationDate").val(), dtm:$("#txtModifDate").val(), code:$("#slctCode").val(), creat:$("#txtCreateurMatr").val(), cp:$("#txtCP").val(), statcur:$("#slctTraitStatut").val(), type:$("#slctType").val(), statcor:$("#slctTraitStatutCor").val()}, function(reponse){//});
      document.location.replace('FNCSearch.php?txtCrationDate='+ txtCrationDate +'&txtModifDate='+txtModifDate + '&slctClientName='+slctClientName +'&slctCode='+slctCode+'&txtCreateurMatr='+txtCreateurMatr+'&txtCP='+txtCP+'&slctTraitStatut='+slctTraitStatut+'&slctType='+slctType+'&slctTraitStatutCor='+slctTraitStatutCor+'&onsearch=1');});
   });
});
    </script>
	</body>
</html>
