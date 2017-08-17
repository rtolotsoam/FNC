<?php

/**
 * @author 
 * @copyright 2010
 */

require_once ("DBConnect.php") ;

		$iId = $_REQUEST['txtId'] ;
		$zRef = $_REQUEST['txtRef'] ;
		
		$zSqlDeleteFNC = "DELETE FROM nc_fiche WHERE fnc_id = '$iId' AND fnc_statut != 'bouclé'" ;
		$zSqlDeleteAction = "DELETE FROM nc_action WHERE \"action_fncId\" = '$iId' " ;
		$oReqDeleteFNC = @pg_query($zSqlDeleteFNC) ;
		$oReqDeleteAction = @pg_query($zSqlDeleteAction) ;
		
		if (($oReqDeleteFNC)&&($oReqDeleteAction))
		{
			$_SESSION['MSGSearch'] = "<font color=\"black\">Derni&egrave;re action :</font> suppression de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> suppression effectu&eacute;e" ;
			echo "<script type=\"text/javascript\">document.location.href=\"FNCSearch.php\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCSearch.php\"/>" ;
		}
		else{
			$_SESSION['MSGSearch'] = "<font color=\"black\">Derni&egrave;re action :</font> suppression de la fiche ayant la r&eacute;f&eacute;rence : ".$zRef."<br /><font color=\"black\">Statut :</font> erreur lors de la suppression" ;
			echo "<script type=\"text/javascript\">document.location.href=\"FNCSearch.php\"</script>";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=FNCSearch.php\"/>" ;
		}

?>
