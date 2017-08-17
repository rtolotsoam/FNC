<?php

	/**
	 * Créé le 04/07/2012 par Fulgence
	 * Modifié le 04/07/2012 par Fulgence
	 * 
	 */

	// ------- Accès à la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
   // print_r($_REQUEST); 
   // curative
   $edit = trim($_REQUEST['edit']);
   $txtId = trim($_REQUEST['txtId']);
   $txtRef = trim($_REQUEST['txtRef']);
   
   echo "<input type='hidden' value='".$txtId."' id='hideId'>";
   

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Admin Actions Correctives</title>
	<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
	<link rel="stylesheet" type="text/css" href="../css/jquery.autocomplete.css" />

	<script type="text/javascript" src="../js/FNCAdminActionCorrective.js"></script>
	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="../js/jquery-block-ui.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>
	<script type="text/javascript" src="../js/ui.draggable.js"></script>
	<script type="text/javascript" src="../js/ui.dialog.js"></script>
	<script type="text/javascript" src="../js/ui.datepicker.js"></script>
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="../js/FNCAjout.js"></script>
	<script type="text/javascript">
	$(document).ready (function () {
		$("#isset_submit").val("0");
		$("#tabAdmin").tablesorter ({
			//sortMultiSortKey: 'altKey',
			headers: { 5: { sorter: false} }
		}) ;

			$("#txtDateDeb").datepicker({ minDate : 0 });
			$("#txtDateFin").datepicker({ minDate : 0 });
		/*
		// autocomplete
		$.get(
			"../webservices/fncAutoComplete.php", 
			{zParam : "Action"},				
			function(_iData){	
				data = _iData.split("<br />");
				$("#txtAction").autocomplete(data) ;
			});
		
		// Function pour remplissage des champs
		$("#txtAction").blur(function() {
			
			$.get(
				"../webservices/fncFillAAC.php",
				{
					act_libelle : $("#txtAction").val(),
					//idFnc :	$("#txtFncId").val()
				},
				function(result) {
					data=result.split("___");
					$("#txtDateDeb").val(data[0]);
					$("#txtResponsable").val(data[1]);
					$("#txtDateFin").val(data[2]);
					//$("#txtDateFin").val(data[3]);
				})
		});
		*/
	});

	// Function d'enregistrement de l'assignation d'action corrective
	function EnregAssignation ()
	{
		txtFaille = document.getElementById('txtFaille') ;
		txtImpact = document.getElementById('txtImpact') ;
		txtDateDeb = document.getElementById('txtDateDeb') ;
		txtAction = document.getElementById('txtAction') ;
		txtResponsable = document.getElementById('txtResponsable') ;
		txtDateFin = document.getElementById('txtDateFin') ;
		txtGeneral = document.getElementById('txtGeneral') ;
		var hideIdState = $('#hideIdState').val() ;
      if(hideIdState =='on') 
      {
        
         txtFaille = $('#txtFaille').val();
         txtImpact = $('#txtImpact').val();
         txtDateDeb = $('#txtDateDeb').val();
         txtAction = $('#txtAction').val();
         txtResponsable = $('#txtResponsable').val();
         txtDateFin = $('#txtDateFin').val();
         txtGeneral = $('#txtGeneral').val();
         txtEfficacite = $('#txtEfficacite').val();
         idHide = $('#hideId').val();
         txtObjectif = $('#txtObjectif').val();
         txtEfficacite = $('#txtEfficacite').val();
         
          if(confirm ("Etes-vous sur que les informations saisies sont exactes ?"))
          {
           	$.ajax({
                  type:'POST',
                  dataType : 'html',
                  url :'FNCAssignationCorreciteViaSearch.php',
                  data:{
                     fnc_id   : idHide,
                     txtFaille     : txtFaille,
                     txtImpact     : txtImpact,
                     txtDateDeb : txtDateDeb,
                     txtAction        : txtAction,
                     txtResponsable   : txtResponsable,
                     txtDateFin       : txtDateFin,
                     txtObjectif      : txtObjectif,
                     txtEfficacite    : txtEfficacite,
                     txtGeneral       : txtGeneral
                    
                  },
                  success:function(sdata){
               
                     
                       if(sdata == 1)
                       {
                           txtFaille = $('#txtFaille').val('');
                           txtImpact = $('#txtImpact').val('');
                           txtDateDeb = $('#txtDateDeb').val('');
                           txtAction = $('#txtAction').val('');
                           txtResponsable = $('#txtResponsable').val('');
                           txtDateFin = $('#txtDateFin').val('');
                           txtGeneral = $('#txtGeneral').val('');
                           txtGeneral = $('#txtEfficacite').val('');
                           idHide = $('#hideId').val();
                           $('#hideId').val('');
                           txtObjectif = $('#txtObjectif').val('');
                           txtEfficacite = $('#txtEfficacite').val('');
                          
                          
                            window.location.href = 'FNCModifier.php?txtId='+idHide;
                       }
                       else
                       {
                          alert('Insertion erreur');
                       }
                    
                  }
               });
         return 0;
        }
      }
      

		if (txtFaille.value == "")
		{
			alert("Veuillez renseigner la Faille") ;
			txtFaille.focus () ;
			// $ ("#txtFaille").css ({backgroundColor:'#FFFFCC'}) ;
			$ ("#txtFaille").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtImpact.value == "")
		{
			alert('Veuillez s\351lectionner un impact') ;
			txtImpact.focus () ;
			$ ("#txtImpact").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtDateDeb.value == "")
		{
			alert('Veuillez s\351lectionner une date d\351but') ;
			txtDateDeb.focus () ;
			$ ("#txtDateDeb").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtAction.value == "")
		{
			alert('Veuillez s\351lectionner une action') ;
			txtAction.focus () ;
			$ ("#txtAction").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtResponsable.value == "")
		{
			alert('Veuillez s\351lectionner un responsable') ;
			txtResponsable.focus () ;
			$ ("#txtResponsable").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtDateFin.value == "")
		{
			alert('Veuillez s\351lectionner une date fin') ;
			txtDateFin.focus () ;
			$ ("#txtDateFin").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else if (txtGeneral.value == "") // 
		{
			alert('Veuillez s\351lectionner une g\351n\351ralisation') ;
			txtGeneral.focus () ;
			$ ("#txtGeneral").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}
      else if (txtEfficacite.value == "") // 
		{
			alert('Veuillez s\351lectionner Indicateur d\'efficacit\351') ;
			txtGeneral.focus () ;
			$ ("#txtEfficacite").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}
       else if (txtObjectif.value == "") // 
		{
			alert('Veuillez s\351lectionner Objectif et \351ch\351ance') ;
			txtGeneral.focus () ;
			$ ("#txtObjectif").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}

		else
		{
			if(confirm ("Etes-vous sur que les informations saisies sont exactes ?")){
				$("#isset_submit").val("1");
				document.getElementById('frmAssignation').action = "FNCAssignActionCorrective.php" ;
				document.getElementById('frmAssignation').submit() ;
			}
		}
	}

	</script>

</head>

	<body>
   <span id="idsp"></span>
		<form id="frmAssignation" name="frmAssignation" method="post" action="">

			<fieldset>
				<legend>Assignation action corrective</legend>
				<br />
				<table width="45%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
					<input type='hidden' name='a_fnc' id='a_fnc' value = '<?php echo serialize($_POST['chek']); ?>' />
						<td colspan="2" width="30%">Faille identifi&eacute;e : </td>
						<td width="15%">
							<textarea id = "txtFaille" name = "txtFaille" class = "txtArea"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2">Impact  : </td>
						<td>
							<textarea id = "txtImpact" name = "txtImpact" class = "txtArea"></textarea>
						</td>
					</tr>
					<tr>
						<td rowspan="5" style="padding-top:30px; padding-left:0px;">Action corrective :</td>
					</tr>
					<tr>
						<td>Action :</td>
						<td>
							<textarea id="txtAction" name="txtAction" class="txtArea" ></textarea>
						</td>
					</tr>
					<tr>
						<td>Date de d&eacute;but d'actions :</td>
						<td><input type="text" id="txtDateDeb" name="txtDateDeb" class="txtInput" value="" readonly /></td>
					</tr>
					<tr>
						<td>Responsable :</td>
						<td><input type="text" id="txtResponsable" name="txtResponsable" class="txtInput" value="" /></td>
					</tr>
					<tr>
						<td>Date fin :</td>
						<td><input type="text" id="txtDateFin" name="txtDateFin" class="txtInput" value="" readonly /></td>
					</tr>
						
					<tr>
						<td colspan="2">G&eacute;n&eacute;ralisation : </td>
						<td><textarea id = "txtGeneral" name = "txtGeneral" class = "txtArea"></textarea></td>
					</tr>
               <tr>
						<td colspan="2">Indicateur d&lsquo;efficacit&eacute;  : </td>
						<td><textarea id = "txtEfficacite" name = "txtEfficacite" class = "txtArea"></textarea></td>
					</tr>
                <tr>
						<td colspan="2">Objectif et &eacute;ch&eacute;ance   : </td>
						<td><textarea id = "txtObjectif" name = "txtObjectif" class = "txtArea"></textarea></td>
					</tr>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr>
		<!-- ------------------------------------------------------------------------------------------------------ -->
						<input type="hidden" id="txtIdFicheAction" name="txtIdFicheAction">
		<!-- ------------------------------------------------------------------------------------------------------ -->
						<td>
							<input type="reset" id="btnCancelAssignAction" name="btnCancelAssignAction" value="R&eacute;initialiser"  class = "ui-state-default" >
						</td>
						<td>
                     <?php 
                        if($edit == 'on')
                        {
                        ?> 
                             <input type="button" id="btnRetour" name="btnRetour" value="Annuler"  class = "ui-state-default" onclick="javascript: document.location.href = 'FNCModifier.php?txtId=<?php echo $txtId;?>&txtRef=<?php echo $txtRef;?>'">
                             <input type="hidden" value="on" id='hideIdState' />
                        <?php
                        }
                        else 
                        {
                        ?>
                           <input type="button" id="btnRetour" name="btnRetour" value="Annuler"  class = "ui-state-default" onclick="javascript: document.location.href = 'FNCAdminActionCorrective.php'">
                        <?php 
                        }
                        
                     ?>
							
						</td>
						<td><input type="button" id="btnEnreg" name="btnEnreg" value="Enregistrer" class = "ui-state-default" onclick="EnregAssignation()">
						<input type = 'hidden' id = 'isset_submit' name = 'isset_submit' value = '0' /></td>
					</tr>
				</table>

			</fieldset>
		</form>
	</body>
</html>

<?php
   /*print_r($_REQUEST);*/
	if (isset($_REQUEST['isset_submit']) && $_REQUEST['isset_submit'] == 1) {
     
		$sFnc = $_REQUEST['a_fnc'];
		$aFnc = unserialize($sFnc);
		$iFncNb = sizeof($aFnc);
		$sRef = "";
		$concatFncId = "" ;

		for ($i = 0; $i < $iFncNb; $i ++) {
			// recuperation des references des fiches
			$aFncRef = @pg_fetch_array(@pg_query($conn, "SELECT fnc_ref FROM nc_fiche WHERE fnc_id = '{$aFnc[$i]}';"));
			if ($i == ($iFncNb - 1)) $sRef .= $aFncRef['fnc_ref']; else $sRef .= $aFncRef['fnc_ref'] . ", ";
			
			/**
			 * variables pass�es en param�tre
			 */
			$iFncId = $aFnc[$i];
			
			$sFaille = pg_escape_string (utf8_decode($_REQUEST['txtFaille'])) ;
			$sImpact = pg_escape_string (utf8_decode($_REQUEST['txtImpact'])) ;
			$sActionLibelle = pg_escape_string (utf8_decode($_REQUEST['txtAction'])) ;
			$sDateDeb = $_REQUEST['txtDateDeb'] ;
			$iResponsable = $_REQUEST['txtResponsable'] ;
			$sDateFin = $_REQUEST['txtDateFin'] ;
			$sGeneralisation = pg_escape_string (utf8_decode($_REQUEST['txtGeneral'])) ;
			$sEtat = pg_escape_string ("en attente") ;
			$sType = "corrective / preventive" ;
			
         $bIndicateurEfficacite = pg_escape_string (utf8_decode($_REQUEST['txtEfficacite'])) ;
         $bObjectifEcheance = pg_escape_string (utf8_decode($_REQUEST['txtObjectif'])) ;
         
			/**
			 * fin r�cup�ration variables
			 */
			
			/**
			 * rechercher dans la base si l'action donn�e existe
			 */
			$sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
						
			$rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn));
			$iActionNb = @pg_num_rows($rQueryFindAction);
			
			/**
			 * s'il n'existe pas, faire une insertion
			 */
			if ($iActionNb == 0) {
				$sSqlInsertAction = "INSERT INTO nc_action_liste (libelle, type) VALUES ('{$sActionLibelle}', '{$sType}');";
            // echo '</br>1-'.$sSqlInsertAction;
            // exit('xxxxxxxxxxx');
				$rQueryInsertAction = @pg_query($conn, $sSqlInsertAction) or die(@pg_last_error($conn));
			}
         
          // exit('Txx');
			/**
			 * récupérer l'identifiant de l'action
			 */
			$sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
						
			$rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn));
			
			$aResActionId = @pg_fetch_array($rQueryFindAction);
			$iFindActionId = $aResActionId['id'];
						
			/**
			 * sélectionner l'id de l'action donnée dans la base
			 */
			$sSqlSelectActionId = "SELECT * FROM nc_fnc_action WHERE fnc_id = '{$iFncId}' AND action_liste_id = '{$iFindActionId}' ; ";
			
			$rQuerySelectActionId = @pg_query($conn, $sSqlSelectActionId) or die(@pg_last_error($conn));
			$iIdActionNb = @pg_num_rows($rQuerySelectActionId) ;
			
			/**
			 * faire une insertions dans la tables nc_fnc_action pour la fiche en question
			 */
			
			if ($iIdActionNb == 0) {
				$sSqlInsertFncInfo = "	INSERT	INTO nc_fnc_infos ( date_debut, 
															date_fin, responsable, faille_identifiee, impact, 
															generalisation, etat,indic_efficacite,obj_echeance) 
											VALUES	( '{$sDateDeb}', 
													'{$sDateFin}', '{$iResponsable}', '{$sFaille}', 
													'{$sImpact}', '{$sGeneralisation}', '{$sEtat}', 
                                       '{$bIndicateurEfficacite}','{$bObjectifEcheance}') ; " ;
				//echo "ito".$sSqlInsertFncInfo."<br/>" ;
             // echo '</br>2-'.$sSqlInsertFncInfo;
            // exit('yyyyyyyyyyyyyy');
				$rQueryInsertFncInfo = @pg_query($conn, $sSqlInsertFncInfo) or die(@pg_last_error($conn)) ;
				
				$sSqlSelectInfoId = " SELECT last_value as lastval from nc_fnc_infos_id_seq ";
				$rLastId = @pg_query($conn, $sSqlSelectInfoId) or die(@pg_last_error($conn));
				$aResLastId= @pg_fetch_array($rLastId);
				$last_Id= $aResLastId['lastval'];
				
				$sSqlInsertFncAction= "	INSERT	INTO nc_fnc_action ( action_liste_id, fnc_id, fnc_info_id) 
											VALUES	( '{$iFindActionId}', '{$iFncId}','{$last_Id}') ; " ;
				// echo '</br>3-'.$sSqlInsertFncAction;
				$rQueryInsertFncAction = @pg_query($conn, $sSqlInsertFncAction) or die(@pg_last_error($conn)) ;
				// (action_liste_id, fnc_id) values ('{$iFindActionId}', '{$iFncId}')
				
				
			} else {
         // exit('Tyyy');
				$sSqlUpateFncAction = "	UPDATE	nc_fnc_infos 
												SET	fnc_id = '{$iFncId}', 
												date_debut = '{$sDateDeb}', 
												date_fin = '{$sDateFin}', 
												responsable = '{$iResponsable}', 
												faille_identifiee = '{$sFaille}', 
												impact ='{$sImpact}', 
												generalisation = '{$sGeneralisation}', 
												etat = '{$sEtat}',
                                    indic_efficacite = {$bIndicateurEfficacite}',
                                    obj_echeance = {$bObjectifEcheance}'
										WHERE	fnc_id = '{$iFncId}' ; " ;
				
				$rQueryUpdateAction = @pg_query($conn, $sSqlUpateFncAction) ;
			}
		}

echo "<script type = 'text/javascript'>
var hideIdState = document.getElementById('hideIdState') ;
if(hideIdState == 'on')
{

}
else
{
   document.location.href = 'FNCAdminActionCorrective.php?fnc_ref_res={$sRef}&action_res={$sActionLibelle}';
}
</script>";

	}
?>