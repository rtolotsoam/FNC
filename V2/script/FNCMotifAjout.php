<?php

	/**
	 * Créé le 17/07/2012 par Fulgence
	 * Modifié le 17/07/2012 par Fulgence
	 * 
	 */

	// ------- Accès à la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
	
	$typ_motif = utf8_decode($_REQUEST['txtMotif']) ;
	$libelle_motif = pg_escape_string($_REQUEST['txtLibelle']) ;
	$ref = trim($_REQUEST['txtRef']) ;
	//echo "tu".$ref."<br/>";
	// Selection d'id dans la table nc_fiche
	$sSqlSlctId = "SELECT fnc_id, fnc_ref FROM nc_fiche WHERE fnc_ref = '{$ref}'" ;
	
	$rQueryId = @pg_query ($conn, $sSqlSlctId) or die(@pg_last_error($conn)) ;

	$aIdFiche = @pg_fetch_array($rQueryId) ;
	$iIdFiche = $aIdFiche['fnc_id'] ;
	//echo $iIdFiche ;
	$iRef = $aIdFiche['fnc_ref'] ;
	
	// Insertion de motif
	if (isset($_REQUEST['isset_submit']) && $_REQUEST['isset_submit'] == 1) {
		$type_motif = $_REQUEST['txtTypeMotif'] ;
		$iFichId = $_REQUEST['txtFicheId'] ;
		
		$sFicheRef = $_REQUEST['txtFicheRef'] ;

		// Insertion dans la table nc_motif
		$sSqlInsertMotif = "INSERT INTO nc_motif (libelle, type_motif, fnc_id) VALUES ('{$libelle_motif}', '{$type_motif}', '{$iFichId}')" ;
		//echo $sSqlInsertMotif ;
		$rQueryMotif = @pg_query ($conn, $sSqlInsertMotif) or die(@pg_last_error($conn)) ;
		
		echo "<script type = 'text/javascript'>document.location.href = 'FNCConsulter.php?txtId={$iFichId}&txtRef={$sFicheRef}'</script>";

	}

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Ajout Motif</title>
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

		}) ;

		// Function d'insertion de motif
		function EnregMotif ()
		{
			txtLibelle = document.getElementById('txtLibelle') ;

			if (txtLibelle.value == "")
			{
				alert("Veuillez renseigner la description du motif") ;
				txtLibelle.focus () ;
				$ ("#txtLibelle").css ({backgroundColor:'#FFFFCC'}) ;
				return 0 ;
			}
			else{
				if(confirm ("Etes-vous sur que les informations saisies sont exactes ?")){
					$("#isset_submit").val("1");
					document.getElementById('frmAjoutMotif').action = "FNCMotifAjout.php" ;
					document.getElementById('frmAjoutMotif').submit() ;
				}
			}
			
		}

		</script>

	</head>

	<body>

		<form id="frmAjoutMotif" name="frmAjoutMotif" method="post" action="">

			<fieldset>
				<legend>Insertion de motif</legend>
				<br />
				<table width="45%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="2" width="30%">Type motif : </td>
						<td width="15%">
							<input type="text" id="txtMotifType" name="txtMotifType" class="txtInput" value="<?php echo $typ_motif ; ?>" />
						</td>
					</tr>
					
					<tr>
						<td colspan="2" width="30%">Libelle : </td>
						<td width="15%">
							<textarea id = "txtLibelle" name = "txtLibelle" class = "txtArea"></textarea>
						</td>
					</tr>

					<tr>
		<!-- ------------------------------------------------------------------------------------------------------ -->
						<input type="hidden" id="txtTypeMotif" name="txtTypeMotif" value="<?php echo $_REQUEST['txtMotif'] ; ?>">
						
						<input type="hidden" id="txtFicheRef" name="txtFicheRef" value="<?php echo $iRef ; ?>">

						<input type="hidden" id="txtFicheId" name="txtFicheId" value="<?php echo $iIdFiche ; ?>">
		<!-- ------------------------------------------------------------------------------------------------------ -->
						<td>
							<input type="reset" id="btnCancelAjout" name="btnCancelAjout" value="R&eacute;initialiser"  class = "ui-state-default" >
						</td>
						<td>
							<input type="button" id="btnRetour" name="btnRetour" value="Annuler"  class = "ui-state-default" onclick="javascript: document.location.href = 'FNCConsulter.php?txtRef=<?php echo $ref ; ?>'">
						</td>
						<td><input type="button" id="btnEnreg" name="btnEnreg" value="Enregistrer" class = "ui-state-default" onclick="EnregMotif()">
						<input type = 'hidden' id = 'isset_submit' name = 'isset_submit' value = '0' /></td>
					</tr>
				</table>

			</fieldset>
		</form>
	</body>
</html>