<?php 
	require_once("/var/www.cache/dgconn.inc");
	
	if (!$conn){
		echo "<p><font color=\"#FF0000\" size=\"3\"> Erreur de connexion !!! </font><br />" ;
	}
	else{
		$sActLibelle = $_REQUEST['act_libelle'] ;

		if(isset($sActLibelle)){
			$sSqlSelectId = " SELECT DISTINCT 
			                     nal.id as id,nal.libelle as libelle,nfa.fnc_info_id as id_info, nfi.etat AS etat	
			                  FROM 
			                     nc_action_liste nal, nc_fnc_action nfa, nc_fnc_infos nfi 
                           WHERE
                              nal.id = nfa.action_liste_id AND 
										nfa.fnc_info_id = nfi.id AND	
										nfi.etat = 'en cours' AND 
										nal.type != 'curative' AND 
										nal.libelle = '{$sActLibelle}'" ;
         
			$rQueryId = @pg_query($conn, $sSqlSelectId) or die(@pg_last_error($conn)) ;
			$iNumId = @pg_num_rows ($rQueryId) ;
			
			for ($i = 0 ; $i < $iNumId ; $i++){
					$aResSelectId = @pg_fetch_array ($rQueryId, $i);	
					$iId = $aResSelectId['id'] ;
					$iIdInfo = $aResSelectId['id_info'] ;
					$libelle = $aResSelectId['libelle'] ;
					
					
					$sSqlSlctAction = "   SELECT 
					                         date_debut,responsable,date_fin,etat 
					                      FROM 
					                         nc_fnc_infos 
					                      WHERE id = '{$iIdInfo}' LIMIT 1 " ;
					
					$rQueryAction = @pg_query($conn, $sSqlSlctAction) ;
										
					for ($j = 0; $j < @pg_num_rows($rQueryAction); $j++){
						$resSelectAction = @pg_fetch_array ($rQueryAction, $j);

						$datdeb[$j] = $resSelectAction['date_debut'] ;
						$resp[$j] = $resSelectAction['responsable'] ;
						$datfin[$j] = $resSelectAction['date_fin'] ;
						//$etat[$j] = $resSelectAction['etat'] ;
						
						$zResult = $datdeb[$j].'___'.$resp[$j].'___'.$datfin[$j] ;
//						$zResult = $datdeb[$j].'___'.$resp[$j].'___'.$datfin[$j].'___'.$etat[$j] ;
					}
				}
		}
		echo $zResult ;
		unset ($sActLibelle);
	}
?>