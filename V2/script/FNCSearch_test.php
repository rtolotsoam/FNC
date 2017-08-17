<?php
	require_once("DBConnect.php") ;
   
	$zSelectRef = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC" ;
	$oQuerySelectRef = @pg_query($conn, $zSelectRef) or die(pg_last_error($conn)) ;
	$iNbSelectRef = @pg_num_rows($oQuerySelectRef) ;

	$zSql = "select *from (select *from (SELECT nc_fiche.fnc_id AS id, nc_fiche.fnc_client AS client, 
nc_fiche.fnc_type AS type, nc_fiche.fnc_ref AS reffnc, 
nc_action.\"action_debDate\" AS datedeb,
nc_action.\"action_finDate\" AS datefin, nc_action.action_description AS description, 
nc_action.action_responsable AS responsable ,
fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id FROM nc_fiche,
 nc_action WHERE nc_action.action_etat != 'ok' 
AND nc_fiche.fnc_id = nc_action.\"action_fncId\" AND 
nc_action.action_type = 'curative' ORDER BY nc_action.\"action_finDate\" ASC, nc_fiche.fnc_ref ASC ) as temp
left join 
(SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON 
            substring(f.fnc_code FROM 1 for 3) = a.code 
            INNER JOIN  business_unit 
            b ON b.id_bu = a.id_bu ) as temp2 on temp2.fnc_id =temp.id ) as temp3
            order by datedeb asc" ;
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Suivi des actions Curatives</title>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
      <script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css" />	
      
      <script type="text/javascript" src="../js/thickbox.js"></script>
      <script type="text/javascript" src="../js/FNCSearch.js"></script>
      <script type="text/javascript" src="../js/FNCAjout.js"></script>
      
      	<link rel="stylesheet" type="text/css" href="../css/FNCAjout.css" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css" />
      
		<style>
         .titre{border-bottom: 1px solid black;border-right: 1px solid black;font-weight: bold;}
         .contenu{border-right: 1px solid black;}
         table,body{font-family: verdana;font-size: 11px;}
         .date_field {position: relative; z-index:100;}
         .wrapper {position: relative;height: 450px;overflow-y: auto;border:4px solid #1e5799;width:100%;direction:ltr;}
         div.ui-datepicker{font-size:12px;z-index:100000000;}
         #ui-datepicker-div ,.ui-datepicker { z-index: 99999 !important; }
		</style>

</head>

<body>
<form id="frmSearch" name="frmSearch" method="get" action="">
<center><span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /></br><b>Chargement...</b></span></center>
<span id='idWrapperCache' style='display:none;'>
<fieldset >

   <legend style="font-family: verdana;font-size:14px; "><b>Recherche de FNC</b></legend>

   <table>
      <tr>
         <td colspan="4">Veullez remplir l'un des champs suivants </td>
        
      </tr>
      <tr>
         <td>&nbsp;&nbsp;Nom du client : </td>
         <td>
         <span id="spnClient">
			    		<select id="slctClientName" name="slctClientName" class="<?php echo $sClass; ?>">
			    			<option value="">*** fa&icirc;tes votre choix ***</option>
			    		</select>
			    	</span>
         </td>
         <td>&nbsp;&nbsp;Code de la commande : </td>
         <td>
            <span id="spnCommand">
				    	<select id="slctCode" name="slctCode" class="<?php echo $sClass; ?>">
				    		<option value="">*** fa&icirc;tes votre choix ***</option>
				    	</select>
				    </span>
         </td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Fiche ouverte du</td>
         <td><input type="text" id="txtCrationDate" name="txtCrationDate" class="txtInput" value="<?php if(isset($_REQUEST["txtCrationDate"])) $zDateCeation1 = $_REQUEST["txtCrationDate"] ; else $zDateCeation1 = date("Y-m")."-01"; echo $zDateCeation1; ?>" readonly/></td>
         <td>&nbsp;&nbsp;au :</td>
         <td><input type="text" id="txtModifDate" name="txtModifDate" class="txtInput" value="<?php if(isset($_REQUEST["txtModifDate"])) $zDateCeation2 = $_REQUEST["txtModifDate"] ; else $zDateCeation2 = date("Y-m-d"); echo $zDateCeation2 ; ?>" readonly/></td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Cr&eacute;ateur : </td>
         <td>
         <?php
               $sqlListeCreateur = "SELECT DISTINCT prenompersonnel, matricule FROM personnel INNER JOIN nc_fiche ON personnel.matricule = nc_fiche.fnc_createur order by matricule " ;
					$zListeCreateur = @pg_query($conn, $sqlListeCreateur) ;
					$iNumListeCreateur = @pg_num_rows ($zListeCreateur) ;

?>					<select id = "txtCreateurMatr" name = "txtCreateurMatr" class = "slct">
		                <option value = "">
		                	*** fa&icirc;tes votre choix ***
		                </option>
<?php
		                for ($i = 0; $i < $iNumListeCreateur; $i++) {
							$toData = @pg_fetch_array ($zListeCreateur, $i) ;
							$iMatricule = $toData['matricule'] ;
							$zPrenom = $toData['prenompersonnel'] ;
							$zSelected = "" ; // intialisation de la variable de selection
							if(isset($_REQUEST["txtCreateurMatr"])) {
								if ($iMatricule == $_REQUEST["txtCreateurMatr"]) $zSelected = "selected" ;
							}
							echo "<option value='{$iMatricule}' {$zSelected}>{$iMatricule} - {$zPrenom}</option>" ;
						}
?>
					</select>
         </td>
         <td>&nbsp;&nbsp;CP :  </td>
         <td><input name="txtCP" type="text" id="txtCP" class="txtInput" onkeypress="return filtrerTouche(event);" value="<?php if(isset($_REQUEST['txtCP'])) echo $_REQUEST['txtCP']; ?>"></td>
      </tr>
       <tr>
         <td>&nbsp;&nbsp;Statut du traitement des actions curatives: </td>
         <td>
         <select id="slctTraitStatut" name="slctTraitStatut" class="slct">
<!-- -------------------------------------------------------------------------------------------------------------- -->
						<option value = "" <?php if(isset($_REQUEST["slctTraitStatut"]) == "") echo " selected" ; ?>>
		                	*** fa&icirc;tes votre choix ***
		                </option>
						<option value="aucune_action_corrective"<?php if($_REQUEST["slctTraitStatut"] == "aucune_action_corrective") echo " selected" ; ?>>
							Actions non d&eacute;finies
						</option>
						<option value="avec_action"<?php if($_REQUEST["slctTraitStatut"] == "avec_action") echo " selected" ; ?>>
							Actions en cours
						</option>
						<option value="actions_correctives_terminees"<?php if($_REQUEST["slctTraitStatut"] == "actions_correctives_terminees") echo " selected" ; ?>>
							En attente validation
						</option>
						<option value="fiche_cloturee"<?php if($_REQUEST["slctTraitStatut"] == "fiche_cloturee") echo " selected" ; ?>>
							Cl&ocirc;tur&eacute;es
						</option>
<!-- -------------------------------------------------------------------------------------------------------------- -->
         </td>
         <td>&nbsp;&nbsp;Type :</td>
         <td>
            <select name="slctType" id="slctType" class="slct">
				  		<option value = "" <?php if ($_REQUEST['slctType'] == "") echo " selected" ; ?>>
				  			*** fa&icirc;tes votre choix ***
				  		</option>
				  		<option value = "audit" <?php if($_REQUEST['slctType'] != "audit") echo ""; else echo " selected" ; ?>>
					  		Audit
					  	</option>
					  	<option value = "client" <?php if($_REQUEST['slctType'] != "client") echo ""; else echo " selected" ; ?>>
					  		Client
					  	</option>
					  	<option value = "interne" <?php if($_REQUEST['slctType'] != "interne") echo ""; else echo " selected" ; ?>>
					  		Interne
					  	</option>
			      	</select>&nbsp;&nbsp;
         </td>
      </tr>
        <tr>
         <td>&nbsp;&nbsp;Statut du traitement des actions correctives: </td>
         <td>
            			  		<select id="slctTraitStatutCor" name="slctTraitStatutCor" class="slct">
<!-- -------------------------------------------------------------------------------------------------- -->
						<option value = "" <?php if(isset($_REQUEST["slctTraitStatutCor"]) == "") echo " selected" ; ?>>
		                	*** fa&icirc;tes votre choix ***
		                </option>
						<option value="aucune_action_corrective"<?php if($_REQUEST["slctTraitStatutCor"] == "aucune_action_corrective") echo " selected" ; ?>>
							Actions non d&eacute;finies
						</option>
						<option value="avec_action"<?php if($_REQUEST["slctTraitStatutCor"] == "avec_action") echo " selected" ; ?>>
							Actions en cours
						</option>
						<option value="actions_correctives_terminees"<?php if($_REQUEST["slctTraitStatutCor"] == "actions_correctives_terminees") echo " selected" ; ?>>
							En attente validation
						</option>
						<option value="fiche_cloturee"<?php if($_REQUEST["slctTraitStatutCor"] == "fiche_cloturee") echo " selected" ; ?>>
							Cl&ocirc;tur&eacute;es
						</option>
<!-- --------------------------------------------------------------------------------------------------- -->

					</select>
         </td>
         <td></td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4" align="center">
         </br>
         <input type="button" name="btnReset" id="btnReset" value="R&eacute;initialiser" class = "ui-state-default" onclick="reinitialiser()">
         &nbsp;&nbsp;&nbsp;
         <input type="button" name="btnSubmit" id="btnSubmit" value="Rechercher" class = "ui-state-default">
          </br>
         </td>
      </tr>
      
   </table>
   
</br>
</fieldset>
</br>

</span></br>
	<div class='wrapper' style='display:none;' id='idCache'>
		<table width='100%' cellspacing='1' cellpadding='1' id='table2'  >
			<thead>
				<tr >
				<th width='5%'>Code</th>
				<th width='9%'>Client</th>
				<th width='3%'>BU</th>
				<th width='12%'>R&eacute;f&eacute;rence</th>
				<th width='7%'>Ouverte le</th>
				<th width='8%'>Cr&eacute;&eacute;e par</th>
				<th width='6%'>Valid&eacute;e</th>
				<th width='9%'>Motif cr&eacute;ation FNC</th>
				<th width='5%'>Type</th>
				<th width='6%'>Version</th>
				<th width='7%'>Criticit&eacute;</th>
				<th width='6%'>&nbsp;</th>
				<th width='6%'>&nbsp;</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
      
	$(function () {
		$("#idCache").show();
		$("#idWrapperCache").show();
		// $("#idContentCache").show();
		$("#imLoad").hide();
		$('#table2').tablesorter({

			theme: 'blue',
			widthFixed : true,

			widgets: ['zebra', 'stickyHeaders'],
			headerTemplate : '{content} {icon}', // Add icon for various themes
			widgets: [ 'zebra', 'stickyHeaders', 'filter' ],
			widgetOptions: {
				// jQuery selector or object to attach sticky header to
				stickyHeaders_attachTo : '.wrapper' // or $('.wrapper')

			},
			headers: { 
				11: { sorter: false}, 12: { sorter: false}
			} 
		});
	});
   fillClient('search', '<?php if (isset($_REQUEST['slctClientName'])) echo $_REQUEST['slctClientName']; ?>');
   fillCommand('search', '<?php if (isset($_REQUEST['slctCode'])) echo $_REQUEST['slctCode']; ?>');

   $(function() {
   $("#txtCrationDate").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
   $("#txtModifDate").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});
   });

	function reinitialiser(){
		$("#slctCode").val("");
		$("#txtRef").val("");
		$("#txtComm").val("");
		$("#slctClientName").val("");

		$("#txtCrationDate").val("<?php echo date("Y-m-")."01" ?>");
		$("#txtModifDate").val("<?php echo date('Y-m-d') ?>");

		$("#txtCreateurMatr").val("");
		$("#txtCP").val("");
		$("#slctTraitStatut").val("");
		$("#slctType").val("");
	}
	function viewFNC(idFNC)
	{
		var widthScreen = screen.width;
		widthScreen -= 50;
		// alert('ppp'+idFNC);return 0;
		tb_show('','FNCConsulter_.php?height=600&width='+widthScreen+'&idFNC='+idFNC);
		widthScreen = screen.width;
	}
	function editFNC(idFNC,test)
	{
		// alert(idFNC);
		var widthScreen = screen.width;
		widthScreen -= 50;
		tb_show('','FNCConsulter_.php?height=600&width='+widthScreen+'&idFNC='+idFNC+'&varTest='+test);
		// tb_show('FNCModifier_.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc);
		widthScreen = screen.width;
	}
   $(document).ready(function(){
   $("#btnSubmit").on('click',function(){
  // document.location.replace('FNCModifier.php?txtId='+varId2+'&txtRef='+varZRef+'&txtNameClient='+slctClientName+'&txtCrationDate='+txtCrationDate+'&txtModifDate='+txtModifDate+'&txtCreateurMatr='+txtCreateurMatr+'&motif_fnc='+motif_fnc);
  /*
    $slctClientName      = $_REQUEST['slctClientName'] ;
      $slctCode            = $_REQUEST['slctCode'] ;
      $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
      $txtModifDate        = $_REQUEST['txtModifDate'] ;
      $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;   
      $s_txtCP             = $_REQUEST['txtCP'] ;
      $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
      $slctType            = $_REQUEST['slctType'] ;
      $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;
      
      $fnc_motif = $_REQUEST['motif_fnc'] ;
  */
      var txtCrationDate = $("#txtCrationDate").val();
      var txtModifDate = $("#txtModifDate").val();
      //var slctClientName = $("#slctClientName:selected").val();
      var slctClientName = $("#slctClientName").val();
      
      // alert('xx'+slctClientName);
      if(slctClientName == undefined || slctClientName == '' || slctClientName == 'undefined')
         slctClientName = "";
      else
         var slctClientName = $('#slctClientName').find('option:selected').text();
      var slctCode = $("#slctCode").val();
      var txtCreateurMatr = $("#txtCreateurMatr").val();
      var txtCP = $("#txtCP").val();
      var slctTraitStatut = $("#slctTraitStatut").val();
      var slctType = $("#slctType").val();
      var slctTraitStatutCor = $("#slctTraitStatutCor").val();
      // alert(slctClientName);
      /*document.location.replace('FNCSearch.php?txtCrationDate='+ txtCrationDate +'&txtModifDate='+txtModifDate + '&slctClientName='+slctClientName +'&slctCode='+slctCode+'&txtCreateurMatr='+txtCreateurMatr+'&txtCP='+txtCP+'&slctTraitStatut='+slctTraitStatut+'&slctType='+slctType+'&slctTraitStatutCor='+slctTraitStatutCor+'&onsearch=1');
	  */
	   $.post('ajax_FNCSearch.php?txtCrationDate='+ txtCrationDate +'&txtModifDate='+txtModifDate + '&slctClientName='+slctClientName +'&slctCode='+slctCode+'&txtCreateurMatr='+txtCreateurMatr+'&txtCP='+txtCP+'&slctTraitStatut='+slctTraitStatut+'&slctType='+slctType+'&slctTraitStatutCor='+slctTraitStatutCor+'&onsearch=1', 
		{ } , function(msg){
			$("#table2").html (msg) ;
		}); 

   });
});
    </script>
	</body>
</html>
