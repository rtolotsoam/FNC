<?php
require_once ("../script/DBConnect.php");

$sqlBU = "select distinct *from business_unit order by lib_bu asc";

$rQueryBU= @pg_query ($conn, $sqlBU);
$nRows = @pg_num_rows ($rQueryBU);
$sAudit ="<option value=''>***** fa&icirc;tes votre choix *****</option>";
for ($i = 0; $i < $nRows; $i++) 
{
	$aData = @pg_fetch_array ($rQueryBU, $i) ;
	$idBU = $aData['id_bu'] ;
   $libBu = $aData['lib_bu'] ;
	$sAudit .= "<option value='{$idBU}' >{$libBu}</option>";
	
}
echo $sAudit;
?>