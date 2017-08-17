<?php
	    
   //session_start();
   include ('../lib_mail/class.phpmailer.php');
   include("/var/www.cache/dgconn.inc");

   function mailEnvoiFnc($slctClientName,$slctCode,$slctGravite,$slctFrequence,$txtMotif) 
   {
      global $conn;
   
      // Recherche de client 
      $sqlCli  = "SELECT nom_client FROM gu_client WHERE id_client = '$slctClientName' " ;
      $resCli  = pg_query($conn,$sqlCli) or die (pg_last_error($conn)) ;
      $arrCli  = pg_fetch_array($resCli) ;
      $sCli = $arrCli['nom_client'];
       
      // Code Prestation
      if(strlen($slctCode) == 6) {
         $prest_cod = substr($slctCode,0,3) ;
      }
      
      if(strlen($slctCode) == 7) {
         $prest_cod = substr($slctCode,1,3) ;
      }
      
      //Gravite
      $sqlGrv = "SELECT libelle_gravite FROM nc_gravite_categorie WHERE id_categorie_grav = $slctGravite " ;
      $resGrv = pg_query($conn, $sqlGrv) or die(pg_last_error($conn)) ;
      $arrGrv = pg_fetch_array($resGrv) ;
      $sGrv = $arrGrv['libelle_gravite'] ;
      
      // Frequence
      $sqlFrq = "SELECT libelle_frequence FROM nc_frequence_categorie WHERE id_categorie_freq = $slctFrequence " ;
      $resFrq = pg_query($conn, $sqlFrq) or die (pg_last_error($conn)) ;
      $arrFrq = pg_fetch_array($resFrq) ;
      $sFrq = $arrFrq['libelle_frequence'] ;
      
      $date_du_jour = date("Y-m-d") ;
      
      
      // $s_motif = 
 
      $mail = new PHPmailer();
      $mail->IsHTML(true);
      $mail->From     ="donotreply@vivetic.mg"; // votre adresse
      //$mail->FromName = 'si@vivetic.mg'; // votre nom
      $mail->Subject  = utf8_decode('FNC critique ouverte sur')." ".$sCli; // sujet de votre message
     
      $header_mail = "";
      $message  = "
      <style type='text/css'>
      .table_style{
         border-width: 0.5pt; 
         border-style: solid; 
         border-color: windowtext;	
         width:400px;
         font-family: Arial;
         font-size:12px;
         padding:3px;
      }
      .table_style_left{
         /*border-width: 0.5pt; 
         border-style: solid; 
         border-color: windowtext;	*/
         width:150px;
         font-family: Arial;
         font-size:12px;
         color: #00007f;
      }
      .link{
         color:#4398DE;
         text-decoration:none;
      }
      #id_div_corps_mail{
         font-family: Arial;
         font-size:12px;
      }
      </style>
      <div id='id_div_corps_mail'>
         <p>Bonjour ,</p>
         <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Une FNC critique vient d'&ecirc;tre ouverte sur :</p>
         <br />
         <!--<table  cellspacing='0' cellpadding='0' border='0' >-->
         <table>

         <tr>
         <td class='table_style_left'>Nom du client:</td><td class='table_style'>&nbsp;".$sCli."</td>
         </tr>

         <tr>
         <td class='table_style_left'>Prestation :</td><td class='table_style'>&nbsp;".$prest_cod."</td>
         </tr>

         <tr>
         <td class='table_style_left'>Motif de cr&eacute;ation :</td><td class='table_style'>&nbsp;".$txtMotif."</td>
         </tr>

         <tr height='90px;' >
         <td class='table_style_left'>Gravit&eacute; :</td><td class='table_style' valign='top' >&nbsp;".$sGrv."</td>
         </tr>

         <tr height='90px;'>
         <td class='table_style_left'>Fr&eacute;quence :</td><td class='table_style' valign='top'>&nbsp;".$sFrq."</td>
         </tr>


         </table>
         <br />

         <!--<a class='link' href='http://192.168.10.24/intranet_light/modules/fnc/script/FNCSearch.php?slctClientName=".$slctClientName."&txtCrationDate=".$date_du_jour."&txtModifDate=".$date_du_jour."&motif_fnc=&btnSubmit=Rechercher&search=1'><b>Voici le lien vers la GPAO sur la FNC cr&eacute;&eacute;e</b></a>-->
         <a class='link' href='http://192.168.10.24/intranet_light/modules/fnc/script/FNCSearch.php?slctClientName=".$sCli."&txtCrationDate=".$date_du_jour."&txtModifDate=".$date_du_jour."&motif_fnc=&btnSubmit=Rechercher&search=1'><b>Voici le lien vers la GPAO sur la FNC cr&eacute;&eacute;e</b></a>
         </div>";
         $footer_mail = "<br /><div style='padding-bottom: 15px;'>Cordialement,<br/><br/> <span class='link' ><b>La Direction Qualit&eacute;</b> </span><br/> 
         <!--<img src='lib_mail/img/logo_vivetic_mail.png' alt='VIVETIC' />-->
         <!--img src='cid:logo_vvt' --/>
      </div>";
        
               
       $mail->AddAddress('direction_tana@vivetic.mg', 'Direction Tana'); 	// adresse du destinataire
       $mail->AddAddress('mbola@vivetic.mg', 'mbola@vivetic.mg'); 	// adresse du destinataire
       $mail->AddAddress('tovo.randriamihaja@vivetic.mg', 'tovo.randriamihaja@vivetic.mg');
       $mail->AddAddress('si@vivetic.mg', 'si@vivetic.mg');
       $mail->AddAddress('tolotra@vivetic.mg', 'tolotra@vivetic.mg');
       $mail->AddAddress('celestine@vivetic.mg', 'celestine@vivetic.mg');
       $mail->AddCC('chq_vivetic@vivetic.mg', 'chq_vivetic@vivetic.mg');
       $mail->AddCC('sc_qualite@vivetic.mg', 'sc_qualite@vivetic.mg');
      $mail->Body=$header_mail.$message.$footer_mail;

      if(!$mail->Send()){ // on teste la fonction Send() -> envoyer 
      echo $mail->ErrorInfo; //Affiche le message d'erreur 
      }
      else{      
      // echo 'Mail envoy&eacute; avec succ&egrave;s';
      }

      unset($mail);
   }

?>