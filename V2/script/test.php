<?php
	require_once("DBConnect.php");
	
	$sql1 = "select distinct " ;
	
	
	$sql2 = "
	SELECT 	nf.fnc_id AS nf_id, nf.fnc_ref AS reffnc, nfa.fnc_id AS id_fnc, 
								nfa.faille_identifiee AS faille_identifiee, nfa.impact AS impact, 
								nfa.date_debut AS datedeb, nfa.date_fin AS datefin,
								nal.libelle AS description, nfa.responsable AS responsable,
								nfa.id AS nfa_id, nfa.etat AS statut,
								nfa.date_suivi AS date_suivi, nfa.commentaire AS comment, 
								nfa.generalisation AS generalisation, nfa.action_liste_id AS act_list_id
						FROM nc_fiche nf, nc_fnc_action nfa, nc_action_liste nal
						WHERE nfa.etat != 'ok'
							AND nfa.action_liste_id = nal.id
							AND nf.fnc_id = nfa.fnc_id 
							AND nal.type != 'curative'							
						order by act_list_id	
	";
	$oQuery = @pg_query($sql);
	$iNbSelectRef = @pg_num_rows($oQuery);
	echo $iNbSelectRef;
?>

<html>
<head>

</head>

<body>

<img style="background: rgb(208,228,247); /* Old browsers */ background: -moz-linear-gradient(top, rgba(208,228,247,1) 0%, rgba(115,177,231,1) 8%, rgba(10,119,213,1) 42%, rgba(83,159,225,1) 89%, rgba(135,188,234,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(208,228,247,1)), color-stop(8%,rgba(115,177,231,1)), color-stop(42%,rgba(10,119,213,1)), color-stop(89%,rgba(83,159,225,1)), color-stop(100%,rgba(135,188,234,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(208,228,247,1) 0%,rgba(115,177,231,1) 8%,rgba(10,119,213,1) 42%,rgba(83,159,225,1) 89%,rgba(135,188,234,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(208,228,247,1) 0%,rgba(115,177,231,1) 8%,rgba(10,119,213,1) 42%,rgba(83,159,225,1) 89%,rgba(135,188,234,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(208,228,247,1) 0%,rgba(115,177,231,1) 8%,rgba(10,119,213,1) 42%,rgba(83,159,225,1) 89%,rgba(135,188,234,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(208,228,247,1) 0%,rgba(115,177,231,1) 8%,rgba(10,119,213,1) 42%,rgba(83,159,225,1) 89%,rgba(135,188,234,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d0e4f7', endColorstr='#87bcea',GradientType=0 ); /* IE6-9 */"/>
</body>
</html>