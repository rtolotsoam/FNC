	<?php

	/**
	 * @author ralainahitra angelo
	 * @copyright 2010
	 * @derniere modification : 2010-05-12 par ralainahitra angelo
	 */

	require_once ("DBConnect.php") ;
	
   if(isset($_REQUEST['txtId']))
	  $idF = $_REQUEST['txtId'] ;

	if(isset($_REQUEST['txtRef']))
	  $f_Ref = $_REQUEST['txtRef'] ;
	
	
?>

<html>

   <head>
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   
   	<title>Logigramme d&eacute;cisionnel</title>
   
   	<link rel="stylesheet" type="text/css" href="../css/FNCConsulter.css" />
   	<link type="text/css" href="../css/ui.all.css" rel="stylesheet" />
   
   	<script type="text/javascript" src="../js/FNCSearch.js"></script>
   	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
   	<script type="text/javascript" src="../js/ui.core.js"></script>
   	<script type="text/javascript" src="../js/ui.draggable.js"></script>
   	<script type="text/javascript" src="../js/ui.dialog.js"></script>
   	<style type="text/css">
   	body, table
   	{
   		font-family: Verdana ;
   		font-size: 11px ;
   	}
   
   	table.tab-base td
   	{
   		border: 1px #323232 solid ;
   		font-family: Verdana ;
   		font-size: 12px ;
   	}
   	</style>
   </head>

   <body>
   
      <form id="frmApercuImage" name="frmApercuImage" action="" method="POST">
         <fieldset>
         	<legend>Logigramme d&eacute;cisionnel</legend>
            <table width="100%" cellspacing="1" cellpadding="1" class="tab-base">
               <tr>
                  <td class="class4" align="center">
                  <img src='../img/logigramme2.JPG'>
                  </td>
               </tr>
               
               <tr>
                  <td class="class4">
                     <input type="button" name="btnRetour" id="btnRetour" value="Retour" onclick="javascript: document.location.href='FNCConsulter.php?txtId=<?php echo $idF ; ?>&txtRef=<?php echo $f_Ref ; ?>'" class = "ui-state-default">
                  </td>
               </tr>
            </table>
         </fieldset>
      </form>
   </body>
</html>
