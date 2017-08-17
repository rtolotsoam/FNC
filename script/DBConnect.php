<?php
	session_start () ;

	include ("/var/www.cache/dgconn.inc") ;

	function findGroup($conn){
		$iMatricule = $_SESSION['matricule'] ;
		$sql = "SELECT distinct idgroupe, id_groupe_bd FROM groupe_bd
			INNER JOIN intranet_personnel_gpe ON intranet_personnel_gpe.gpe_id = groupe_bd.id_groupe_bd
			INNER JOIN personnel ON intranet_personnel_gpe.personnel_id = personnel.matricule
			WHERE actif = 1 AND personnel.matricule = ".$iMatricule." LIMIT 1" ;
		$res_query = @pg_query($conn, $sql) ;

		if(@pg_num_rows($res_query) > 0){
			$arr_tab = @pg_fetch_array($res_query);
			$idgroupe = $arr_tab['idgroupe'];
			$iGroupeId = $arr_tab['id_groupe_bd'];
		}
		else $iGroupeId = 27 ;

		if ($iMatricule == '5000')  $iGroupeId = 78; //cas particulier de Nathalie Perotte
		
		return $iGroupeId ;
	}

	// Function pour les actions curatives
	function searchAction($action_type, $id){
		global $conn;
		$action_type='curative' ;
		
		$zSqlAction = "SELECT nc_action.action_etat, nc_action.\"action_fncId\" AS id
	  						FROM nc_action 
	  						LEFT JOIN nc_fiche ON nc_action.\"action_fncId\" = nc_fiche.fnc_id
							WHERE nc_action.action_type = '$action_type'" ;
		
		if(!empty($id)) $zSqlAction .= " AND nc_fiche.fnc_id = '$id'";
      
		$oReqAction = @pg_query ($conn, $zSqlAction) ;
		$iNbAction = @pg_num_rows ($oReqAction) ;
      
		$res = "ok";
		for ($i = 0; $i < $iNbAction; $i ++){
			$toAction = @pg_fetch_array($oReqAction,$i) ;
			if ($toAction['action_etat'] != "ok") $res = "pasOk" ;
		}

		return $res."_____".$iNbAction;
	}

	// Function pour les actions non curatives
	function searchAction_b($action_type, $id){
		global $conn;
		$action_type='corrective / preventive' ;
		/*$zSqlAction2 = "  SELECT 
                           nc_fnc_infos.etat, nc_fnc_action.fnc_id AS id
                        FROM 
                           nc_action_liste, nc_fiche, nc_fnc_action, nc_fnc_infos
							   WHERE 
							     nc_fnc_action.fnc_id = nc_fiche.fnc_id
								AND nc_action_liste.type != '$action_type'" ;
		*/
		$zSqlAction2 = "  SELECT 
                           nfi.etat, nfa.fnc_id AS id
                        FROM 
                           nc_action_liste nal 
                           LEFT JOIN nc_fnc_action nfa ON nal.id = nfa.action_liste_id 
                           LEFT JOIN nc_fiche nf ON nf.fnc_id = nfa.fnc_id
                           LEFT JOIN nc_fnc_infos nfi ON nfi.id =nfa.fnc_info_id
							   WHERE 
							      nal.type = '$action_type'" ;
		
		if(!empty($id)) $zSqlAction2 .= " AND nf.fnc_id = '$id'";
      
		$oReqAction2 = @pg_query ($conn, $zSqlAction2) ;
		$iNbAction2 = @pg_num_rows ($oReqAction2) ;
		
		$res2 = "ok";
		for ($i = 0; $i < $iNbAction2; $i ++){
			$toAction2 = @pg_fetch_array($oReqAction2,$i) ;
			if ($toAction2['etat'] != "ok") $res2 = "pasOk" ;
		}
		return $res2."_____".$iNbAction2;
	}

	function setACloturer($action_type, $id){
		global $conn;

		$nbTotal = @pg_num_rows(@pg_query($conn, "SELECT action_id FROM nc_action WHERE \"action_fncId\" = '".$id."' AND action_type = '$action_type' "));
		$nbOK = @pg_num_rows(@pg_query($conn, "SELECT action_id FROM nc_action WHERE \"action_fncId\" = '".$id."' AND action_type = '$action_type' AND action_etat = 'ok' "));

		if($nbTotal == 0) $res = "vide";
		else{
			if($nbTotal == $nbOK) $res = "ok";
			else $res = "pasOk";
		}
		
		return $res;
	}
   
	// a cloturer 2
	function setACloturer_i($action_type, $id){
		global $conn;
      
		/*$sqlNbTotal =   " SELECT 
                           fnc_info_id 
                        FROM 
                           nc_fnc_action,nc_fnc_infos,nc_action_liste,nc_fiche 
                        WHERE nc_fnc_action.fnc_id = '".$id."' AND nc_action_liste.type = '$action_type' ; " ;*/
		//Benja
      $sqlNbTotal ="
         select fnc_info_id from nc_fiche ncf 
         inner join nc_fnc_action ncact
         on ncact.fnc_id::integer = ncf.fnc_id::integer
         inner join nc_action_liste ncactlist 
         on ncact.action_liste_id = ncactlist.id
         where ncf.fnc_id = '".$id."'and ncactlist.type = '".$action_type."'";
      
		$rQueryTotal = @pg_query($conn, $sqlNbTotal) ;
		
		$nbTotal = @pg_num_rows($rQueryTotal);
		
      /*$sqlNbOK =  "  SELECT 
                        fnc_info_id 
                     FROM 
                        nc_fnc_action,nc_fnc_infos,nc_action_liste,nc_fiche 
                     WHERE nc_fnc_action.fnc_id = '".$id."' AND nc_action_liste.type = '$action_type' 
                     AND nc_fnc_infos.etat = 'ok' ; " ;*/
		//Benja
         $sqlNbOK = "select fnc_info_id from nc_fiche ncf 
         inner join nc_fnc_action ncact
         on ncact.fnc_id::integer = ncf.fnc_id::integer
         inner join nc_action_liste ncactlist 
         on ncact.action_liste_id = ncactlist.id
         inner join nc_fnc_infos ncinfos
         on ncinfos.id = ncact.fnc_info_id
         where ncf.fnc_id = '".$id."' and ncactlist.type = '".$action_type."'
         and ncinfos.etat = 'ok'";
		$rNbOK = @pg_query($conn, $sqlNbOK) ;
		
		$nbOK = @pg_num_rows($rNbOK);

		if($nbTotal == 0) $res = "vide";
		else{
			if($nbTotal == $nbOK) $res = "ok";
			else $res = "pasOk";
		}
		
		return $res;
	}
	
	
	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	# - AJOUTEE PAR: Lovasoa RAKOTONIERANA
	# - DATE: 03 juin 2010
	#
	# - CLASSE POUR AFFICHAGE DU CONTENU DE L'ONGLET INDICATEURS
	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	class cIndic
	{
		var $iMois  ; // - mois
		var $iAnnee ; // - Ann�e

		var $zActionType ; // - Type d'actions
		var $zActionEtat ; // - Etat action

		var $zDateTest ;// - La date � tester
		var $zDateLimit ;// - La date fin du mois recherch� (Utile pour la r�partition des action)

		var $zSqlAction ; // - Les actions � faire

		function dateAffect ()
		{
			# - Cr�ation de la date � tester
			$this->zDateTest = $this->iAnnee . "-" . $this->iMois . "%" ;

			# - Cr�ation de la date limite
			$this->zDateLimit = date ("Y-m-d", mktime (0, 0, 0, $this->iMois + 1, 0, $this->iAnnee)) ; // - Le jour avant le 1er du mois suivant
		}

		function sqlNumberNC () // - Calcul du nombre de fiche du mois
		{
			$this->zSqlAction = "SELECT count(fnc_id) AS result FROM nc_fiche WHERE \"fnc_creationDate\" LIKE '$this->zDateTest' ;" ;
		}

		function sqlNumberAction () // - Nombre d'actions
		{
			$this->zSqlAction = "SELECT count (action_id) AS result FROM nc_action WHERE \"action_debDate\" LIKE '$this->zDateTest' AND action_type='$this->zActionType' ;" ;
		}

		function sqlActRepart () // - r�partition des actions
		{
			switch ($this->zActionEtat)
			{
				case "en cours":
				case "ok":
					$this->zSqlAction = "SELECT count (action_id) AS result FROM nc_action WHERE \"action_debDate\" LIKE '$this->zDateTest' AND action_type='$this->zActionType' AND action_etat = '$this->zActionEtat' AND \"action_finDate\" is not null ; " ;
				break ;

				case "en attente":
					$this->zSqlAction = "SELECT count (action_id) AS result FROM nc_action WHERE \"action_debDate\" LIKE '$this->zDateTest' AND action_type='$this->zActionType' AND action_etat <> 'ok' AND \"action_finDate\" <= '$this->zDateLimit' AND \"action_finDate\" is not null ; " ;
				break ;

				case "incompl�tes":
					$this->zSqlAction = "SELECT count (action_id) AS result FROM nc_action WHERE \"action_debDate\" LIKE '$this->zDateTest' AND action_type='$this->zActionType' AND action_etat <> 'ok' AND \"action_finDate\" is not null ; " ;
				break ;
			}


		}

		function execSQL ()
		{
			global $conn ;

			$res = @pg_query ($conn, $this->zSqlAction) or die (pg_last_error ($conn)) ;

			$toResult = @pg_fetch_array ($res, 0) ;

			return $toResult['result'] ;
		}
	}
	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>