<?php

include("/var/www.cache/dgconn.inc"); 

$echo = "";

if (!$conn)
{
	$echo .= "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
}
else
{
	$zImputation = $_REQUEST['valeur'] ;
	$zSqlTypologie = "	SELECT nc_typologie.typologie_id, nc_typologie.typologie_libelle
						FROM nc_typologie 
						/*
						INNER JOIN nc_imputation_typologie on nc_typologie.typologie_id = nc_imputation_typologie.\"nc_idTypologie\"
						INNER JOIN nc_imputation on nc_imputation.imputation_id = nc_imputation_typologie.\"nc_idImputation\"
						WHERE nc_imputation.imputation_id = '" .$zImputation. "'
						*/ 
						WHERE typologie_actif IS NULL
						ORDER BY nc_typologie.typologie_libelle " ;
	$resSqlTypologie = @pg_query ($conn, $zSqlTypologie) ;
	$echo .= "<option value = \"\">***** fa&icirc;tes votre choix *****</option>";
	for ($i = 0; $i < @pg_num_rows($resSqlTypologie); $i ++)
	{
		$res = @pg_fetch_array($resSqlTypologie, $i) ;
		$echo .= "<option value = \"" .$res['typologie_id']. "\" >" .$res['typologie_libelle']. "</option>\n" ;
	}
}

echo $echo;

?>
