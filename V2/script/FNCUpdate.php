<?php

require_once "/var/www.cache/dgconn.inc";
$iCounter = 0;

if(!$conn)
{
	echo "<font style='color: red; font-size: 12px; font-weight: bold'></font>";
}
else
{
	if(isset($_REQUEST['fnc_nb']))
	{
		$dBeginDate		= $_REQUEST['txtDate1'];
		$dEndingDate	= $_REQUEST['txtDate2'];
		
		$iNbFnc			= $_REQUEST['fnc_nb'];
      echo "ato".$iNbFnc ."<br/>";
		for($i = 0; $i < $iNbFnc; $i ++)
		{
			$iFncId			= $_REQUEST['fnc_id_' . $i] ;
         echo $iFncId."<br/>" ;
			$sProcess		= $_REQUEST['slctProcess' . $iFncId] ;
			$sClassement	= $_REQUEST['slctClassement' . $iFncId] ;
			$sModule		   = $_REQUEST['slctModule' . $iFncId] ;
			$sTypo			= $_REQUEST['txtTypo' . $iFncId] ;
			$sImputation	= $_REQUEST['slctImputation' . $iFncId] ;
			$sTypologie		= $_REQUEST['slctTypologie' . $iFncId] ;
         //Modif by Fulgence 20150219
         $txtGravit     = $_REQUEST['txtGravit'] ;
         $txtFrequency  = $_REQUEST['txtFrequency' ;
         
         echo $txtGravit."-".$txtFrequency ;exit;
         
			
			$aTypologie		= @pg_fetch_array(@pg_query($conn, "SELECT typologie_id FROM nc_typologie WHERE typologie_libelle ILIKE '%{$sTypologie}%'"));
			$iTypologieId	= (int)$aTypologie['typologie_id'];
			$aImputation	= @pg_fetch_array(@pg_query($conn, "SELECT imputation_id FROM nc_imputation WHERE imputation_libelle ILIKE '%{$sImputation}%'"));
			$iImputationId	= (int)$aImputation['imputation_id'];
			
			$sSqlUpdate		= "	UPDATE	nc_fiche 
								SET		fnc_process		= '{$sProcess}',
										fnc_classement	= '{$sClassement}',
										fnc_module		= '{$sModule}',
										fnc_typo		= '{$sTypo}' ";
			if(empty($sTypologie)) 
            $sSqlUpdate .= ", fnc_typologie = null" ;
			else 
            $sSqlUpdate .= ", fnc_typologie = '{$iTypologieId}'" ;
            
			if(empty($sImputation)) 
            $sSqlUpdate .= ", fnc_imputation = null" ;
			else 
            $sSqlUpdate .= ", fnc_imputation = '{$iImputationId}' " ;
			
         
         $sSqlUpdate		.= " WHERE	fnc_id			= '{$iFncId}' ; " ;
         
			//$rQueryUpdate	= @pg_query($conn, $sSqlUpdate);
			if(!$rQueryUpdate) $iCounter ++;
		}
	}
	
	if($iCounter > 0) $sMsg = urlencode("Quelques erreurs sont survenues durant l'enregistrement, veuillez r&eacute;essayer!");
	else $sMsg = urlencode("Enregistrement effectu&eacute; avec succ&egrave;s!");
	
	echo "	<script type='text/javascript'>
				document.location.href='FNCAdmin.php?admin_msg={$sMsg}&txtDate1={$dBeginDate}&txtDate2={$dEndingDate}';
			</script>";
}

/**
 * end of FNCUpdate.php
 * location : webservices
 */