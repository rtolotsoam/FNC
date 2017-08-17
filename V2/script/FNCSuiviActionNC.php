<?php
	// echo $_SERVER['REMOTE_ADDR'];
   /*if($_SERVER['REMOTE_ADDR'] != '192.168.10.131')
   {
      exit();
   } */
   require_once("DBConnect.php");
	$zSelectRef = "SELECT fnc_ref FROM nc_fiche ORDER BY fnc_ref ASC";
	$oQuerySelectRef = @pg_query($zSelectRef);
	$iNbSelectRef = @pg_num_rows($oQuerySelectRef);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Suivi des actions</title>

      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      
      <link rel="stylesheet" type="text/css" href="../css/FNCAjout.css?1" />
      <link rel="stylesheet" type="text/css" href="../css/ThickBox.css?1" />
      <link rel="stylesheet" type="text/css" href="css/theme.blue.css?1" />	
      <style>
         div.ui-datepicker{
 font-size:12px;
}

      </style>
      
		

		<style>
			.titre{
				border-bottom: 1px solid black;
				border-right: 1px solid black;
				font-weight: bold;
			}
			.contenu{
				 border-right: 1px solid black;
			}
			table{
				font-family: verdana;
				font-size: 11px;
				border: 1px solid black;
			}
         .wrapper {
           position: relative;
         
           height: 550px;
           overflow-y: auto;
           border:4px solid #1e5799;width:100%;
            direction:ltr;
         }
		</style>

	</head>

	<body>
  
	<form id="frmActionCorrective" method="post" action="?">
    <span id='idContentCache' style='display:none;'>
		<p style="font-family: verdana; font-size: 12px; color: #666666">
			Cette page permet de visualiser les actions correctives dont l'&eacute;tat n'est pas "OK".
	<ul style="font-family: verdana; font-size: 12px">
				<li>
					<font color="#FF0066">En rouge :</font> les actions correctives dont la date de fin est inf&eacute;rieure &agrave; la date du jour.
				</li>
				<li>
					<font color="#FF9933">En orange :</font> les actions correctives dont la date de fin est &eacute;gale &agrave; la date du jour.
				</li>
				<li>
					<font color="#008000">En vert :</font> les actions correctives dont la date de fin est sup&eacute;rieure &agrave; la date du jour.
				</li>
				<li>
					<font color="#000000">En noir :</font> les actions correctives dont aucune date de fin n'est d&eacute;finie.
				</li>
	</ul>
		</p>

<p align="center">
	<!--<input type="button" id="btnEnreg" name="btnEnreg" class="ui-state-default" style="cursor: pointer" value="Enregistrer" />
	<input type="hidden" id="hdnGoEnreg" name="hdnGoEnreg" /> -->
	<a href="toExcel2.php" style="text-decoration: none">
		<input type="button" id="btnExportToExcel" name="btnExportToExcel" class="ui-state-default" value="Export Excel" style="cursor: pointer" />
	</a>
	<input type="button" id="btnAffiche" name="btnAffiche" class="ui-state-default" style="cursor: pointer" value="Afficher toutes les actions" onclick = "afficheActionCorrective ()" style="width:200px;"/> <span id='info' style='display:none;'><b>Modification &eacute;ffectu&eacute;e</b><img src='../images/success.png' height='12' width='12'></span>
   <span id='info_warning' style='display:none;'><b>Veuillez remplir le champ date ! </b><img src='../images/error.png' height='12' width='12'></span><span id='info_warning_comment' style='display:none;'><b>Veuillez remplir le champ commentaire ! </b><img src='../images/error.png' height='12' width='12'></span>
</p>
</span>
<center ><span id='imLoad' style='display:inline;'><img src='images/ajax-loader.gif' /></br><b>Chargement ... </b></span></center>

</br>
		<div class='wrapper' id='idWrapperCache' style='display:none;' >
			<table id="table1" style='display:none;'>
			   <tr><thead>
                <th class="titre" colspan="5">Fiche de non conformit&eacute; </th>
                <th class="titre" rowspan="2">Faille identifi&eacute;e</th>
                <th class="titre" rowspan="2">Impact</th>
                <th colspan="9" class="titre" align="center">Actions</th>
                <th class="titre" rowspan="2">G&eacute;n&eacute;ralisation</th>
                <th class="titre" rowspan="2">Criticit&eacute;</th>
              </tr>
              <tr>
                <!--th class="titre">Nom du client</th-->
                <th class="titre">Type</th>
                
                <th class="titre">Nom du client</th>
                <th class="titre">BU</th>
                <th class="titre">R&eacute;f&eacute;rence</th>
                <th class="titre">Type d'appel</th>
                <th class="titre">Description</th>
                <th class="titre">Responsable</th>
                <th class="titre" style='text-align:center;'>Date d&eacute;but</th>
                <th class="titre" style='text-align:center;'>Date fin </th>
                <th class="titre" style='text-align:center;'>Date suivi</th>
                <th class="titre" style='text-align:center;'>Indicateur d&rsquo;&eacute;fficacit&eacute;</th>
                <th class="titre" style='text-align:center;'>Objectif et &eacute;ch&eacute;ance</th>

                <th class="titre">
					<SELECT id="slctActionValidationFiltre" name="slctActionValidationFiltre" class="titre" onchange="myValidation();">
						<option value="">Validation action</option>
						<option value="en attente">Non entam&eacute;</option>
						<option value="en cours">En cours</option>
						<option value="ok">Valid&eacute;</option>
					</SELECT>
               </th>
                <th class="titre">Commentaire</th>
               
           
              </thead></tr><tbody>

<?php
			$sAdminMsg = "";
			$iCounter = 0;
			$nombre = $_REQUEST["nombre"];
			
			
			
		$zSqlId = "		select * from (
            SELECT 
			               nf.fnc_id,nf.fnc_client,nfa.action_liste_id as id, nfi.date_debut as dated_, nfi.date_fin as datef_, 
			               nfi.etat as etat_,nfi.faille_identifiee as faille_,nfi.impact as imp_,
			               nfi.generalisation as gen_
                     FROM 
                        nc_fiche nf, nc_fnc_action nfa,nc_fnc_infos nfi, nc_action_liste nal
                     WHERE nfi.etat != 'ok'
                     AND nfa.action_liste_id = nal.id
                     AND nf.fnc_id = CAST(nfa.fnc_id as integer)
                     AND nfa.fnc_info_id = nfi.id
                     AND nal.type != 'curative'
                     GROUP BY nf.fnc_id,nf.fnc_client,nfa.action_liste_id , nfi.date_debut, nfi.date_fin, nfi.etat,
                     nfi.faille_identifiee,nfi.impact,nfi.generalisation
                     ORDER BY nfa.action_liste_id  ASC ) as temp
            left join 
            (SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
                        INNER JOIN gu_application a ON 
                        substring(f.fnc_code FROM 1 for 3) = a.code 
                        INNER JOIN  business_unit 
                        b ON b.id_bu = a.id_bu 
                        union
 select lib_bu,fnc_id from nc_fiche ncf 
 inner join business_unit bu
 on ncf.fnc_bu = bu.id_bu) as temp2 
                        on temp2.fnc_id =temp.fnc_id ";
		
		    $rQueryId = @pg_query($conn, $zSqlId) or die(@pg_last_error($conn)) ;
			
		    $clWere = '';
		    $cpt = 0;
		    $coul = 0;
			for ($i = 0 ; $i < @pg_num_rows ($rQueryId) ; $i++){
					$reference = '';
					$type = '' ;
					$resSelectId = @pg_fetch_array ($rQueryId, $i);	
					$id_ = $resSelectId['id'] ;
					$dated_ = $resSelectId['dated_'] ;
					$datef_ = $resSelectId['datef_'] ;
					$etat_ = $resSelectId['etat_'] ;
					$faille_ = $resSelectId['faille_'] ;
					$imp_ = $resSelectId['imp_'] ;
					$gen_ = $resSelectId['gen_'] ;
				
			   
					$zSql = " SELECT DISTINCT 
                           nc_fiche.fnc_ref as ref, nc_fiche.fnc_type AS type					
                        FROM 
                           nc_fnc_action, nc_action_liste, nc_fiche, nc_fnc_infos
                        WHERE 
                           nc_fiche.fnc_id = CAST (nc_fnc_action.fnc_id as integer)
                        AND nc_fnc_infos.id = nc_fnc_action.fnc_info_id
                        AND nc_fnc_action.action_liste_id = $id_ 
                        AND nc_fnc_infos.etat ='$etat_'
                        AND nc_fnc_infos.date_debut ='$dated_'
	 				         AND nc_fnc_infos.date_fin ='$datef_'
									
					";
					
					$rQueryId_ = @pg_query($conn, $zSql) or die(@pg_last_error($conn)) ;
					$iNumId_ = @pg_num_rows ($rQueryId_) ;
               for ($j = 0; $j < $iNumId_; $j++){
               	$resRefId = @pg_fetch_array ($rQueryId_, $j);	
               if ($reference == '') 
               	$reference = $resRefId['ref'] ;
               else 
               	$reference .= ', '.$resRefId['ref'] ;
               //echo "ref==>".$reference."<br/>" ;
               if ($type == '') 
               	$type = $resRefId['type'] ;
               else 
               	$type .= '-'.$resRefId['type'] ; 
               }
				
					$zSqlInfo = "select *from (";
					$zSqlInfo .= "	SELECT DISTINCT nc_fiche.fnc_client,obj_echeance,indic_efficacite,date_debut as datedeb, date_fin as datefin,responsable,
									etat,nc_fiche.fnc_id,nc_fiche.fnc_id as fncid,fnc_ref,faille_identifiee, impact, generalisation, date_suivi, commentaire,
									 nc_fnc_infos.id as idinfo, libelle AS description,indice ";
                           
               $zSqlInfo .= " ,fnc_gravite_id,fnc_frequence_id,fnc_freq_cat_id,fnc_grav_cat_id " ;
               
					$zSqlInfo .= " FROM nc_fnc_infos, nc_fnc_action, nc_action_liste, nc_fiche
									WHERE nc_fnc_infos.id = nc_fnc_action.fnc_info_id
									AND nc_fiche.fnc_id = CAST(nc_fnc_action.fnc_id as integer)
									AND nc_action_liste.id = nc_fnc_action.action_liste_id
									AND nc_fnc_action.action_liste_id = $id_/*1262 */
									AND nc_fnc_infos.etat ='$etat_'
									AND nc_fnc_infos.date_debut ='$dated_'
									AND nc_fnc_infos.date_fin ='$datef_'
					 				AND  nc_fnc_infos.etat != 'ok'
									AND nc_action_liste.type != 'curative' ";
						
						if (isset($_REQUEST['slctActionValidationFiltre'])) {
						if (!empty($_REQUEST['slctActionValidationFiltre'])) 
							$zSqlInfo .= " AND nc_fnc_infos.etat = '{$_REQUEST['slctActionValidationFiltre']}' ";
						}
            
            $zSqlInfo .= ") as temp
                        left join (
                        SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
                                    INNER JOIN gu_application a ON 
                                    substring(f.fnc_code FROM 1 for 3) = a.code 
                                    INNER JOIN  business_unit 
                                    b ON b.id_bu = a.id_bu 
                                    union
                                    select lib_bu,fnc_id from nc_fiche ncf 
                                    inner join business_unit bu
                                    on ncf.fnc_bu = bu.id_bu
                                    ) as temp2 on temp.fnc_id = temp2.fnc_id"; 
				// echo $zSqlInfo ;
             // echo '</br>';
             // echo '<pre>';
             // print_r($zSqlInfo);
             // echo '</pre>';
            
				$rQueryInfo = @pg_query($conn, $zSqlInfo) or die(@pg_last_error($conn)) ;
				
				$nombre_enreg = pg_num_rows($rQueryInfo);
				//echo '***'.$nombre_enreg.'****';
				
				$toRes = @pg_fetch_array ($rQueryInfo, 0);
            //echo $toRes['fnc_id'] ;
            $obj = $toRes['obj_echeance'];
            $eff = $toRes['indic_efficacite'];
              $idFNC = $toRes['fncid'];
            /****************** Modif Fulgence 20150210  ******************/
            // Affichage criticit&eacute;
            if($toRes['fnc_grav_cat_id'] != '' && $toRes['fnc_freq_cat_id'] != '')
            {
               //if($toRes['fnc_grav_cat_id'] != '')
                  $cat_id_grav = $toRes['fnc_grav_cat_id'] ;
               /*else
                  $cat_id_grav = 1 ;
               
               if($toRes['fnc_freq_cat_id'] != '')*/
                  $cat_id_freq = $toRes['fnc_freq_cat_id'] ;
               /*else
                  $cat_id_freq = 1 ;*/
               
               // gravit&eacute;
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav=$cat_id_grav " ;
               //echo $sqlSltGrv ;
               $resGrv = pg_query($conn, $sqlSltGrv) or die (pg_last_error($conn)) ;
               $arGrv = pg_fetch_array($resGrv) ;
               $grv_ech = $arGrv['echelle_id_grav'] ;
               
               //Fr&eacute;quence
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
               $colr = ($i%2 == 0 ? "odd" : "style='background-color:#FFFFFF;font-weight:bold;'") ;
               $criticite = "" ;
            }
            /****************** Fin modif ******************/
				
				$coul =($i%2==1?"#ffffff":"#ebebeb");

				if(empty($toRes['datefin'])) $color = "#000000";
				else{
					if($toRes['datefin'] < date("Y-m-d")) $color = "#FF0066";
						elseif($toRes['datefin'] == date("Y-m-d")) $color = "#FF9933";
							else $color = "#008000";
				}
				$aDescrition = explode("*#|#*", $toRes['description']);

				$types = explode("-", $type) ;
				$types = explode("-", $type) ;
				$references = explode(",", $reference);
            
            //echo $toRes['fnc_ref'] ;
				$bu = $toRes['lib_bu'];
				$clt = $toRes['fnc_client'];
          
				echo "	<tr style=\"color: $color;\" bgcolor=\"$coul\"  >
						<td class='contenu' valign='top' style='cursor:pointer;' onclick=viewFNC(".$idFNC."); >&nbsp;".$type."</td>
                  <td class='contenu' valign='top'>&nbsp;".$clt."</td>
					  <td class='contenu' valign='top'>&nbsp;".$bu."</td>
						<td class='contenu' valign='top'>&nbsp;<a style=\"text-decoration: none; color: $color;\" href=\"FNCConsulter.php?txtRetour=1&txtRef=".$toRes['fnc_ref']."&txtId=".$toRes['fnc_id']." \" >";
						foreach ($references as $r => $v){
							$virg = (($r == 0) ? '' : ', ') ;
							echo $virg.'<a style="text-decoration:none;color:'.$color.';" href="javascript: document.location.href = \'FNCConsulter.php?txtRef='.$v.' \'">'.$v.'</a>';
						}
                  
                  $b_fncid= $toRes['fnc_id'];
                  if(isset($idFNC))
                  {
                     $sqlAppeType = "
                     select libelle_typologie from cc_sr_typologie cc_typo inner join nc_fiche  ncf
                     on ncf.id_cc_sr_typo = cc_typo.id_typologie
                     where fnc_id = ".$idFNC;
                     
                     $resAppeType= pg_query($conn, $sqlAppeType) or die (pg_last_error($conn)) ;
                     $arAppeType = pg_fetch_array($resAppeType) ;
                     $gAppeType = $arAppeType['libelle_typologie'] ;
                     // $typeApp = 'Aucun';
                     if($gAppeType == '')
                        {
                           $typeApp = 'Aucun';
                        }
                      else 
                      {
                        $typeApp = $gAppeType;
                      }
                  }
						echo "</a></td>
							
							<td class='contenu' valign='top' style='text-align: center;'>&nbsp;".$typeApp."</td>
							<td class='contenu' valign='top'>&nbsp;".$toRes['faille_identifiee']."</td>
							<td class='contenu' valign='top'>&nbsp;".$toRes['impact']."</td>
							<td class='contenu' valign='top' style='cursor:pointer;' onclick=viewFNC(".$idFNC."); >&nbsp;".$aDescrition[0]."</td>
							<td class='contenu' valign='top'>&nbsp;".$toRes['responsable']."</td>
							<td class='contenu' valign='top'>&nbsp;".$toRes['datedeb']."</td>
							<!--td class='contenu' valign='top'>&nbsp;".$toRes['datefin']."</td-->" ;
                     
                     $b_comment = utf8_decode($toRes['commentaire']);
                     $b_infoid = $toRes['idinfo'];
                     $b_val = $b_infoid."||".$b_comment;
                     echo "<input type='hidden' value = '".$b_infoid."' id = 'bvalId_".$i."'>";
                     // $up_comment = utf8_decode($toRes['commentaire']);
                      $up_comment=$i; 
                    
?>
							<td class='contenu' valign="top">
								<input class="kilasy" type="text" onchange= "upComment(<?php echo $i; ?>);" id="txtDateFin<?php echo $i?>" name="txtDateFin<?php echo $i?>" style="width: 100px; height: 19px; cursor: text; background: white; border: 1px solid gray" value="<?php echo $toRes['datefin']; ?>" readonly />
                        <input  type ="hidden" id="tDateFin<?php echo $i?>" name="txtDateFin<?php echo $i?>"  value="<?php echo $toRes['datefin']; ?>" readonly />
							</td>

							<td class='contenu' valign="top">
								<input class="kilasy " onchange = "upComment(<?php echo $i; ?>);" type="text" id="txtDateSuivi<?php echo $i?>" name="txtDateSuivi<?php echo $i?>" style="width: 100px; height: 19px; cursor: text; background: white; border: 1px solid gray" value="<?php echo $toRes['date_suivi']; ?>" readonly />
                        <input type='hidden' value="<?php echo $toRes['date_suivi']; ?>" id="hide_suivi<?php echo $i;?>" />
							</td>
                        <td class='contenu' valign="top"><?php echo $eff; ?></td>
                       <td class='contenu' valign="top"><?php echo $obj; ?></td>
                      
							<td class='contenu' valign="top">
								<SELECT id="slctActionValidation<?php echo $i ; ?>" name="slctActionValidation<?php echo $i; ?>" style="width: 100px; height: 17px" onchange="myFunction(<?php echo $i ; ?>,<?php echo $idFNC ; ?>)">
									<option value="">** choisir **</option>
									<option value="en attente" <?php if ($toRes['etat'] == "en attente") echo "selected"; ?>>non entam&eacute;</option>
									<option value="en cours" <?php if ($toRes['etat'] == "en cours") echo "selected"; ?>>en cours</option>
									<option value="ok" <?php if ($toRes['etat'] == "ok") echo "selected"; ?>>valid&eacute;</option>
								</SELECT>
							</td>
                     
							<td class='contenu' valign="top">
								<textarea   onblur = "upComment(<?php echo $i; ?>);" class ='<?php echo $i; ?>' id="txtComment<?php echo '#'.$b_infoid.'#'.$i; ?>" name="txtComment<?php echo $i ; ?>" style="width: 120px; height: 60px" ><?php echo $toRes['commentaire']; ?></textarea>
                        <textarea    id="txtComment<?php echo $i; ?>" name="txtComment<?php echo $i ; ?>" style="display:none;" ><?php echo $toRes['commentaire']; ?></textarea>
							</td>
                     
							<input type="hidden" name="idaction<?php echo $i ; ?>" id="idaction<?php echo $i ;?>" value="<?php echo  $id_;?>"> 
							<input type="hidden" name="idinfo<?php echo $i ; ?>" id="idinfo<?php echo $i ; ?>" value="<?php echo $reference ; ?>"> 
							
		<?php
				
            $hideId = $toRes['idinfo'];
            if(($hideId >=3505 && $hideId <= 3664) && ($toRes['generalisation'] == $toRes['indic_efficacite']) )
               echo "		<td class='contenu' valign='top'>&nbsp;</td> " ;
            else 
               echo "		<td class='contenu' valign='top'>&nbsp;".$toRes['generalisation']."</td> " ;
            echo "		<td $colr valign='top' >&nbsp;".$criticite."</td> " ;
				
		?>
                     
                    
		<?php
						echo "</tr>";
				$coul++;
			}
			
			
		?>
		 <input type="hidden" id="nombre" name="nombre" value="<?php echo @pg_num_rows ($rQueryId); ?>">
			</tbody></table>
		</div>
	</form>
 	<?php 
         echo "<input type = 'hidden' value = '".$i."' id = 'nbr_i'>";
   ?>
	<p style="color: #B1221C; font-weight: bold; font-size: 12px"><?php echo $sAdminMsg; ?></p>
<script type="text/javascript" src="js/jquery-1.11.3.min.js?1"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js?1"></script>
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../js/thickbox.js"></script>
	</body>
</html>
<script type="text/javascript">
       $(function () {
       $("#table1").show();
       $("#idWrapperCache").show();
       $("#idContentCache").show();
       $("#imLoad").hide();
       $('#table1').tablesorter({
        theme: 'blue',
         widthFixed : true,
        widgets: ['zebra', 'stickyHeaders'],
        headerTemplate : '{content} {icon}', // Add icon for various themes
        widgets: [ 'zebra', 'stickyHeaders','filter'],
        widgetOptions: {
      // jQuery selector or object to attach sticky header to
      stickyHeaders_attachTo : '.wrapper' // or $('.wrapper')
      
    },
        headers: { 0: { sorter: false}, 17: {sorter: false}, 3: {sorter: false} } 
    });});
    
			$(document).ready(function(){
				$("#hdnGoEnreg").val("");
				$(".kilasy").datepicker({inline: true, changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});

				$("#slctActionValidationFiltre").change(function() {
            var slctActionValidationFiltre = $("#slctActionValidationFiltre").val();
               // alert('xx'+slctActionValidationFiltre);
               // return 0;
					$("#frmActionCorrective").submit();
				});
               // $('th').on('click',function(){
                  // alert('xx');
               // });
				$("#btnEnreg").click(function() {
					$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>');
					$("div.dialog").removeAttr("title");
					$("div.dialog").attr({title: "Enregistrement"});
					$("p.pMessage").html("Voulez vous enregistrer les modifications?");
					$("div.dialog").dialog({
						modal: true,
						overlay: {
							backgroundColor: '#000000',
							opacity: 0.5
						},
						buttons: {
							'Enregistrer': function() {
								//$("#hdnGoEnreg").val("go");
                        /*
                        txtComment#3781#1
                        
                         <input type='hidden' value = '".$b_infoid."' id = 'bvalId_".$i."'>";
                            
                        */
                        var b_var_to_up = new Array ();
                        var b_date_fin = new Array ();
                        var b_date_suivi = new Array ();
                        var nbr_list = $("#nbr_i").val();
                        nbr_list = parseInt(nbr_list, 10)
                         
                        for(var i = 0 ; i<nbr_list ; i++)
                        {
                           var content_comment = $('textarea[name=txtComment'+i+']').val();
                             // id_info +'||'+content_comment+||date_fin||date_suivi
                             var id_info = $("#bvalId_"+i).val();
                             var txt_date_fin = $("#txtDateFin"+i).val();
                             var txt_date_suivi = $("#txtDateSuivi"+i).val();
                             var comment_final = id_info +'||'+content_comment+'||'+txt_date_fin+'||'+txt_date_suivi;
                             b_var_to_up.push(comment_final);
                            
                           
                           
                        }
                       // alert(b_var_to_up);
                        
                        $.ajax({
                           type: "POST",
                           url: "FNCUpdateAcionCorr.php",
                           data: { 
                                 comment : 'comment',
                                 data : b_var_to_up
                                   
                                 },	
                           success: function(rslt){},
                           async: true
                        });
                        // return 0;
                       
                       
                        /*return 0;*/
								$("#frmActionCorrective").submit();
							},
							'Annuler': function() {
								$(this).dialog('close') ;
							}
						}
					});
				});
			});
function upComment(x)
{
   
   var tmp_comment = $('#txtComment'+x).val();
   var content_fin = $('#txtDateFin'+x).val();
   var content_suivi = $('#txtDateSuivi'+x).val();
   var content_comment = $('.'+x).val();
   
   var tmp_date_fin = $("#tDateFin"+x).val();
   var tmp_suivi = $("#hide_suivi"+x).val();
   
   if(content_fin == '' || content_suivi == '' || content_comment.trim() == '')
   {
     
      if(content_fin == ''){
         $("#info_warning").show();
         $('#txtDateFin'+x).focus();
         setTimeout(function(){  $("#info_warning").css('display','none'); }, 3000);
           }
      if(content_suivi == ''){
         $("#info_warning").show();
         $('#txtDateSuivi'+x).focus();
         
         setTimeout(function(){  $("#info_warning").css('display','none'); }, 3000);
         }
      if(content_comment == ''){
            
            $("#info_warning_comment").show();
            setTimeout(function(){  $("#info_warning_comment").css('display','none'); }, 4000);
            }
           
          
      return 0;
   }
  
   if(confirm('Voulez-vous vraiment modifi\351e?'))
   {
      var content_comment = $('.'+x).val();
      var id = $('.'+x).attr("id");
      var id_infos = id.split("#");
      var id_inf = id_infos[1];
      
      
      upSuivi(x);
      upFin(x);
      
      $.ajax({
         type: "POST",
         url: "FNCUpdateAcionCorr.php",
         data: { 
               comment : 'comment_only',
               data : content_comment,
               id_inf:id_inf
               },	
         success: function(rslt){
            
            if(rslt == 1)
            {
               // alert('Mise \340 jour effectu\351e !');
               $("#info").show();
               setTimeout(function(){  $("#info").css('display','none'); }, 1500);
            }
         },
         async: false
      });
     
   }
   else
   {
      $('.'+x).val(tmp_comment);
      $('#txtDateFin'+x).val(tmp_date_fin);
      $('#txtDateSuivi'+x).val(tmp_suivi);
      return 0 ;
   
   }
  
  
}
function myValidation()
{
   // var xx = $("#slctActionValidationFiltre").val();alert('3'+xx);
   $("#frmActionCorrective").submit();
}
   // Fonction affichage action corrective
function afficheActionCorrective ()
{
   document.getElementById('frmActionCorrective').action = "FNCAfficheActionCorrective.php" ;
   document.getElementById('frmActionCorrective').submit() ;
   //NC_SIR_160204
}

         /******/
 function viewFNC(idFNC)
   {
      var widthScreen = screen.width;
         widthScreen -= 50;
      var test = 0;
         // alert('ppp'+idFNC);return 0;
        tb_show('','FNCConsulter.php?height=600&width='+widthScreen+'&idFNC='+idFNC+'&varTest='+test+'&corrective=1');
      widthScreen = screen.width;
}
function myFunction(id,idfnc)
{
  var b_slctActionValidation = $("#slctActionValidation"+id).val();
    // var b_slctActionValidation = $("#slctActionValidation").val();
       // alert(b_slctActionValidation+'idfnc'+idfnc);
  /*if(b_slctActionValidation !='' && b_slctActionValidation != undefined )
  {
       $.get("FNCUpdateAcionCorr.php",
      {
         etat : b_slctActionValidation,
         idfnc : idfnc
      },
      function(_result){ }
   );*/
   
   $.ajax({
            type: "POST",
            url: "FNCUpdateAcionCorr.php",
            data: { 
                  etat : b_slctActionValidation,
                  idfnc : idfnc
                    
                  },	
            success: function(rslt){},
            async: false
      });
      
      
  }
function upSuivi(x)
{
  var id = $('.'+x).attr("id");
  var tmp_suivi = $("#hide_suivi"+x).val();
  console.log(tmp_suivi);
  
      var content_comment = $('#txtDateSuivi'+x).val();
      var id = $('.'+x).attr("id");
      console.log(content_comment+'*****'+id);
      var id_infos = id.split("#");
      var id_inf = id_infos[1];
      console.log('x:'+id_infos[0]);
      // return 0;
      $.ajax({
         type: "POST",
         url: "FNCUpdateAcionCorr.php",
         data: { 
               comment : 'date_suivi',
               data : content_comment,
               id_inf:id_inf
               },	
         success: function(rslt){
            if(rslt == 1)
            {
               // alert('Mise \340 jour effectu\351e !');
            }
         },
         async: true
      });
     
  
}
function upFin(x)
{
  var id = $('.'+x).attr("id");
  var tmp_date_fin = $("#tDateFin"+x).val();
   
   var content_comment = $('#txtDateFin'+x).val();
   var id = $('.'+x).attr("id");
   var id_infos = id.split("#");
   var id_inf = id_infos[1];
   
      // console.log('x:'+id_inf);
      // return 0;
      $.ajax({
         type: "POST",
         url: "FNCUpdateAcionCorr.php",
         data: { 
               comment : 'date_fin',
               data : content_comment,
               id_inf:id_inf
               },	
         success: function(rslt){
            if(rslt == 1)
            {
               // alert('Mise \340 jour effectu\351e !');
            }
         },
         async: true
      });
      // alert(1);
  
  
}
		</script>