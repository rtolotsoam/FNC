<?php
	
foreach($_POST['chek'] as $keys => $value)
{
	echo '<input type="hiden" name="chek['.$keys.']" value='.$value.' >' ;
	echo "<input type='hiden' id='txtFncId2' name='txtFncId2'  value=". $value ." />" ;

	
	$enregAct = $_GET['enregAct'] ;
	echo "".$enregAct.'val'.$value;

	if ($enregAct == 1)
	{
		echo "quinternaire" ;
		// S�lection ou insertion de libelle action
		$sActionLibelle = addslashes ($_REQUEST['txtAction']);
		
		/**
		 * rechercher dans la base si l'action donn�e existe
		 */
		$sSqlFindAction = "SELECT id FROM nc_action_liste WHERE libelle = '{$sActionLibelle}';";
		echo $sSqlFindAction."<br/>" ;
		$rQueryFindAction = @pg_query($conn, $sSqlFindAction) or die(@pg_last_error($conn));
		$iActionNb = @pg_num_rows($rQueryFindAction);
		
		/**
		 * s'il n'existe pas, faire une insertion
		 */
		if ($iActionNb == 0) {
			$sSqlInsertAction = "INSERT INTO nc_action_liste (libelle, type) VALUES ('{$sActionLibelle}', '{$sType}');";
			echo "ty".$sSqlInsertAction."<br/>" ;
			
//					$rQueryInsertAction = @pg_query($conn, $sSqlInsertAction) or die(@pg_last_error($conn));
		}
		/**
		 * r�cup�rer l'identifiant de l'action
		 */
		$aResActionId = @pg_fetch_array($rQueryFindAction);
		$iFindActionId = $aResActionId['id'];
		echo "::".$iFindActionId."<br/>" ;

		/**
		 * variables pass�es en param�tre
		 */
		$iFncId = $value;
		
		$sFaille = addslashes ($_REQUEST['txtFaille']) ;
		echo "eto".$sFaille;
		$sImpact = addslashes ($_REQUEST['txtImpact']) ;
		$sActionLibelle = addslashes ($_REQUEST['txtAction']) ;
		$sDateDeb = $_REQUEST['txtDateDeb'] ;
		$iResponsable = $_REQUEST['txtResponsable'] ;
		$sDateFin = $_REQUEST['txtDateFin'] ;
		$sGeneralisation = addslashes ($_REQUEST['txtGeneral']) ;
		$sEtat = addslashes ("non entam�") ;
		$sType = "corrective / preventive" ;
		
		/**
		 * fin r�cup�ration variables
		 */
		
		/**
		 * s�lectionner l'id de l'action donn�e dans la base
		 */
		$sSqlSelectActionId = "SELECT * FROM nc_fnc_action WHERE fnc_id = '{$iFncId}' ";
		echo ":ty:".$sSqlSelectActionId ;
		$rQuerySelectActionId = @pg_query($conn, $sSqlSelectActionId) or die(@pg_last_error($conn));
		$iIdActionNb = @pg_num_rows($rQuerySelectActionId) ;
		
		/**
		 * faire une insertions dans la tables nc_fnc_action pour la fiche en question
		 */

		if ($iIdActionNb == 0)
		{
			$sSqlInsertFncAction = "INSERT INTO nc_fnc_action (action_liste_id, fnc_id, date_debut, date_fin, responsable, faille_identifiee, impact, generalisation, etat) 
									VALUES ('{$iFindActionId}', '{$iFncId}', '{$sDateDeb}', '{$sDateFin}', '{$iResponsable}', '{$sFaille}', '{$sImpact}', '{$sGeneralisation}', '{$sEtat}') ; ";
			echo "ta".$sSqlInsertFncAction."<br/>";
			
			$rQueryInsertFncAction = @pg_query($conn, $sSqlInsertFncAction) or die(@pg_last_error($conn)) ;
		}
		else
		{
			$sSqlUpateFncAction = "UPDATE nc_fnc_action SET faille_identifiee = '{$sFaille}', impact ='{$sImpact}', generalisation = '{$sGeneralisation}', etat = '{$sEtat}' WHERE fnc_id = '{$iFncId}' ; " ;
			echo "".$sSqlUpateFncAction."<br/>" ;
			
			//$rQueryUpdateAction = @pg_query($conn, $sSqlUpateFncAction) ;
		}
	}
}
?>