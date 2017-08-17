<script type="text/javascript">
        //$(document).ready(function() {


            $("#slctClientName").change(function(){
                var type = $("#slctTypeFNC").val();
                var client = $("#slctClientName").val();

                $("#texte_autre").empty();

                console.log("type = "+type+" , client= "+client);

                if (type == "audit" && client == "Autres"){

                     var inputtxt = "<input type='text' id='autre_cplmt' name='autre_cplmt' class='slct'  label='autre_cplmt' style='width:200px;'/> <br/>";
                     $("#texte_autre").append(inputtxt);

                }else{
                    $("#texte_autre").empty();
                }
            });


        //});
</script>
<?php

/**
 * @author angelo ralainahitra
 * @copyright 2012-01-06
 * changement de l'input texte en menu deroulant
 * enjeu 2012 - amelioration fnc
 */

require_once "../script/DBConnect.php";

$sStyle = "";
// not msie
if (strpos($_SERVER['HTTP_USER_AGENT'], '(compatible; MSIE ') == false) {
    $sClass = "slct";
}

$sToPrint = "";
$sToPrint .= "<select id='slctClientName' name='slctClientName' class='{$sClass}'>";
$sToPrint .= "<option value=''>***** fa&icirc;tes votre choix *****</option>";
if (strpos($_SERVER['HTTP_USER_AGENT'], '(compatible; MSIE ') == false) {
    $sClass = "slct";
}

/**
 * prod sql
 * $rQueryClient = @pg_query ($conn, "SELECT nom_entreprise FROM abl_entreprise_client ORDER BY nom_entreprise ASC");
 */
/**
 * test sql
 */
//$rQueryClient = @pg_query ($conn, "SELECT nomentreprise FROM client WHERE nomentreprise != '' ORDER BY nomentreprise ASC");

////////////////////////////////
$rQueryClient = @pg_query($conn, "select distinct guc.id_client,guc.nom_client
    from gu_client guc
    inner join  gu_application gua on guc.id_client = gua.id_client
    inner join commande cmd on  gua.code = cmd.code3
    where cmd.idcommande is not null
    order by nom_client");
////////////////////////////////

/**
 * end test sql
 */

$sType       = $_REQUEST['FillType'];
$sClientName = $_REQUEST['ClientName'];

$sAudit = " <option value='PROCESSUS ADMINISTRATION'";
if ($sClientName == "PROCESSUS ADMINISTRATION") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS ADMINISTRATION</option><option value='PROCESSUS AMELIORATION'";
if ($sClientName == "PROCESSUS AMELIORATION") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS AMELIORATION</option><option value='PROCESSUS CLIENT'";
if ($sClientName == "PROCESSUS CLIENT") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS CLIENT</option><option value='PROCESSUS MANAGEMENT'";
if ($sClientName == "PROCESSUS MANAGEMENT") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS MANAGEMENT</option><option value='PROCESSUS PRE-PRODUCTION'";
if ($sClientName == "PROCESSUS PRE-PRODUCTION") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS PRE-PRODUCTION</option><option value='PROCESSUS PRODUCTION-CALL'";
if ($sClientName == "PROCESSUS PRODUCTION-CALL") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS PRODUCTION-CALL</option><option value='PROCESSUS PRODUCTION-BATCH'";
if ($sClientName == "PROCESSUS PRODUCTION-BATCH") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS PRODUCTION-BATCH</option><option value='PROCESSUS PRODUCTION-BPO'";
if ($sClientName == "PROCESSUS PRODUCTION-BPO") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS PRODUCTION-BPO</option><option value='PROCESSUS PRODUCTION-COMMUNICATION'";
if ($sClientName == "PROCESSUS PRODUCTION-COMMUNICATION") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS PRODUCTION-COMMUNICATION</option><option value='PROCESSUS RESSOURCES HUMAINES'";
if ($sClientName == "PROCESSUS RESSOURCES HUMAINES") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS RESSOURCES HUMAINES</option><option value='PROCESSUS TECHNIQUE'";
if ($sClientName == "PROCESSUS TECHNIQUE") {
    $sAudit .= " selected";
}

$sAudit .= ">PROCESSUS TECHNIQUE</option><option value='Autres'";
if ($sClientName == "Autres") {
    $sAudit .= " selected";
}

$sAudit .= ">Autres</option>";

$sNotAudit = "";
for ($i = 0; $i < @pg_num_rows($rQueryClient); $i++) {
    $aData    = @pg_fetch_array($rQueryClient, $i);
    $idClient = $aData['id_client'];
    $sClient  = $aData['nom_client'];
    $sNotAudit .= "<option value='{$idClient}'";
    if ($sClientName == $sClient) {
        $sNotAudit .= " selected";
    }

    $sNotAudit .= ">{$sClient}</option>";
}

if ($sType == "add") {
    $sTypeFNC = $_REQUEST['TypeFNC'];
    if (!empty($sTypeFNC)) {
        if ($sTypeFNC == "audit") {
            $sToPrint .= $sAudit;
        } else {
            $sToPrint .= $sNotAudit;
        }

    }
} else {
    $sToPrint .= $sAudit . $sNotAudit;
}
$sToPrint .= "</select>";

echo $sToPrint;

?>