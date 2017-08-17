<?php
include ("/var/www.cache/dgconn.inc") ;

$zClientAffiche = $_REQUEST['zClientAffiche'];
$zCodezeroAffiche = $_REQUEST['zCodezeroAffiche'];
$zCpMatricule = $_REQUEST['zCpMatricule'];
$zPseudoCP = $_REQUEST['zPseudoCP'];
?>

<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = iso-8859-1">

	<title>ajoutFNC</title>

	<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>
	<script type="text/javascript" src="../js/ui.draggable.js"></script>
	<script type="text/javascript" src="../js/ui.dialog.js"></script>
	<script type="text/javascript" src="../js/ui.datepicker.js"></script>


	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
	<script type="text/javascript" src="../js/FNCAjout.js"></script>

	<script type="text/javascript">
	$(function() {
		$("#txtDateRep").datepicker();
	});

	</script>

</head>

<body>

<?php
	require_once ("DBConnect.php");
	echo "<p style = \"color: #999999; padding-left: 10%;\">".$_SESSION["MSGAjout"]."</p>";
?>

	<form id = "frmFNCAjout" name = "frmFNCAjout" method = "post">
	<fieldset>
		<legend>Identifiant de la Fiche de Non Conformit&eacute;</legend>
		<br />
		<table width = "100%"  border = "0" cellspacing = "1" cellpadding = "1">
			<tr>
		    	<td>Type : </td>
		    	<td colspan="3">
					<select id = "slctTypeFNC" name = "slctTypeFNC" class = "slct">
				  		<option value = "">***** fa&icirc;tes votre choix *****</option>
				  		<option value = "audit">audit</option>
				  		<option value = "client">client</option>
				  		<option value = "interne">interne</option>
		    		</select>
		    		<span class = "style2">*</span>
				</td>
		  	</tr>
			<tr>
		    	<td width="25%">Nom du client : </td>
		    	<td colspan="3">
					<input type = "text" id = "txtClientName" name = "txtClientName" class = "txtInput" style="text-transform: uppercase" onfocus="removeStar ()" value="<?php echo $zClientAffiche; ?>">
		      		<span class = "style2">*</span>
				</td>
		  	</tr>
		  	<tr>
			    <td>Code de la commande : </td>
			    <td colspan="3">
					<!--<input type = "text" id = "txtCode" name = "txtCode" class = "txtInput" style="text-transform:uppercase" onBlur = "fillMatCpFromCommande ()"  value="<?php //echo $zCodezeroAffiche; ?>">-->
					<select name="txtCode" id="txtCode" class = "slct">
						<?php

								$code3 = substr($zCodezeroAffiche, 0, 3);
								$zSqlCmd = "select idcommande from commande where idcommande ilike '$code3%' and flaggpao = 'o' ; ";

								$resCmd = @pg_query($conn, $zSqlCmd);
								$irows = @pg_num_rows($resCmd);
								
								for ($i = 0 ; $i < $irows ; $i++)
								{
										$arrCmd = @pg_fetch_array ($resCmd, $i) ;
										$zidcommande = $arrCmd['idcommande'] ;
										
										echo "<option value='$zidcommande'>$zidcommande</option>" ;
								}
						?>
					</select>	
			    	<span class = "style2" id="starCMD">*</span>
				</td>
		  	</tr>
		  	<tr>
			    <td>Matricule CP / CPM : </td>
			    <td width="25%">
					<input type = "text" id = "txtCP" name = "txtCP" class = "txtInput" onkeypress="return filtrerTouche(event)" value="<?php echo $zCpMatricule; ?>"/>
			    	<span class = "style2" id="starCP">*</span>
			    </td>
		  	    <td width="15%">Pr&eacute;nom CP / CPM : </td>
		  	    <td width="35%"><input type = "text" id = "txtCPPrenom" name = "txtCPPrenom" class = "txtInput" value="<?php echo $zPseudoCP; ?>"/></td>
		  	</tr>
		  	<tr>
		    	<td>Commercial : </td>
		    	<td colspan="3">
					<select id = "txtComm" name = "txtComm" class = "slct">
						<option value="">***** fa&icirc;tes votre choix *****</option>
						<?php
							$zSqlComm = @pg_query ($conn, "SELECT comm_libelle FROM nc_comm ORDER BY comm_libelle ASC") ;

							for ($i=0; $i<@pg_num_rows ($zSqlComm); $i++)
							{
								$toData = @pg_fetch_array ($zSqlComm, $i) ;
								$zValue = $toData['comm_libelle'] ;

								$zSelected = "" ; // intialisation de la variable de sélection

								if ($zValue == $txtComm)
									$zSelected = "selected" ;

								echo "<option value=\"" . $zValue . "\" $zSelected >" . $zValue ."</option>" ;
							}
						?>
		    		</select>
		    	</td>
		  	</tr>

		  	<tr>
		    	<td>Motif de cr&eacute;ation de la fiche : </td>
		    	<td colspan="3"><textarea id = "txtMotif" name = "txtMotif" class = "txtArea"></textarea><span class = "style2">*</span></td>
		  	</tr>
		  	<tr>
		    	<td>Exigences du client sur la commande : </td>
		    	<td colspan="3"><textarea id = "txtExigence" name = "txtExigence" class = "txtArea"></textarea></td>
		  	</tr>
		  	<tr>
		    	<td>&nbsp;</td>
		    	<td colspan="3">&nbsp;</td>
		  	</tr>
		  	<tr>
<!-- ------------------------------------------------------------------------------------------------------ -->
				  <input type="hidden" id="txtAjoutAction" name="txtAjoutAction">
<!-- ------------------------------------------------------------------------------------------------------ -->
		    	<td><input type = "reset" id = "btnCancelAjout" name = "btnCancelAjout" value = "R&eacute;initialiser" class = "ui-state-default"></td>
			    <td colspan="2"><input type = "button" id = "btnAjoutFNC" name = "btnAjoutFNC" value = "Ouvrir une FNC" class = "ui-state-default" onClick="verifChampAjoutFNC();
				"></td>
				<td><input type = "button" id = "btnGPAO" name = "btnGPAO" value = "Retour GPAO" onclick="window.location.href='../../gpao/accueil_gpao.php'" class = "ui-state-default"></td>
		  	</tr>
		</table>
	</fieldset>
	</form>

<?php
	if (isset ($_REQUEST["txtAjoutAction"]))
	{
		$zType = $_REQUEST["slctTypeFNC"] ;	
	
		$zCode = pg_escape_string ($_REQUEST["txtCode"]) ;
		if (($zType == "interne")&&($_REQUEST["txtCode"] == "")) $zRef = "NC_INTERNE_".date ("ymd"); 
			elseif(($zType == "audit")&&($_REQUEST["txtCode"] == "")) $zRef = "NC_AUDIT_".date ("ymd");
				else $zRef = "NC_".strtoupper($zCode[0].$zCode[1].$zCode[2])."_".date("ymd");
				
		$zSqlSelect = "SELECT fnc_ref FROM nc_fiche WHERE fnc_ref LIKE '$zRef%'";
		$oQueryRequete = pg_query($zSqlSelect);
		if(pg_num_rows($oQueryRequete) == 0) $zRef .="";
			else $zRef .= "(".pg_num_rows($oQueryRequete).")"; 
	
		$zClientName = strtoupper(pg_escape_string($_REQUEST["txtClientName"])) ;
	
		$iCP = $_REQUEST["txtCP"] ;
		$zComm = pg_escape_string($_REQUEST["txtComm"]) ;
			$zCreationDate = date("Y-m-d");		//automatique lors de la création de la fiche
			$zCreationHour = date("H:i:s");
		
		$zMotif = pg_escape_string($_REQUEST["txtMotif"]) ;
		$zExigence = pg_escape_string($_REQUEST["txtExigence"]) ;

			if ($zType == "client"){ 
				$zTraitStatut = "en cours";
				$zACStatut = "en cours";
			}
				elseif($zType == "audit"){
					$zTraitStatut = "en cours";
					$zACStatut = "";
				}  
					else{
						$zTraitStatut =  "en attente" ;
						$zACStatut = "";
					} 
									
			$zANCStatut = "";
			
		$iCreateur = $_SESSION["matricule"] ;
		
		$COQUAL = findGroup($conn) ;

		if (($zType == "client")||($COQUAL == 78)||($zType == "audit")) $zValide = "true" ; else $zValide = "false" ;
		$iVersion =  "1";			//par defaut

		$zSqlInsertFNC = "INSERT INTO nc_fiche (";
			if (!empty ($zCode)) $zSqlInsertFNC .= "fnc_code, ";
		$zSqlInsertFNC .= "fnc_ref, ";
			if (!empty($iCP)) $zSqlInsertFNC .= "fnc_cp, ";
		$zSqlInsertFNC .= "fnc_comm, \"fnc_creationDate\", fnc_type, fnc_motif, fnc_exigence, fnc_statut, fnc_createur, fnc_valide, fnc_client, fnc_version, \"fnc_creationHour\", \"fnc_actionCStatut\", \"fnc_actionNCStatut\") VALUES (";
			if (!empty($zCode)) $zSqlInsertFNC .= "'$zCode', ";
		$zSqlInsertFNC .= "'$zRef', ";
			if (!empty($iCP)) $zSqlInsertFNC .= "'$iCP', ";
		$zSqlInsertFNC .= "'$zComm', '$zCreationDate', '$zType', '$zMotif', '$zExigence', '$zTraitStatut',  '$iCreateur', '$zValide', '$zClientName', '$iVersion', '$zCreationHour', '$zACStatut', '$zANCStatut')" ;

		//echo $zSqlInsertFNC ;
		//exit ;

		$oInsertFNC = @pg_query ($conn, $zSqlInsertFNC) ;

		/**	*************************** récupération de la dernière enregistrement ************************** **/
		$resSelect = @pg_query("SELECT fnc_id, fnc_ref FROM nc_fiche ORDER BY fnc_id DESC LIMIT 1") ;
		$toDonnee = @pg_fetch_array($resSelect) ;
		$iId = $toDonnee['fnc_id'] ;
		$zRef = $toDonnee['fnc_ref'] ;

		if ($zType == "client")
		{
			@pg_query ($conn, "INSERT INTO nc_action (\"action_fncId\", \"action_debDate\", action_description, action_responsable, action_etat, action_type) VALUES ('$iId', '$zCreationDate', 'Accusé de réception de la réclamation', '$iCreateur', 'en cours', 'curative') ") ;
		}
		/** ************************************************************************************************* **/

		if (!$oInsertFNC)
		{
			//$_SESSION['MSGAjout'] = $zSqlInsertFNC ;
			$_SESSION["MSGAjout"] = "<font color=\"black\">Derni&egrave;re action :</font> ouverture de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> erreur lors de l'ouverture de la FNC." ;
			echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCAjout.php\"/>" ;
		}
		else
		{
			if ($_REQUEST['txtAjoutAction'] == "true")
			{
				//$_SESSION['MSGAjout'] = $zSqlInsertFNC ;
				$_SESSION["MSGAjout"] = "<font color=\"black\">Derni&egrave;re action :</font> ouverture de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> fiche ouverte " ;
				echo "<script type=\"text/javascript\">document.location.href=\"FNCAjout.php?txtId=$iId&txtRef=$zRef\"</script>";
				//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCAjout.php?txtId=".$iId."&txtRef=".$zRef."\"/>" ;
			}
			else
			{
			}
		}
	}

?>
</body>
</html>