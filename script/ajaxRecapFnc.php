<?php
require_once("DBConnect.php");

$contentTable ="";
$contentTable .="
   <table class='hover even odd' id='tabNC' name='tabNC' cellspacing='1' cellpadding='1' border='1'>
   <thead>
      <tr>
         <th colspan='8'><b>FNC</b></th>
         <th colspan='3'><b>Analyse</b></th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th colspan='5'>Actions</th>
         <th colspan='5'></th>
      </tr>
      <tr>
         <th><b>Client</b></th>
         <th><b>BU</b></th>
         <th><b>Code</b></th>
         <th><b>Statut</b></th>
         <th><b>Mois</b></th>
         <th><b>Date</b></th>
         <th><b>Type</b></th>
         <th><b>Motif cr&eacuteation</b></th>
         <th><b>Motif d'apparition</b></th>
         <th><b>Motif de non d&eacutetection</b></th>
         <th><b>Motif de l'organisation</b></th>
         <th><b>Typologie</b></th>
         <th><b>Imputation</b></th>
         <th><b>Criticit&eacute</b></th>
         <th><b>Faille identifi&eacute</b></th>
         <th><b>Impact</b></th>
         <th><b>Description</b></th>
         <th><b>Responsable</b></th>
         <th><b>Date de d&eacutebut</b></th>
         <th><b>Date de fin</b></th>
         <th><b>Date de suivi</b></th>
         <th><b>Indicateur d'&eacutefficacit&eacute</b></th>
         <th><b>Objectif et &eacutech&eacuteance</b></th>
         <th><b>Validation action</b></th>
         <th><b>Commentaire</b></th>
         <th><b>G&eacuten&eacuteralisation</b></th>
      </tr>
   </thead>
   
";
echo $contentTable;
?>