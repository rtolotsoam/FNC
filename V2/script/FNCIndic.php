<?php
	require_once("DBConnect.php") ;

	$tzMois = array ("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre") ;

	$zMoisNow = date ("m") ;

	$zAnneeNow = date ("Y") ;

	#+++++++++++++++++++++++++++++++++++++++++++++++++++++
	# - TABLEAUX CONTENANT LES ACTIONS
	#+++++++++++++++++++++++++++++++++++++++++++++++++++++
	$tzActionType = array ("curative", "corrective / preventive" ) ;
	$tzActionType_ = array ("curatives", "correctives / préventives" ) ;
	//$tzActionType = array ("curative") ;
	$tzActionEtat = array ("en cours", "ok", "en attente", "incomplètes") ;
	#+++++++++++++++++++++++++++++++++++++++++++++++++++++



?>

<html>
	<head>
		<title>
		</title>
		<link type="text/css" href="../css/tablesorter.css" rel="stylesheet" />
		<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
		<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
		<style type="text/css">
		body, table, .tablesorter td, .tablesorter th
		{
			font-family: Trebuchet MS ;
			font-size: 12px ;
		}
		.tab-page
		{
			font-weight: bold ;
		}
		input[type=text], select
		{
			border: 1px #323232 solid ;
			width: 150px ;
			font-family: Trebuchet MS ;
			font-size: 12px ;
		}
		</style>
		<?php

			if (!isset ($_GET['slctAnnee']))
			{
				echo "
				<script type='text/javascript'>
					$(document).ready (function ()
					{
						$('#frmBase').submit () ;
					}) ;
				</script>
				" ;
			}
		?>

	</head>
	<body>
		<center>
			<form id="frmBase" method="GET" action="">
				<table class="tab-page">
					<tr>
						<td align="left">Mois :</td>
						<td align="left">
							<select name="slctMois">
								<?php
									for ($i = 1 ; $i < sizeof ($tzMois) ; $i ++)
									{
										$zMois = $tzMois[$i] ;

										$zSelected = "" ;

										($i < 10) ? $i = "0$i" : $i ;

										if (isset ($_GET['slctMois']) && intval($_GET['slctMois']) == intval($i))
										{
											$zSelected = "selected='selected'" ;
										}
										elseif (!isset ($_GET['slctMois']) &&intval($i) == intval($zMoisNow))
										{
											$zSelected = "selected='selected'" ;
										}

										echo "<option value='$i' $zSelected>$zMois</option>\n" ;
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">
							Ann&eacute;e :
						</td>
						<td align="left">
							<select name="slctAnnee">
								<?php
									for ($i = $zAnneeNow-2 ; $i <= $zAnneeNow ; $i ++)
									{
										$zSelected = "" ;

										if (isset ($_GET['slctAnnee']) && intval($_GET['slctAnnee']) == intval($i))
										{
											$zSelected = "selected='selected'" ;
										}
										elseif (!isset ($_GET['slctAnnee']) && intval($i) == intval($zAnneeNow))
										{
											$zSelected = "selected='selected'" ;
										}

										echo "<option value='$i' $zSelected>$i</option>\n" ;
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="Afficher" class="ui-state-default" />
						</td>
					</tr>
				</table>
			</form>

			<!-- AFFICHAGE -->
			<?php
				$oIndic = new cIndic () ;

				# - Création des dates
				$oIndic->iMois =  isset($_GET['slctMois']) ? $_GET['slctMois'] : date("m");
				$oIndic->iAnnee = isset($_GET['slctAnnee']) ? $_GET['slctAnnee'] : date("Y");

				$oIndic->dateAffect() ;
				$_GET['slctMois'] = isset($_GET['slctMois']) ? $_GET['slctMois'] : date("m");
				$_GET['slctAnnee'] = isset($_GET['slctAnnee']) ? $_GET['slctAnnee'] : date("Y");
				# - Affichage
			 	#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			 	echo "<table class='tablesorter' rules='rows' style='width:60%;'>\n" ;
			 	echo "<thead>\n" ;
			 	echo "<tr>\n" ;
			 	echo "<th align='center' colspan='3'>" .$tzMois[intval ($_GET['slctMois'])]. " " .$_GET['slctAnnee']. "</th>" ;
			 	echo "</tr>" ;
			 	echo "<tr>" ;
			 	echo "<th align='center'>Nombre de fiches NC</th>" ;
			 	echo "<th align='center'>Nombre d'actions curatives créées</th>" ;
			 	echo "<th align='center'>Nombre d'actions préventives créées</th>" ;
			 	echo "</tr>\n" ;
			 	echo "</thead>\n" ;
			 	echo "<tbody>\n" ;
			 	echo "<tr>\n" ;
		 		$oIndic->sqlNumberNC() ;
			 	echo "<td align='center'>".$oIndic->execSQL ()."</td>" ;

			 	$oIndic->zActionType = "curative" ;
			 	$oIndic->sqlNumberAction () ;
			 	echo "<td align='center'>".$oIndic->execSQL ()."</td>" ;

			 	$oIndic->zActionType = "corrective / preventive" ;
			 	$oIndic->sqlNumberAction() ;
			 	echo "<td align='center'>".$oIndic->execSQL ()."</td>" ;
		 		echo "</tr>\n" ;
		 		echo "<tr><td align='center'></td></tr>" ;
			 	echo "</tbody>\n" ;
			 	echo "</table>\n" ;

			 	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			 	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			 	echo "<table class='tablesorter' rules='rows' style='width:60%;'>\n" ;
			 	echo "<thead>\n" ;
			 	echo "<tr>\n" ;

			 	$iTaille = sizeof ($tzActionType)+1 ;

			 	echo "<th align='center' colspan='$iTaille'>R&eacute;partition des actions</th>" ;
			 	echo "</tr>" ;
			 	echo "<tr>" ;
			 	echo "<th align='center'>Etat</th>" ;
			 	for ($iType = 0 ; $iType < sizeof($tzActionType) ; $iType ++)
			 	{
			 		echo "<th align='center' width='40%'>".$tzActionType_[$iType]."</th>\n" ;
			 	}
			 	echo "</tr>\n" ;
			 	echo "</thead>\n" ;
			 	echo "<tbody>\n" ;

			 	for ($iEtat = 0 ; $iEtat < sizeof ($tzActionEtat) ; $iEtat ++)
			 	{
			 		echo "<tr>\n" ;
			 		echo "<td align='left'>" .strtoupper($tzActionEtat[$iEtat][0]).substr ($tzActionEtat[$iEtat],1). " </td>" ;
			 		for ($iType = 0 ; $iType < sizeof($tzActionType) ; $iType ++)
				 	{
				 		$oIndic->zActionType = $tzActionType[$iType] ;
				 		$oIndic->zActionEtat = $tzActionEtat[$iEtat] ;
				 		$oIndic->sqlActRepart () ;

				 		echo "<td align='center' width='40%'>".$oIndic->execSQL ()."</td>\n" ;
				 	}
			 		echo "</tr>\n" ;
			 	}
			 	echo "<tr><td align='center'></td></tr>" ;
			 	echo "</tbody>\n" ;
			 	echo "</table>\n" ;
			 	#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			?>
			</center>
		<!-- FIN AFFICHAGE -->
	</body>
</html>