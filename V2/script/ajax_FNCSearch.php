

 <?php
      require_once ("DBConnect.php");
      echo "<p style = \"color: #999999; padding-left: 10%; \">".$_SESSION["MSGSearch"]."</p>";

      $slctClientName      = $_REQUEST['slctClientName'] ;
      $zClient      	   = $_REQUEST['slctClientName'] ;
      $slctCode            = $_REQUEST['slctCode'] ;
      $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
      $txtModifDate        = $_REQUEST['txtModifDate'] ;
      $txtCreateurMatr     = $_REQUEST['txtCreateurMatr'] ;   
      $s_txtCP             = $_REQUEST['txtCP'] ;
      $slctTraitStatut     = $_REQUEST['slctTraitStatut'] ;
      $slctType            = $_REQUEST['slctType'] ;
      $slctTraitStatutCor  = $_REQUEST['slctTraitStatutCor'] ;

      $fnc_motif = $_REQUEST['motif_fnc'] ;

   if(isset($_REQUEST["search"]) == 1) 
   {
     
   }

	//if (!isset($_REQUEST['bntSubmit']))
	else{
    // echo $_REQUEST["search"]; exit("x");
     
     
      //echo "te ".$txtCrationDate." fef" ;
     /*  $zSqlFNCduJour  = "select *from(";
      $zSqlFNCduJour .= "SELECT * FROM nc_fiche WHERE \"fnc_creationDate\" BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-d")."' ORDER BY \"fnc_creationDate\" DESC, \"fnc_creationHour\" DESC" ;
       $zSqlFNCduJour  .= ") as temp
            left join 
            (
            SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON 
            substring(f.fnc_code FROM 1 for 3) = a.code 
            INNER JOIN  business_unit 
            b ON b.id_bu = a.id_bu 
            ) as temp2 on temp.fnc_id=temp2.fnc_id"; */
     $clauseDate = "";
     if(isset($_REQUEST['txtModifDate']) && isset($_REQUEST['txtCreateurMatr']))
     {
         $txtCrationDate      = $_REQUEST['txtCrationDate'] ;
         $txtModifDate        = $_REQUEST['txtModifDate'] ;
         $clauseDate = "BETWEEN '".$txtCrationDate ."' and '".$txtModifDate."'";
     }
     else
     {
         $txtCrationDate      = date("Y-m")."-01";
         $txtModifDate        = date("Y-m-d");
         $clauseDate = "BETWEEN'".$txtCrationDate ."' and  '".$txtModifDate."'";
     }
        
      $onsearch = $_REQUEST['onsearch'] ;
       $zSqlFNCduJour = "";
         /*    $zSqlFNCduJour = "select (case when fnc_bu is not null then (select bu.lib_bu as xx from nc_fiche ncf inner join business_unit bu on bu.id_bu= ncf.fnc_bu where ncf.fnc_bu is not null) else 'x' end ) as bu_audit,*from(SELECT * FROM nc_fiche WHERE \"fnc_creationDate\"
            BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-d")."' ORDER BY \"fnc_creationDate\" DESC, 
            \"fnc_creationHour\" DESC) as temp left join ( SELECT distinct lib_bu,fnc_id FROM nc_fiche f 
            INNER JOIN gu_application a ON substring(f.fnc_code FROM 1 for 3) = a.code INNER JOIN business_unit b ON b.id_bu = a.id_bu 
            ) as temp2 on temp.fnc_id=temp2.fnc_id";   */
          $zSqlFNCduJour ="select res3.fnc_id, (case when (bu_fnc is  null and lib_bu <>'') then lib_bu else bu_fnc end ) as bu_fnc2,fnc_code,fnc_ref,fnc_cp,fnc_comm,\"fnc_creationDate\",
               fnc_type,fnc_motif,fnc_exigence,fnc_statut,fnc_cause,\"fnc_reponseDate\",\"fnc_reponseRef\",fnc_createur,
               fnc_validateur,\"fnc_modifDate\",\"fnc_modif_Matricule\",
               fnc_valide,fnc_client,fnc_version,fnc_imputation,  fnc_typologie, \"fnc_creationHour\",\"fnc_actionCStatut\",\"fnc_actionNCStatut\",fnc_process,fnc_classement,
               fnc_module,  fnc_typo,fnc_autre_cplmnt,fnc_traitement,fnc_id_grille_application,fnc_id_notation, fnc_gravite_id,fnc_frequence_id,
               fnc_freq_cat_id,fnc_grav_cat_id from (SELECT *FROM nc_fiche WHERE \"fnc_creationDate\"   $clauseDate  ORDER BY \"fnc_creationDate\" DESC, \"fnc_creationHour\" DESC) as res3
               left join (
               SELECT distinct lib_bu,fnc_id FROM nc_fiche f INNER JOIN gu_application a ON substring(f.fnc_code FROM 1 for 3) = a.code 
               INNER JOIN business_unit b ON b.id_bu = a.id_bu 
               ) as res4 on res3.fnc_id = res4.fnc_id
               left join (
               select *from (
               select (case when fnc_bu is not null then xx else 'xx' end ) as bu_fnc, *from (
               select bu.lib_bu as xx,fnc_bu,fnc_id from nc_fiche ncf inner join business_unit bu on bu.id_bu= ncf.fnc_bu where ncf.fnc_bu is not null ) as res_bu ) as res2
               ) as res5 on res3.fnc_id = res5.fnc_id
"; /*<<< requete pour rechercher fnc onload*/
         
      if(isset($onsearch) && ($onsearch == 1))
      {
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
         
         
         // print_r($_REQUEST);
         $zSqlFNCduJour .= "
         WHERE 1=1";                   
         if (isset($fnc_motif))
            $zSqlFNCduJour .= " AND fnc_motif ilike '%$fnc_motif%' " ;
                        
         if($zCode != "") $zSqlFNCduJour .= " AND (fnc_code LIKE '%$zCode%')" ;
         if($zCreateurMatr != '') $zSqlFNCduJour .= " AND (fnc_createur ILIKE '%$zCreateurMatr%')" ;
         if($slctClientName != "") $zSqlFNCduJour .= " AND (fnc_client ILIKE '%$slctClientName%')" ;
         
         if ($_REQUEST['slctTraitStatut'] == "aucune_action_corrective") {
            $zSqlFNCduJour .= " AND	((\"fnc_actionCStatut\" = '') || (\"fnc_actionCStatut\" = 'en attente')  ";
         } elseif ($_REQUEST['slctTraitStatut'] == "avec_action") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionCStatut\" = 'en cours') ";
         } elseif ($_REQUEST['slctTraitStatut'] == "actions_correctives_terminees") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionCStatut\" = 'ok') ";
         } elseif ($_REQUEST['slctTraitStatut'] == "fiche_cloturee") {
            $zSqlFNCduJour .= " AND fnc_statut = 'bouclé' ";
         } else {
            $zSqlFNCduJour .= "";
         }
         
         
         if ($_REQUEST['slctTraitStatutCor'] == "aucune_action_corrective") {
            $zSqlFNCduJour .= " AND	((\"fnc_actionNCStatut\" = '') || (\"fnc_actionNCStatut\" = 'en attente') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "avec_action") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionNCStatut\" = 'en cours') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "actions_correctives_terminees") {
            $zSqlFNCduJour .= " AND	(\"fnc_actionNCStatut\" = 'ok') ";
         } elseif ($_REQUEST['slctTraitStatutCor'] == "fiche_cloturee") {
            $zSqlFNCduJour .= " AND fnc_statut = 'bouclé' ";
         } else {
            $zSqlFNCduJour .= "";
         }
         

         if($zClient != '') $zSqlFNCduJour .= " AND (fnc_client LIKE '%$zClient%')" ;
         if($zCP != '') $zSqlFNCduJour .= " AND (fnc_cp LIKE '%$zCP%')" ;
         if($zType != '') $zSqlFNCduJour .= " AND (fnc_type LIKE '%$zType%')" ;
         $zSqlFNCduJour .= " ORDER BY \"fnc_creationDate\", \"fnc_creationHour\" ASC" ;
         
            
      }
    // echo  $zSqlFNCduJour;
		$oFNCduJour = @pg_query($conn, $zSqlFNCduJour) ;
		$iNbFNCduJour = @pg_num_rows($oFNCduJour) ;

		if($iNbFNCduJour != 0)
		{
      
      // $contentFieldset2 ="
      // <fieldset>
			// <legend>R&eacute;sultats</legend>";
			
      $contentTable2 ="
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
            <tbody>
      ";
      
      for($i = 0; $i < $iNbFNCduJour; $i ++)
			{
				$toFNCduJour = @pg_fetch_array($oFNCduJour) ;
				$sqlCreater = "SELECT prenompersonnel FROM personnel WHERE matricule = ".$toFNCduJour['fnc_createur'] ;
				$resCreater = @pg_query($conn, $sqlCreater) ;
				$oCreateur = @pg_fetch_array($resCreater,0) ;
				$couleur = ($i%2 == 0 ? "odd":"");
            
            $hidden = "";
            $fncIdHidden = $toFNCduJour['fnc_id'];
            $fncRefHidden =$toFNCduJour['fnc_ref'];
            $hidden .= "
            <input type='hidden' id='ID_{$i}'value='{$fncIdHidden}'>
            <input type='hidden' id='REF_{$i}'value='{$fncRefHidden}'>
            ";
            
            echo $hidden;
            
            if ($toFNCduJour['fnc_valide'] == 'f') 
             $fncValide = 'non'; 
            else 
               $fncValide = 'oui' ;
               
            if($toFNCduJour['fnc_freq_cat_id'] !='' && $toFNCduJour['fnc_freq_cat_id'] !='') 
            {
                $cat_id_grav = $toFNCduJour['fnc_grav_cat_id'] ;
                $cat_id_freq = $toFNCduJour['fnc_freq_cat_id'] ;
                
                 // gravité
               $sqlSltGrv = "SELECT id_categorie_grav,echelle_id_grav FROM nc_gravite_categorie WHERE id_categorie_grav='$cat_id_grav' " ;
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
               else
                  $criticite = "p" ;
               
               // test de couleur
               if ($criticite == "m")
               {
                  $color = "style='background-color:#FCF03F;font-weight:bold;'" ;
                  $criticite = "mineure" ;
               }
               elseif ($criticite == "M")
               {
                  $color = "style='background-color:#F28810;font-weight:bold;'" ;
                  $criticite = "Majeure" ;
               }
               elseif ($criticite == "C")
               {
                  $color = "style='background-color:#E71D07;color:#FFFFFF;font-weight:bold;'" ;
                  $criticite = "Critique" ;
            }
            
         }
         else
         {
            $color = ($i%2 == 0 ? "odd" :"style='background-color:#FFFFFF;font-weight:bold;'") ;
            $criticite = "" ;
         }

				$client = $toFNCduJour['fnc_client'] ;
				if (trim($toFNCduJour['fnc_client']) == 'Autres') 
               $client = $toFNCduJour['fnc_autre_cplmnt'];
           
            $toFNCduJourAudit_buTab2 =trim($toFNCduJour['bu_audit']);
           if($toFNCduJourAudit_buTab2 != 'x')
           {
               $toFNCduJourLib_buTab2 =$toFNCduJourAudit_buTab2;
           }
           else
           {
               $toFNCduJourLib_buTab2 =$toFNCduJour['lib_bu'];
           }
           
           $toFNCduJourCodeTab2 = $toFNCduJour['fnc_code'];
           $toFNCduJourLib_buTab2 = $toFNCduJour['bu_fnc2'];
          
           
           $toFNCduJourFnc_refTab2 =$toFNCduJour['fnc_ref'];
           $toFNCduJourFnc_creationDateTab2 =$toFNCduJour['fnc_creationDate'];
           $toFNCduJourFnc_creationDateTab2 =$toFNCduJour['fnc_creationDate'];
           $toFNCduJourFnc_motifTab2 =$toFNCduJour['fnc_motif'];
           $toFNCduJourFnc_typeTab2 =$toFNCduJour['fnc_type'];
           $toFNCduJourFnc_versionTab2 =$toFNCduJour['fnc_version'];
           $toFNCduJourFnc_idTab2 =$toFNCduJour['fnc_id'];
           
           $contentTable2 .="
           <tr id='btnMenu' class='".$couleur."'>
               <td width='5%'>".$toFNCduJourCodeTab2."</td>
               <td width='9%'>".$client."</td>
               <td width='3%'>".$toFNCduJourLib_buTab2."</td>
               <td width='12%'>".$toFNCduJourFnc_refTab2."</td>
               <td width='7%'>".$toFNCduJourFnc_creationDateTab2."</td>
               <td width='8%'>".$oCreateur['prenompersonnel']."&nbsp;(".$toFNCduJour['fnc_createur'].")</td>
               <td width='5%'>".$fncValide."</td>
               <td width='16%'>".$toFNCduJourFnc_motifTab2."</td>
               <td width='6%'>".$toFNCduJourFnc_typeTab2."</td>
               <td width='7%'>".$toFNCduJourFnc_versionTab2."</td>
               <td width='7%' $color >".$criticite."</td>
           ";
			$contentTable2 .="
         <td  align='center' width='7%'>
            <input type='button' id='btnConsulter_".$toFNCduJourFnc_idTab2."' name='btnConsulter' value='consulter' class = 'ui-state-default'  onclick=viewFNC(".$toFNCduJourFnc_idTab2."); >
         </td>
         ";	
         
         
      $bfnc_id = $toFNCduJour['fnc_id'];
      $bfnc_ref = $toFNCduJour['fnc_ref'];
      $bslctClientName = $slctClientName;
      $bslctCode = $slctCode;
      $bzDateCeation1 = $zDateCeation1;
      $bzDateCeation2 = $zDateCeation2;
      $btxtCreateurMatr = $txtCreateurMatr;
      $bs_txtCP = $s_txtCP;
      $bslctTraitStatut = $slctTraitStatut;
      $bslctType = $slctType;
      $bslctTraitStatutCor = $slctTraitStatutCor;
      $bfnc_motif = $fnc_motif;
      
      $contentTable2 .="
      <td  align='center' width='7%'>   
         <input type='button' id='btnModifier_".$bfnc_id."' name='btnModifier' value='Modifier' class = 'ui-state-default'  onclick=editFNC(".$bfnc_id.",0); >
      </td>
      ";
      
       

			}
      $contentTable2 .="
      </tr>";
		}
     $contentTable2 .="</tbody></table>";
	}
   echo $contentTable2;
      ?>