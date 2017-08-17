<?php
	
	session_start();
require 'lib_mail/class.phpmailer.php';
include("/var/www.cache/dgconn.inc");
  
function get_info_sup_tlc($matricule_tlc)
{
	global $conn;
	//requête pour recuperer le N+1 du TLC
	$sql_sup = " SELECT p.matricule,p.prenompersonnel,p.emailpers FROM gpao_hierarchie gh INNER JOIN personnel p ON gh.matricule_parent=p.matricule WHERE gh.matricule in (".$matricule_tlc.") and p.actifpers = 'Active'";
	$query_sup = pg_query($conn,$sql_sup) or die (pg_last_error());
	/*$rws_sup = pg_fetch_array( $query_sup );

	//prenom N+1, mail N+1 , prenom  ACC, mail ACC
	return $rws_sup[0].'#'.$rws_sup[1].'#'.$rws_acc[0].'#'.$rws_acc[1];*/
	return $query_sup;
}
function get_info_acc_tlc($matricule_acc)
{
	global $conn;
	$sql_acc = "SELECT prenompersonnel,emailpers FROM personnel WHERE  matricule={$matricule_acc}";
	$query_acc = pg_query($conn,$sql_acc) or die (pg_last_error());
	$rws_acc = pg_fetch_row( $query_acc );
	//prenom N+1, mail N+1 , prenom  ACC, mail ACC
	return $rws_acc[0].'#'.$rws_acc[1];
}
	 

/*$matricule_tlc = $_REQUEST['matricule_tlc'];
$id_client = $_REQUEST['id_client'];
$id_prestation = $_REQUEST['id_prestation'];

$type_traitement = $_REQUEST['type_traitement'];
$id_tlc = utf8_decode( $_REQUEST['id_tlc'] );
$id_fichier = $_REQUEST['id_fichier'];
$date_traitement = $_REQUEST['date_traitement'];
$date_evaluation = $_REQUEST['date_evaluation'];
$categorie_si = $_REQUEST['categorie_si'];
$description_ecart = utf8_decode($_REQUEST['description_ecart']);
$exigence_client = utf8_decode($_REQUEST['exigence_client']);
$idclient = $_REQUEST['idclient'];*/

$matricule_tlc = 9092;
$id_client = 'NACRE SOFTWARE';
$id_prestation = 'NCE';

$type_traitement = 'Appel entrant';
$id_tlc = 'Nj';
$id_fichier = 'test notation';
$date_traitement = '12/02/2015';
$date_evaluation = '12/02/2015';
$categorie_si = 'Conclusion';
$description_ecart = 'ceci est un test SI';
$exigence_client = 'Ceci est un test SI';
$idclient = '919';

list($jr_fnc,$mois_fnc,$annee_fnc) = explode("/",$date_evaluation );
$date_fnc = $annee_fnc.'-'.$mois_fnc.'-'.$jr_fnc;
$matricule_acc = $_SESSION['matricule'];
$info_acc = get_info_acc_tlc($matricule_acc);
list($prenom_acc,$mail_acc) = explode("#",$info_acc);
//$mail_sup = 'njivaniaina@vivetic.mg';
//$mail_cc = 'tsilavina.si@vivetic.mg';
//$prenom_sup = 'Njivaniaina';
$mail = new PHPmailer();
$mail->IsHTML(true);
$mail->From     = $mail_acc; // votre adresse
//$mail->FromName = 'NF345'; // votre nom
$mail->FromName = $prenom_acc; // votre nom
$mail->Subject  = utf8_decode('NF 345 - Fiche de non conformité'); // sujet de votre message

$id_tlc_fnc = str_replace(" ","_",$id_tlc )."_".$matricule_tlc;

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
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Une situation inacceptable, suivant les exigences de notre r&eacute;f&eacute;rentiel d'&eacute;valuation 
a &eacute;t&eacute; constat&eacute;e sur la communication r&eacute;f&eacute;renc&eacute;e ci-dessous.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Une fiche de non-conformit&eacute; interne a &eacute;t&eacute; ouverte automatiquement.</p>
<br />
<!--<table  cellspacing='0' cellpadding='0' border='0' >-->
<table>

<tr>
<td class='table_style_left'>Nom du client:</td><td class='table_style'>&nbsp;".$id_client."</td>
</tr>

<tr>
<td class='table_style_left'>Prestation:</td><td class='table_style'>&nbsp;".$id_prestation."</td>
</tr>

<tr>
<td class='table_style_left'>Type de traitement:</td><td class='table_style'>&nbsp;".$type_traitement."</td>
</tr>

<tr>
<td class='table_style_left'>CC:</td><td class='table_style'>&nbsp;".$id_tlc." - ".$matricule_tlc."</td>
</tr>

<tr>
<td class='table_style_left'>Fichier:</td><td class='table_style'>&nbsp;".$id_fichier."</td>
</tr>

<tr>
<td class='table_style_left'>Date de traitement:</td><td class='table_style'>&nbsp;".$date_traitement."</td>
</tr>

<tr>
<td class='table_style_left'>Date &eacute;valuation:</td><td class='table_style'>&nbsp;".$date_evaluation."</td>
</tr>

<tr>
<td class='table_style_left'>Cat&eacute;gorie SI:</td><td class='table_style'>&nbsp;".$categorie_si."</td>
</tr>

<tr height='90px;' >
<td class='table_style_left'>Description de l'&eacute;cart:</td><td class='table_style' valign='top' >&nbsp;".$description_ecart."</td>
</tr>

<tr height='90px;'>
<td class='table_style_left'>Exigence client / r&eacute;f&eacute;rentiel:</td><td class='table_style' valign='top'>&nbsp;".$exigence_client."</td>
</tr>


</table>
<br />

<p><b>Comme l'exige notre SMQ, cette situation inacceptable doit &ecirc;tre d&eacute;brieff&eacute;e par le manager avec le conseiller.</b></p>

<p><b>Une fois le d&eacute;brief du conseiller effectu&eacute;, veuillez cliquer sur le lien ci-dessous pour l'enregistrement des &eacute;l&eacute;ments <b></p>
<p><b>d'analyses de l'occurrence et des &eacute;ventuelles actions curatives que vous avez d&eacute;finies.</b></p>

<a class='link' href='http://192.168.10.24/intranet_light/modules/fnc/script/FNCSearch.php?slctClientName=".$idclient."&txtCrationDate=".$date_fnc."&txtModifDate=".$date_fnc."&motif_fnc=".$id_tlc_fnc."&btnSubmit=Rechercher&search=1'>lien vers la gpao sur la FNC cr&eacute;&eacute;e via la grille </a>
</div>";
$footer_mail = "<br /><div style='padding-bottom: 15px;'>Cordialement,<br/> <span class='link' >".$prenom_acc." </span><br/> 
<!--<img src='lib_mail/img/logo_vivetic_mail.png' alt='VIVETIC' />-->
<img src='cid:logo_vvt' />
</div>";

$info_sup = get_info_sup_tlc($matricule_tlc);
$parent_sup = "";
$nb_sup = pg_num_rows($info_sup);
$i = 0;
while($res_sup = pg_fetch_array($info_sup))	
{
	$parent_sup .= $res_sup['matricule'];
	if($i < ($nb_sup -1))
	{
		$parent_sup .= ',';
		$i++;
	}
}
$info_sup = get_info_sup_tlc($matricule_tlc);
while($res_sup = pg_fetch_array($info_sup))	
{
	$mail->AddAddress($res_sup['emailpers'], $res_sup['prenompersonnel']); 	// adresse du destinataire
}

$info_parent_sup = get_info_sup_tlc($parent_sup);
while($res_sup = pg_fetch_array($info_parent_sup))	
{
	$mail->AddCC($res_sup['emailpers'], $res_sup['prenompersonnel']); 	// adresse du destinataire
}
//$mail->AddAddress('njivaniaina@vivetic.mg', 'Njivaniaina'); 	// adresse du destinataire
//$mail->AddCC($mail_acc, $prenom_acc); 	// adresse en copie										
//$mail->AddCC('tsilavina.si@vivetic.mg', 'Tsilavina'); 	// adresse en copie
$mail->AddCC('njivaniaina@vivetic.mg', 'Njivaniaina'); 	// adresse en copie
//$mail->AddCC('ando.randriamananjo@vivetic.mg', 'Ando'); 	// adresse en copie
//$mail->AddCC('jerome.tavola@vivetic.mg', 'Tavola'); 	// adresse en copie

$mail->Body=$header_mail.$message.$footer_mail;
$mail->AddEmbeddedImage('lib_mail/img/logo_vivetic_mail.png', 'logo_vvt');
//ra bdb n le fichier o attachena d asina boucle eto
//debut boucle
$directory = "reporting/";
$target_path = $directory .basename('reporting_2014_08_12__2014_08_25_.xls');

if(file_exists($target_path)) 
	$mail->AddAttachment($target_path); 
else 
    echo $target_path.' inexistant';  

///in boucle ra mis fichier bdb o attachena 
    
if(!$mail->Send()){ // on teste la fonction Send() -> envoyer 
  echo $mail->ErrorInfo; //Affiche le message d'erreur 
}
else{      
  // echo 'Mail envoy&eacute; avec succ&egrave;s';
}

unset($mail);


