<?php 
	require_once("/var/www.cache/dgconn.inc");
	
	if (!$conn){
		echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
	}
	else{		
		$zParam = $_REQUEST['zParam'];
		if(isset($zParam)){
			$sqlSelect = "SELECT libelle FROM ";
			if ($zParam == "Action") $sqlSelect .= " nc_action_liste ";
			else $sqlSelect .= " nc_cause_liste ";
			$sqlSelect .= " WHERE type != 'curative' ORDER BY libelle ASC;";
				
			$reqSelect = @pg_query ($conn, $sqlSelect);
				
			for ($i = 0 ; $i < @pg_num_rows ($reqSelect) ; $i++){
				$resSelect = @pg_fetch_array ($reqSelect, $i);				
				$tzResult[$i] = $resSelect['libelle'];
			}
				
			$zResult = implode("<br />", $tzResult);
			echo $zResult;
		}
		unset ($zParam);	
	}
?>