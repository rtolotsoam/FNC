<?php 
	require_once("/var/www.cache/dgconn.inc");
	
	if (!$conn){
		echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
	}
	else{/*
		$sActLibelle = $_REQUEST['act_libelle'] ;
		$iFncId = $_REQUEST['fnc_id'] ;
		if(isset($sActLibelle)){*/
			/*
			$aResId = @pg_fetch_array(@pg_query($conn, "SELECT id FROM nc_action_liste WHERE libelle = '{$sActLibelle}';"));
			$aResAction = @pg_fetch_array(@pg_query($conn, "SELECT date_deb, date_fin, responsable FROM nc_fnc_action WHERE fnc_id = '{}' AND action_list_id = '{}';")) ;
			*/
			$sSqlSelectId = "SELECT DISTINCT nal.id, nal.libelle FROM nc_action_liste nal, nc_fnc_action nfa WHERE nal.id = nfa.action_liste_id and nfa.etat= 'en cours' " ;
			echo "ty==> ".$sSqlSelectId ;
			$rQueryId = @pg_query($conn, $sSqlSelectId) ;

		//}
	}
?>




<?php 
	require_once("/var/www.cache/dgconn.inc");
	
	if (!$conn){
		echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
	}
	else{
		$sActLibelle = $_REQUEST['act_libelle'] ;
		//$sActLibelle = 'testing' ;
		$iFncId = $_REQUEST['fnc_id'] ;
		//$iFncId = '45'
		if(isset($sActLibelle)){
			/*
			$aResId = @pg_fetch_array(@pg_querty($conn, "SELECT DISTINCT nal.id, nal.libelle FROM nc_action_liste nal, nc_fnc_action nfa WHERE nal.id = nfa.action_liste_id and nfa.etat = 'en cours' AND nal.libelle= '{$sActLibelle}'")) ;
			
			$aResAction = @pg_fetch_array(@pg_query($conn, "SELECT date_debut, date_fin, responsable FROM nc_fnc_action WHERE fnc_id = '{$iFncId}' ")) ;
			*/
			
			$sSqlSelectId = "SELECT DISTINCT nal.id, nal.libelle FROM nc_action_liste nal, nc_fnc_action nfa WHERE nal.id = nfa.action_liste_id and nfa.etat = 'en cours' AND nal.libelle= '{$sActLibelle}' " ;
			
			$rQueryId = @pg_query($conn, $sSqlSelectId) or die(@pg_last_error($conn)) ;
			
			for ($i = 0 ; $i < @pg_num_rows ($rQueryId) ; $i++){
					$resSelectId = @pg_fetch_array ($rQueryId, $i);				
					$tzResult[$i] = $resSelectId['libelle'];
				
					$sSqlSlctAction = "SELECT date_debut, date_fin, responsable FROM nc_fnc_action WHERE fnc_id = '{$iFncId}' " ;

					$rQueryAction = @pg_query($conn, $sSqlSlctAction) ;
					
					for ($j = 0 ; $j < @pg_num_rows ($rQueryAction) ; $j++){
						$resSelectAction = @pg_fetch_array ($rQueryAction, $j);

						$datdeb = $resSelectAction['date_debut'] ;
						$resp = $resSelectAction['responsable'] ;
						$datfin = $resSelectAction['date_fin'] ;

					}
				
				}
				/*
				$zResult = implode("<br />", $tzResult);
				echo "ty".$zResult;
				*/
				//$zResult = implode($datedeb."___".$resp."___".$datefin) ;
				$zResult = implode("___", $datedeb,$resp,$datefin) ;
				echo "ty".$zResult ;
				
		}
		unset ($sActLibelle);
	}
?>