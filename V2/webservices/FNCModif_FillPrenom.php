<?php

include("/var/www.cache/dgconn.inc"); 

if (!$conn)
{
	echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
}
else
{
	$zMatricule = $_REQUEST['matrCP'] ;
	$zSqlPrenom = "SELECT prenompersonnel FROM personnel WHERE matricule = '$zMatricule' ";
							
	$resSqlPrenom = @pg_query ($conn, $zSqlPrenom) ;
	$res = @pg_fetch_array($resSqlPrenom) ;
		echo $res['prenompersonnel'] ;
}

?>
