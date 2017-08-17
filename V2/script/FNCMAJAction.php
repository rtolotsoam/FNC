<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = iso-8859-1">
	<title>Ajout Action</title>

	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
	<link rel="stylesheet" type="text/css" href="../css/ui.all.css" />
	<link rel="stylesheet" type="text/css" href="../css/jquery.autocomplete.css" />

	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>
	<script type="text/javascript" src="../js/ui.draggable.js"></script>
	<script type="text/javascript" src="../js/ui.dialog.js"></script>
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="../js/ui.datepicker.js"></script>

	<script type="text/javascript" src="../js/FNCAjout.js"></script>

	<script type="text/javascript">

	$(function() {
		$("#txtDateDeb").datepicker();
		$("#txtDateFin").datepicker();

		$.get(
			"../webservices/fncAutoComplete.php", 
			{zParam : "Action"},				
			function(_iData){	
				data = _iData.split("<br />");
				$("textarea#txtDescript").autocomplete(data) ;
			});

	});
	</script>

</head>
<body>

<?php
	require_once ("DBConnect.php");

	$iId = $_REQUEST['txtId'] ;
	$zRef = $_REQUEST['txtRef'] ;
	$zCTemp = $_REQUEST['txtC'] ;

	if ($zCTemp == "C"){
		$zC = "curative";
		$zMSG = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> ajout d'une action curative effectu&eacute;";
	}
	else
	{
		$zC = "corrective / preventive" ;
		$zMSG = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> ajout d'une action corrective / pr&eacute;ventive effectu&eacute;";
	}

	echo "<p style = \"color: #999999; padding-left: 10%;\">".$_SESSION["MSGSearch"]."</p>";
?>

	<form id="frmAjoutAutreAction" name="frmAjoutAutreAction" method="post">
	<fieldset>
		<legend>
			Ajouter une action
			<?php
				if ($_REQUEST['txtC'] == "C") echo "curative " ; else echo "corrective"
			?>
			 &agrave; la FNC ayant la r&eacute;f&eacute;rence <?php echo $zRef ; ?> .
		</legend>
		<br />
		
		<table width="100%"  border="0" cellspacing="1" cellpadding="1">
		  	<tr>
				<td>Date de d&eacute;but : </td>
				<td colspan="2">
					<input type="text" id="txtDateDeb" name="txtDateDeb" class="txtInput">
					<span class="style2">*</span>
				</td>
		  	</tr>
		  	<tr>
				<td>Date de fin  : </td>
				<td><input type="text" id="txtDateFin" name="txtDateFin" class="txtInput"></td>
		 	</tr>
		  	<tr>
				<td>Description :</td>
				<td><textarea id="txtDescript" name="txtDescript" class="txtArea"></textarea></td>
		  	</tr>
		  	<tr>
				<td>Nom ou matricule du(des) responsable(s) : </td>
				<td><input type="text" id="txtResponsable" name="txtResponsable" class="txtInput"></td>
		  	</tr>
		  	<tr>
				<td>Etat de traitement : </td>
				<td>
					<select id="slctTraitStatutAction" name="slctTraitStatutAction" class="slct" onfocus="verifDate()" >
				  		<option value="">***** fa&icirc;tes votre choix *****</option>
				  		<option value="en attente">En attente</option>
				  		<option value="en cours">En cours</option>
				  		<option value="ok">OK</option>
					</select>
					<span class="style2">*</span>
				</td>
		  	</tr>

			<tr>
    			<td>&nbsp;</td>
    			<td>&nbsp;</td>
  			</tr>
			
			<tr>
<!-- ------------------------------------------------------------------------------------------------------ -->
				<input type="hidden" id="txtAjoutAutreAction" name="txtAjoutAutreAction">
<!-- ------------------------------------------------------------------------------------------------------ -->
				<td>
					<input type="reset" id="btnCancelAjoutAction" name="btnCancelAjoutAction" value="R&eacute;initialiser"  class = "ui-state-default" >
					<input type="button" id="btnRetour" name="btnRetour" value="Retour"  class = "ui-state-default" onclick="javascript: document.location.href = 'FNCModifier.php?txtId=<?php echo $iId ; ?>&txtRef=<?php echo $zRef ; ?>' ">
				</td>
				<td><input type="button" id="btnAjoutAction" name="btnAjoutAction" value="Ajouter action" class = "ui-state-default" onclick="verifChampAjoutAction();"></td>
		 	</tr>
		</table>

	</fieldset>
	</form>

<?php
	
	if (isset ($_REQUEST["txtAjoutAutreAction"]))
	{
		$zDateDeb = $_REQUEST['txtDateDeb'] ;
		$zDateFin = $_REQUEST['txtDateFin'] ;

		$zDescript = pg_escape_string($_REQUEST['txtDescript']) ;
		$iResponsable = pg_escape_string ($_REQUEST['txtResponsable']) ;
		$zTraitStatutAction = $_REQUEST['slctTraitStatutAction'] ;

		// test de date
		$dateRef = '2012-07-02' ;
		
		$sqlSelectDate = "SELECT \"fnc_creationDate\" FROM nc_fiche WHERE fnc_id = '{$iId}' " ;
		$rQueryDate = @pg_query($conn, $sqlSelectDate);
		$aResDate = @pg_fetch_array($rQueryDate);

		//if (strtotime($aResDate['fnc_creationDate']) < strtotime($dateRef)) {

			$zSql = "INSERT INTO nc_action (\"action_fncId\", \"action_debDate\",";
				if ($zDateFin != '') $zSql .= " \"action_finDate\",";
			$zSql .= " 	action_description, action_responsable, action_etat, action_type) VALUES ('$iId', '$zDateDeb',";
				if ($zDateFin != '') $zSql .= " '$zDateFin',";
			$zSql .= " '$zDescript', '$iResponsable', '$zTraitStatutAction', '$zC') ";
         //echo "testtt ".$zSql."<br/>" ;
         
			$sqlInsert = @pg_query ($conn, $zSql) ;

			/* ***** une fois qu'on ajoute une action, l'etat du fiche devient en cours ***** */
			if($zCTemp == "C") @pg_query("UPDATE nc_fiche SET \"fnc_actionCStatut\" = 'en cours', fnc_statut = 'en cours' WHERE fnc_id = '$iId' ");
				else @pg_query("UPDATE nc_fiche SET \"fnc_actionNCStatut\" = 'en cours', fnc_statut = 'en cours' WHERE fnc_id = '$iId' ");
			/**********************************************************************************/

			if (!$sqlInsert)
			{
				$_SESSION['MSGSearch'] = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> erreur lors de l'attribution de l'action, le matricule du responsable est peut &ecirc;tre trop long ou l'une des dates est au format incorrect.";
				echo "<script type=\"text/javascript\">document.location.href=\"FNCMAJAction.php?txtId=$iId&txtRef=$zRef&txtC=$zC\"</script>";
				//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCMAJAction.php?txtId=".$iId."&txtRef=".$zRef."&txtC=".$zC."\" />" ;
			}
			else
			{
				if ($_REQUEST['txtAjoutAutreAction'] == "true")
				{
					$_SESSION['MSGSearch'] = $zMSG ;
					echo "<script type=\"text/javascript\">document.location.href=\"FNCMAJAction.php?txtId=$iId&txtRef=$zRef&txtC=$zCTemp\"</script>";
					//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCMAJAction.php?txtId=".$iId."&txtRef=".$zRef."&txtC=".$zCTemp."\" />" ;
				}
				else {
					;
				}
			}
		//}
		
	}

?>

</body>
</html>