<?php

//                      CONNEXION A LA BASE DE DONNEES ET INCLUDE FILES
// ******************************************************************************************
include ("/var/www.cache/dgconn.inc") ;
// ******************************************************************************************

session_start () ;

$_SESSION["iCol"]		= $_REQUEST["iCol"] ;
$_SESSION["iSortOrder"] = $_REQUEST["iSortOrder"] ; ;

// print $_SESSION["iCol"] . " - " . $_SESSION["iSortOrder"] ;

?>