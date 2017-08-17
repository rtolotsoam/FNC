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
				$("input#txtDescript").autocomplete(data) ;
			});

	});
	</script>

</head>
<body>	

<?php
	require_once ("DBConnect.php");
	$iId = $_REQUEST['txtId'] ;
	$zRef = $_REQUEST['txtRef'] ;
	
	echo "<p style = \"color: #999999; padding-left: 10%;\">".$_SESSION["MSGAjout"]."</p>";
?>

	<form id="frmAjoutAutreAction" name="frmAjoutAutreAction" method="post">
	<fieldset>
		<legend>
			Ajouter une action &agrave; la FNC ayant la r&eacute;f&eacute;rence <?php echo $zRef ; ?> .
		</legend>
		<br />
		<table width="100%"  border="0" cellspacing="1" cellpadding="1">

		  	<tr>
				<td>Date de d&eacute;but : </td>
				<td colspan="2">
					<input type="text" id="txtDateDeb" name="txtDateDeb" class="txtInput" readonly="readonly">
					<span class="style2">*</span>
				</td>
		  	</tr>
		  	<tr>
				<td>Date de fin  : </td>
				<td><input type="text" id="txtDateFin" name="txtDateFin" class="txtInput" readonly="readonly" ></td>
		 	</tr>
		  	<tr>
				<td>Descriptions :</td>
				<td><input type="text" id="txtDescript" name="txtDescript" class="txtInput" ></td>
		  	</tr>
		  	<tr>
				<td>Matricule du responsable : </td>
				<td><input type="text" id="txtResponsable" name="txtResponsable" class="txtInput" onkeypress="return filtrerTouche(event);"></td>
		  	</tr>
		  	<tr>
				<td>Etat de traitement : </td>
				<td> 
					<select id="slctTraitStatutAction" name="slctTraitStatutAction" class="slct" onfocus="verifDate()">
				  		<option value="">***** fa&icirc;tes votre choix *****</option>
				  		<option value="en attente">En attente</option>
				  		<option value="en cours">En cours</option>
				  		<option value="ok">OK</option>
					</select>      
					<span class="style2">*</span>
				</td>
		  	</tr>
		  	<tr>
				<td>Type :</td>
				<td>
					<select id="slctType" name="slctType" class="slct">
					  	<option value="">***** fa&icirc;tes votre choix *****</option>
					  	<option value="corrective">Corrective</option>
					  	<option value="curative">Curative</option>
					  	<option value="preventive">Pr&eacute;ventive</option>
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
					<input type="button" id="btnRetour" name="btnRetour" value="Retour"  class = "ui-state-default" onclick="javascript: document.location.href = 'FNCAjout.php' ">
				</td>
				<td><input type="button" id="btnAjoutAction" name="btnAjoutAction" value="Ajouter une action"  class = "ui-state-default" onclick="verifChampAjoutAction();"></td>
		 	</tr>
		</table>
	</fieldset>
	</form>

<?php
	if (isset ($_POST["txtAjoutAutreAction"]))
	{
		$iId = $_REQUEST['txtId'] ;
		$zRef = $_REQUEST['txtRef'] ;
		
		//$iPilote = $_POST['txtPilote'] ;	
		$zDateDeb = $_POST['txtDateDeb'] ;						//
		$zDateFin = $_POST['txtDateFin'] ;	
		$zDescript = pg_escape_string($_POST['txtDescript']) ;
		$iResponsable = $_POST['txtResponsable'] ; 
		$zTraitStatutAction = $_POST['slctTraitStatutAction'] ;  //
		$zType = $_POST['slctType'] ;							//
		
		$zSql = "INSERT INTO nc_action (\"action_fncId\", \"action_debDate\", \"action_finDate\", action_description, action_responsable, action_etat, action_type)
			VALUES ('$iId', '$zDateDeb', '$zDateFin', '$zDescript', '$iResponsable', '$zTraitStatutAction', '$zType')";
		//echo "tessst ".$zSql."<br/>" ;
		$sqlInsert = @pg_query ($conn, $zSql) ;

		if (!$sqlInsert)
		{
			$_SESSION['MSGAjout'] = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> erreur lors de l'attribution de l'action";
			echo "<script type=\"text/javascript\">document.location.href=\"FNCAjoutAction.php\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCAjoutAction.php\"/>" ;
		}
		else
		{
			if ($_POST['txtAjoutAutreAction'] == "true")
			{
				$_SESSION['MSGAjout'] = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> ajout effectu&eacute;";
				echo "<script type=\"text/javascript\">document.location.href=\"FNCAjoutAction.php\"</script>";
				//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCAjoutAction.php />" ;
			}
			else
			{
				$_SESSION['MSGAjout'] = "<font color=\"black\">Derni&egrave;re action :</font> ajout d'action &agrave; la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> ajout effectu&eacute;";
				echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php\"</script>";
				//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCAjout.php\"/>" ;
			}
		}
	}
		
?>

</body>
</html>