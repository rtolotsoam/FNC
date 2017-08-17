<?php
/**
* Créé le : 22/04/2010 par : Fano
* Modifié le : 22/04/2010 par : Fano
*/

// Data base connexion
//***************************************************************************************************
// Inclure les paramètres dans le cache du serveur 
include("/var/www.cache/dgconn.inc"); 
/***************************************************************************************************/

//Traitement
if (!$conn)
{
	echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
}
else
{		
	$resSqlFindMatCP = @pg_query ($conn, "SELECT matriculecp, prenompersonnel FROM commande INNER JOIN personnel ON commande.matriculecp = personnel.matricule WHERE idcommande = '" . $_REQUEST["zCommandId"] . "' LIMIT 1 ; ") ;
	
	$tzDataFindMatCP = @pg_fetch_array ($resSqlFindMatCP, 0) ;

	$iCpMatFound = $tzDataFindMatCP["matriculecp"] ;

	if (empty ($iCpMatFound))
		$iCpMatFound = 0 ;

	print ($iCpMatFound . "_" . $tzDataFindMatCP["prenompersonnel"]) ;
}

?>
