<?php 

	/**
	 * Créé le 03/07/2012 par Fulgence
	 * Modifié le 03/07/2012 par Fulgence
	 * 
	 */

	// ------- Accès à la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
	
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = iso-8859-1">
	<title> FNC </title>

	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
	<link rel="stylesheet" type="text/css" href="../css/ui.all.css" />

	
	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>	
	<script type="text/javascript" src="../js/ui.draggable.js"></script>
	<script type="text/javascript" src="../js/ui.dialog.js"></script>	
	<script type="text/javascript" src="../js/ui.datepicker.js"></script>
	
	<script type="text/javascript" src="../js/FNCAjout.js"></script>

	<script type="text/javascript">
	$(document).ready (function () {
		document.getElementById('txtUser').focus();
		$("#tabUser").tablesorter ({
			sortMultiSortKey: 'altKey',
		}) ; 
	});
	/*
	// function pour le changement de base
	function baseChange ()
	{
		document.frmInsertUser.action = "smsBaseSelect.php" ;
		document.frmInsertUser.submit() ;
	}
	
	// Function d'insert new user
	function insertUser ()
	{
		txtUser = document.getElementById('txtUser') ;
		txtUserVal = document.getElementById('txtUser').value ;
		
		if (txtUserVal == "")
		{
			alert("Veuillez renseigner le nom d'user") ;
			txtUser.focus () ;
			$ ("#txtUser").css ({backgroundColor:'#FFFFCC'}) ;
			return 0 ;
		}
		else
		{
			
			document.frmInsertUser.action = "smsUserInsert.php?erg=1" ;
			document.frmInsertUser.submit() ;
		}
	}

	*/
	</script>
	
	</head>
	
	<body>
		<center>
			<h1>Insertion d'une action</h1>
				<form id="frmInsertUser" name="frmInsertUser" method="POST" action="smsUserInsert.php">
					<table>
						<tr>
							<td>Libelle :</td>
						
							<td>
								<input type="text" id="txtLibelle" name="txtLibelle" class="txtInput" value="<?php  ?>" />
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
							</td>
						</tr>
						
						<tr>
							<td></td>
							<td>
								<input type="button" id="btnValider" name="btnValider" value="Valider" class = "ui-state-default" onclick="insertAction ();"/>
							</td>
						</tr>
					</table>
				</form>
				<table>
					<tr>
						<td></td>
						<td><?php  ?></td>
					</tr>
				</table>
				<?php
				
				?>												
		</center>										
	</body>
</html>

<?php
	/**
	 * variables passées en paramètre
	 */
	$iFncId = $_REQUEST['fnc_id'];
	$sDateDeb = $_REQUEST['date_deb'];
	$sDateFin = $_REQUEST['date_fin'];
	$sType = $_REQUEST['slctType'];
	$sEtat = $_REQUEST['etat'];
	$iResponsable = $_REQUEST['responsable'];
	$iPilote = $_REQUEST['pilote'];

	$sActionLibelle = $_REQUEST['txtLibelle'];
	/**
	 * fin récupération variables
	 */
	/**
	 * rechercher dans la base si l'action donnée existe
	 */
	$sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
	$rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn));
	$iActionNb = @pg_num_rows($rQueryFindAction);
	/**
	 * s'il n'existe pas, faire une insertion
	 */
	if ($iActionNb == 0) {
		$sSqlInsertAction = "INSERT INTO nc_action_liste(libelle, type) VALUES('{$sActionLibelle}', '{$sType}');";
		$rQueryInsertAction = @pg_query($conn, $sSqlInsertAction) or die(@pg_last_error($conn));
	}
	/**
	 * sélectionner l'id de l'action donnée dans la base
	 */
	$sSqlSelectActionId = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
	$rQuerySelectActionId = @pg_query($conn, $sSqlSelectActionId) or die(@pg_last_error($conn));
	$aResActionId = @pg_fetch_array($rQuerySelectActionId);
	$iActionId = $aResActionId['id'];
	/**
	 * faire une insertions dans la tables nc_fnc_action pour la fiche en question
	 */
	$sSqlInsertFncAction = "INSERT INTO nc_fnc_action(action_liste_id, fnc_id, date_deb, date_fin, responsable, etat, pilote) 
							VALUES('{$aResActionId}', '{$iFncId}', '{$sDateDeb}', '{$sDateFin}', '{$iResponsable}', '{$sEtat}', '{$iPilote}');";
?>