<?php

	/**
	 * Cr�� le 04/07/2012 par Fulgence
	 * Modifi� le 04/07/2012 par Fulgence
	 * 
	 */

	// ------- Acc�s � la base ------------
	require_once("/var/www.cache/dgconn.inc") ;
	
	// Ajout par Fulgence -------------
   $dateDeb = date('Y-m-d') ;
	$dateFin = date('Y-m-d') ;
	
	if(isset($_REQUEST['txtDateDeb'])){
      $dateDeb  = $_REQUEST['txtDateDeb'] ;   
   }
   
   if(isset($_REQUEST['txtDateFin'])){
      $dateFin  = $_REQUEST['txtDateFin'] ;   
   }
   
	// -------- fin ajout ------------
	

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Admin Actions Correctives</title>
	<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="../js/jquery-block-ui.js"></script>
	<script type="text/javascript" src="../js/ui.core.js"></script>
	<script type="text/javascript" src="../js/ui.draggable.js"></script>
	<script type="text/javascript" src="../js/ui.dialog.js"></script>
	<script type="text/javascript" src="../js/ui.datepicker.js"></script>
	<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="../js/FNCAjout.js"></script>
	<script type="text/javascript">
	$(document).ready (function () {
	   
	   $("#isset_affiche").val("0");
		$("#tabAdmin").tablesorter ({
			
			headers: { 6: { sorter: false} }
		}) ;
		
		$("#txtDateDeb").datepicker ({inline: true, changeMonth: true,changeYear: true}) ;
		$("#txtDateFin").datepicker ({inline: true, changeMonth: true,changeYear: true}) ;
	
	});
	
	// Function pour l'assignation d'action corrective
	function assignAction()
	{
		
		var countCheck = document.getElementById('countCheck').value ;

		var i = 0;
		var COCHE = false;
			
		for (i = 0; i < countCheck ; i++)
		{
			if(document.getElementsByName('chek[]').item(i).checked)
			{
				COCHE = true;
				break;
			}
		}

		if(COCHE)
		{
			document.getElementById('frmAffectation').action = "FNCAssignActionCorrective.php" ;
			document.getElementById('frmAffectation').submit () ;
		}
		else
		{
			alert("Veuillez s\351lectionner au moins une fiche");
		}
	}
	
	// Fonction affichage action
	function searchAction ()
	{
	   $("#isset_affiche").val("1");
	   document.getElementById('frmAffectation').action = "FNCAdminActionCorrective.php" ;
      document.getElementById('frmAffectation').submit () ;
	}

	</script>

</head>

<body>

<?php
	require_once ("DBConnect.php");
	//echo "<p style = \"color: #999999; padding-left: 10%; \">".$_SESSION["MSGSearch"]."</p>";
?>

<center>
<form id="frmAffectation" name="frmAffectation" method="post" action="FNCAssignActionCorrective.php">
	
	<p>
		<?php 
			if (isset($_REQUEST['fnc_ref_res']) AND isset($_REQUEST['action_res'])) 
				echo "L'action <font style='color: green'>{$_REQUEST['action_res']}</font> est ajout&eacute;e aux fiches de r&eacute;f&eacute;rences <font style='color: green'>{$_REQUEST['fnc_ref_res']}</font>"; 
			else echo "&nbsp;"; 
		?>
	</p>

	<fieldset>
		<legend>Liste des FNC</legend>				
         <table>
            <tr>
               <td>Fiche ouverte du :</td>
               <td>
                  <input type="text" id="txtDateDeb" name="txtDateDeb" value="<?php echo $dateDeb ; ?>" class="txtInput" readonly/>
               </td>
               <td>Au :</td>
               <td>
                  <input type="text" id="txtDateFin" name="txtDateFin" value="<?php echo $dateFin ; ?>" class="txtInput" readonly />
                  <input type = 'hidden' id = 'isset_affiche' name = 'isset_affiche' value = '0' />
               </td>
            </tr>
            <tr>
               <td colspan="2">&nbsp;</td>
               <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
               <td></td>
               <td colspan="3" align="center">
                  <input type="button" id="btnSearch" name="btnSearch" value="Rechercher" class = "ui-state-default" onclick="searchAction ()" />
               </td>
            </tr>
         </table>
         
         <?php
            if (isset($_REQUEST['isset_affiche']) && $_REQUEST['isset_affiche'] == 1) {
          ?>
		<table style="width:80%;">
			<tr>
				<td align="right">
					<input type="button" id="btnAffect" name="btnAffect" value="Assigner" class = "ui-state-default" onclick="assignAction()" />
				</td>
			</tr>
		</table>
		<?php
         
			/*
			* Requete pour l'affichage de la liste des fiches
			*/
			$sSqlSelectFiche = " SELECT 
			                        fnc_id, 
			                        fnc_type, 
			                        fnc_ref, 
			                        \"fnc_creationDate\", 
			                        fnc_client, 
			                        fnc_motif, 
			                        fnc_exigence,
			                        fnc_autre_cplmnt " ;
                                 
         $sSqlSelectFiche .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id " ;
                                 
			$sSqlSelectFiche .= " FROM nc_fiche
			                     WHERE \"fnc_creationDate\" BETWEEN '$dateDeb' AND '$dateFin'
			                      " ;
			
			$rQueryFiche = @pg_query ($conn, $sSqlSelectFiche) ;

			$iNumRowFiche = @pg_num_rows($rQueryFiche) ;
			
		?>
		<table style="width:80%;" cellspacing="0" cellpadding="2" class="tablesorter" id="tabAdmin">
			<thead>
				<tr class="titre" bgcolor="#efefef">
					<br />
					<th width="9%">Type</th>
					<th width="13%">R&eacute;f&eacute;rence</th>
					<th width="6%">Date cr&eacute;ation</th>
					<th width="13%">Nom Client</th>
					<th width="12%">Motif Cr&eacute;ation</th>
					<th width="12%">Exigences client</th>
               <th width="12%">Criticit&eacute;</th>
					<th width="7%" align="center">S&eacute;lectionner</th>
				</tr>
			</thead>
			<tbody>									
			<?php
				for ($j=0; $j<$iNumRowFiche; $j++)
				{
					$toDataFiche = @pg_fetch_array($rQueryFiche, $j) ;
               
               /****************** Modif Fulgence 20150210  ******************/
               // Affichage criticité
               if($toDataFiche['fnc_grav_cat_id'] != '' && $toDataFiche['fnc_freq_cat_id'] != '')
               {
                  //if($toDataFiche['fnc_grav_cat_id'] != '')
                     $cat_id_grav = $toDataFiche['fnc_grav_cat_id'] ;
                  /*else
                     $cat_id_grav = 1 ;
                  
                  if($toDataFiche['fnc_freq_cat_id'] != '')*/
                     $cat_id_freq = $toDataFiche['fnc_freq_cat_id'] ;
                  /*else
                     $cat_id_freq = 1 ;**/
                  
                  // gravité
                  $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$cat_id_grav " ;
                  //echo $sqlSltGrv ;
                  $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
                  $arGrv = pg_fetch_array($resGrv) ;
                  $grv_ech = $arGrv['echelle_id_grav'] ;
                  
                  //Fréquence
                  $sqlSltFrq = "SELECT id_categorie_freq, echelle_id_freq FROM nc_frequence_categorie WHERE id_categorie_freq = $cat_id_freq " ;
                  $resFrq = pg_query($conn, $sqlSltFrq) or die (pg_last_error($conn)) ;
                  $arrFrq = pg_fetch_array($resFrq) ;
                  $frq_ech = $arrFrq['echelle_id_freq'] ;
                  
                  if ($grv_ech == 1)
                     $criticite = "m" ;
                  elseif ($grv_ech == 2 && $frq_ech <= 2)
                     $criticite = "m" ;
                  elseif ($grv_ech == 2 && $frq_ech >= 3)
                     $criticite = "M" ;
                  elseif ($grv_ech == 3 && $frq_ech < 4)   
                     $criticite = "M" ;
                  elseif ($grv_ech == 3 && $frq_ech == 4)   
                     $criticite = "C" ;
                  elseif($grv_ech >= 4)
                     $criticite = "C" ;
                  /*else
                     $criticite = "m" ;*/
                  
                  // test de couleur
                  if ($criticite == "m")
                  {
                     $colr = "style='background-color:#FCF03F;font-weight:bold;'" ;
                     $criticite = "mineure" ;
                  }
                  elseif ($criticite == "M")
                  {
                     $colr = "style='background-color:#F28810;font-weight:bold;'" ;
                     $criticite = "Majeure" ;
                  }
                  elseif ($criticite == "C")
                  {
                     $colr = "style='background-color:#E71D07;color:#FFFFFF;font-weight:bold;'" ;
                     $criticite = "Critique" ;
                  }
               }
               else
               {
                  //$color = "style='background-color:#FFFFFF;font-weight:bold;'" ;
                  $color = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'") ;
                  $criticite = "" ;
               }
               /****************** Fin modif ******************/
               
					$couleur = (($j%2 == 0) ? "odd" : "0") ;
			?>
				<tr class= "<?php echo $couleur ; ?>">
					<td><?php echo $toDataFiche['fnc_type'] ; ?></td>
					<td><?php echo $toDataFiche['fnc_ref'] ; ?></td>
					<td><?php echo $toDataFiche['fnc_creationDate'] ; ?></td>
					<td><?php
                     if (trim($toDataFiche['fnc_client']) == 'Autres')
                        echo $toDataFiche['fnc_autre_cplmnt']; 
                     else
                        echo $toDataFiche['fnc_client'];
                   ?>
               </td>
					<td><?php echo $toDataFiche['fnc_motif'] ; ?></td>
					<td><?php echo $toDataFiche['fnc_exigence'] ; ?></td>
               <td <?php echo $colr ; ?>><?php echo $criticite ; ?></td>
					<td align="center">
						<input type="checkbox" name="chek[]" value="<?php echo $toDataFiche['fnc_id'] ; ?>" />
						<input type='hidden' id='countCheck' name='countCheck'  value="<?php echo $iNumRowFiche ; ?>" />
					</td>
				</tr>				
			<?php
				}
			?>
			</tbody>
		</table>
		<?php
			}
		?>
	</fieldset>	
</form>
</center>


<!-- AJOUT POUR MOIFICATION ET CONSULTATION -->
<div class="divGrand" id="div-iframe" style="display:none;width:100%;height:100%;background:#FFFFFF;">
	<iframe name="iframeAffiche" frameborder="no" style="border: none;width:100%;height:100%;"></iframe>
</div>
<!-- FIN AJOUT -->


</body>
</html>