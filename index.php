<?php
session_start();
require_once "script/DBConnect.php";

$matricule_login         = $_SESSION['matricule'];

// echo "<br/> matricule = ";
// var_dump($matricule_login);

$COQUAL                  = findGroup($conn);

$iQualite                = $_GET["qualite"];

/*****************************************************************/
//                                                                //
//  fichier de cofiguration admin FNC, ajouter par mle 9092       //
/****************************************************************/
include "script/FNCUser_admin.php";

?>

<html>
<head>
	<title>FNC</title>
	<link type="text/css" href="css/ui.all.css" rel="stylesheet" />

	<script type="text/javascript" src="js/jquery-1.3.2.js?10"></script>
	<script type="text/javascript" src="js/ui.core.js?10"></script>
	<script type="text/javascript" src="js/ui.tabs.js?10"></script>

	<script type="text/javascript">
	$(function() {
		$("#tabs").tabs({
			event: 'click'
		});
	});
	function ret(){
		$("#FNCS iframe").removeAttr("src");
		$("#FNCS iframe").attr({
			src: "script/FNCSearch.php?10"
		});
	}
	function retAC(){
		$("#FNCSAC iframe").removeAttr("src");
		$("#FNCSAC iframe").attr({
			src: "script/FNCSuiviActionC.php?10"
		});
	}
	function retANC(){
		$("#FNCSANC iframe").removeAttr("src");
		$("#FNCSANC iframe").attr({
			src: "script/FNCSuiviActionNC.php?10"
		});
	}
	function retRECAP(){
		$("#FNCR iframe").removeAttr("src");
		$("#FNCR iframe").attr({
			src: "script/FNCRecap.php?qualite=<?php echo $iQualite; ?>"
		});
	}
	function retINDIC(){
		$("#FNCINDIC iframe").removeAttr("src");
		$("#FNCINDIC iframe").attr({
			src: "script/FNCIndic.php"
		});
	}
	function retAdmin()
	{
		$("#FNCAdmin iframe").removeAttr("src");
		$("#FNCAdmin iframe").attr({
			src: "script/FNCAdmin.php?10"
		});
	}
	function retAFANC()
	{
		$("#FNCAFANC iframe").removeAttr("src");
		$("#FNCAFANC iframe").attr({
			src: "script/FNCAdminActionCorrective.php?10"
		});
	}

	</script>
</head>
<body  style="overflow: hidden">

<?php
unset($_SESSION["MSGAjout"]);
unset($_SESSION["MSGSearch"]);
?>

<!-- div class="demo" -->
<div id="tabs" style="height: 100%; width: 100%; overflow: hidden;">
	<ul>
			<li><a href="#FNCA">Ouvrir une FNC</a></li>
			<li><a href="#FNCS" onclick="ret(); return false;">Rechercher une FNC</a></li>
			<li><a href="#FNCSAC" onclick="retAC(); return false;">Suivi des actions curatives</a></li>
			<li><a href="#FNCSANC" onclick="retANC(); return false;">Suivi des actions correctives</a></li>
			<li><a href="#FNCR" onclick="retRECAP(); return false;">R&eacute;capitulatif</a></li>


		<?php

if (in_array($matricule_login, $array_admin_CQ)) {
    ?>
	        	<li><a href="#FNCAFANC" onclick="retAFANC();">Admin actions correctives</a></li>

		<?php
}
?>
	</ul>

		<div id="FNCA" class="content">
			<iframe src="script/FNCAjout.php" frameborder="no" scrolling="yes"></iframe>
		</div>
		<div id="FNCS" class="content">
			<iframe src="" frameborder="no" scrolling="no"></iframe>
		</div>
		<div id="FNCSAC" class="content">
			<iframe src="" frameborder="no" scrolling="yes"></iframe>
		</div>
		<div id="FNCSANC" class="content">
			<iframe src="" frameborder="no" scrolling="no"></iframe>
		</div>

		<div id="FNCR" class="content">
			<iframe src="" frameborder="no" scrolling="yes"></iframe>
		</div>

  <!-- Demande manitra ChQ reactivation -->
	<?php

if (in_array($matricule_login, $array_admin_CQ)) {
    ?>
			<div id="FNCAFANC" class="content">
				<iframe src="" frameborder="no" scrolling="yes"></iframe>
			</div>
	<?php
}
?>
</div>
</body>
</html>

<?php
//----------------------------------- save acces module ---------------------------------
include "../access_modules_count/enrg_acces_module.php";
data_acces("Fiches de non conformité", "GPAO", $_SERVER['PHP_SELF']);
//----------------------------------------------------------------------------
?>