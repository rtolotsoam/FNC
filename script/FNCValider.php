<?php

/**
 * @author 
 * @copyright 2010
 */
 
require_once ("DBConnect.php") ;

		$iId = $_REQUEST['txtId'] ;
		$zRef = $_REQUEST['txtRef'] ;
		
		$zValidateur = $_SESSION['matricule'] ;
		
		$zSqlUpdateValide = "UPDATE nc_fiche SET fnc_valide = true, fnc_validateur = '$zValidateur', fnc_statut = 'en cours' WHERE fnc_id = '$iId' " ;
//echo $zSqlUpdateValide ;exit;
		$oReqUpdateValide = @pg_query($zSqlUpdateValide) ;

		if ($oReqUpdateValide)
		{
			$_SESSION['MSGSearch'] = "<font color=\"black\">Derni&egrave;re action :</font> validation de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> fiche valid&eacute;e" ;
			echo "<script type=\"text/javascript\">document.location.href=\"FNCConsulter.php?idFNC=$iId\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCConsulter.php?txtId=".$iId."&txtRef=".$zRef."\"/>" ;
		}
		else{
			$_SESSION['MSGSearch'] = "<font color=\"black\">Derni&egrave;re action :</font> validation de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> erreur lors de la validation" ;
			echo "<script type=\"text/javascript\">document.location.href=\"FNCConsulter.php?txtId=$iId&txtRef=$zRef\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCConsulter.php?txtId=".$iId."&txtRef=".$zRef."\"/>" ;
		}
		
?>