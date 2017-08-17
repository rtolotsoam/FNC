<?php
	session_start();
	
	$_SESSION['h_fnc_nmclient'] = $_REQUEST["nmclient"];
	$_SESSION['h_fnc_client'] = $_REQUEST["client"];
	$_SESSION['h_fnc_dtc'] = $_REQUEST["dtc"];
	$_SESSION['h_fnc_dtm'] = $_REQUEST["dtm"];
	$_SESSION['h_fnc_code'] = $_REQUEST["code"];
	$_SESSION['h_fnc_creat'] = $_REQUEST["creat"];
	$_SESSION['h_fnc_cp'] = $_REQUEST["cp"];
	$_SESSION['h_fnc_statcur'] = $_REQUEST["statcur"];
	$_SESSION['h_fnc_type'] = $_REQUEST["type"];
	$_SESSION['h_fnc_statcor'] = $_REQUEST["statcor"];
	$_SESSION['h_fnc_presence'] = "1";
	$_SESSION['h_fnc_iteration'] = "1";