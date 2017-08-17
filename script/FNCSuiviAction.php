<?php
	require_once("DBConnect.php");
	$zSelectRef = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC";
	$oQuerySelectRef = @pg_query($zSelectRef);
	$iNbSelectRef = @pg_num_rows($oQuerySelectRef);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Suivi des actions</title>
		
		<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
		
		<!--link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
		<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
		<script type="text/javascript">$(document).ready(function(){$("#table1").tablesorter();});</script-->
		
	</head>

	<body>
		<p style="font-family: verdana; font-size: 12px; color: #666666">
			Cette page permet de visualiser les actions dont l'&eacute;tat n'est pas "OK".
			<ul style="font-family: verdana; font-size: 12px">
				<li>	
					<font color="#FF0066">En rouge :</font> les actions dont la date de fin est inférieure à la date du jour.
				</li>
				<li>
					<font color="#FF9933">En orange :</font> les actions dont la date de fin est &eacute;gale à la date du jour.
				</li>
				<li>
					<font color="#00CC99">En vert :</font> les actions dont la date de fin est supérieure à la date du jour.
				</li>
				<li>
					<font color="#000000">En noir :</font> les actions dont aucune date de fin n'est d&eacute;finie.
				</li>
			</ul>
		</p>
		
		<p>
			<fieldset>
				<table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-family: verdana; font-size: 12px">
					<!--thead-->
						<tr style="font-weight: bold">
							<td width="18%">R&eacute;f&eacute;rence de la fiche</td>
							<td width="18%">Date de d&eacute;but d'action</td>
							<td width="28%">Description de l'action</td>
							<td width="18%">Responsable de l'action</td>
							<td width="18%">Date de fin de l'action</td>
						</tr>
					<!--/thead>
					<tbody-->			
<?php
			$zSql = "	SELECT 	nc_fiche.fnc_ref AS reffnc, nc_action.\"action_debDate\" AS datedeb, 
								nc_action.\"action_finDate\" AS datefin, nc_action.action_description AS description, 
								nc_action.action_responsable AS responsable 
						FROM nc_fiche, nc_action 
						WHERE nc_action.action_etat != 'ok' 
							AND nc_fiche.fnc_id = nc_action.\"action_fncId\" 
						ORDER BY nc_fiche.fnc_ref ASC;";
						
			$oQuerySql = @pg_query($zSql);
			$iNbSql = @pg_num_rows($oQuerySql);	
			
			for($i = 0; $i < $iNbSql; $i ++){
				echo "<tr>";
				$toRes = @pg_fetch_array($oQuerySql, $i);
				
				if(empty($toRes['datefin'])) $color = "#000000";
				else{
					if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
						elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
							else $color = "#00CC99";	
				}
				
				echo "	<tr style=\"color: $color\">
							<td width=\"18%\">".$toRes['reffnc']."</td>
							<td width=\"18%\">".$toRes['datedeb']."</td>
							<td width=\"28%\">".$toRes['description']."</td>
							<td width=\"18%\">".$toRes['responsable']."</td>
							<td width=\"18%\">".$toRes['datefin']."</td>
						</tr>";
			}
?>		
					<!--/tbody-->
				</table>
			</fieldset>
		</p>
	</body>
</html>
