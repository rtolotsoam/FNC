<?php

/**
 * @author angelo ralainahitra
 * @copyright 2012-01-06
 * changement de l'input texte en menu deroulant suivant type de client
 * enjeu 2012 - amelioration fnc
 */

require_once "../script/DBConnect.php";

$sClass = "";
// not msie
if (strpos($_SERVER['HTTP_USER_AGENT'], '(compatible; MSIE ') == false) {
    $sClass = "slct_grav";
}

$sType        = $_REQUEST['FillType'];
$sClientName  = $_REQUEST['ClientName'];
$sCommandCode = $_REQUEST['CommandCode'];

/**
 * prod sql
 *
$sSqlCmdCode = "SELECT DISTINCT(commande.idcommande) AS idcommande FROM commande ";
if (!empty($sClientName)) $sSqlCmdCode .= ", abl_entreprise_client, abl_operation ";
$sSqlCmdCode .= "WHERE commande.idcommande IS NOT NULL ";
if (!empty($sClientName))
$sSqlCmdCode .= "    AND abl_entreprise_client.id_entreprise = abl_operation.id_entreprise
AND abl_operation.id_client = commande.idclient
AND abl_entreprise_client.nom_entreprise = '{$sClientName}'";
$sSqlCmdCode .= "ORDER BY idcommande ASC";
 */
/**
 * test sql
 */
// $sSqlCmdCode = "SELECT DISTINCT(commande.idcommande) AS idcommande FROM commande ";
// if (!empty($sClientName)) $sSqlCmdCode .= ", client ";
// $sSqlCmdCode .= "WHERE commande.idcommande IS NOT NULL ";
// if (!empty($sClientName)) $sSqlCmdCode .= "AND client.idclient = commande.idclient AND client.nomentreprise = '{$sClientName}' ";
// $sSqlCmdCode .= "ORDER BY idcommande ASC";
/**
 * end test sql
 */

/////////////////////////////////////////

$sSqlCmdCode = "SELECT
                    GUC.ID_CLIENT
                ,   GUC.NOM_CLIENT
                ,   CMD.IDCOMMANDE
                FROM
                    GU_CLIENT   GUC
                INNER JOIN
                    GU_APPLICATION  GUA
                ON
                    GUC.ID_CLIENT   =   GUA.ID_CLIENT
                INNER JOIN
                    COMMANDE    CMD
                ON
                    GUA.CODE    =   CMD.CODE3
                WHERE
                    CMD.IDCOMMANDE  IS  NOT NULL
                AND GUC.ID_CLIENT   =   '{$sClientName}'
                ORDER BY
                    NOM_CLIENT
                ,   IDCOMMANDE"
                ;

/////////////////////////////////////////

$sToPrint = "";
$sToPrint .= "<select id='slctCode' name='slctCode' class='{$sClass}'>";
$sToPrint .= "<option value=''>***** fa&icirc;tes votre choix *****</option>";

$sAudit = "<option value='QUAL' ";
if ($sCommandCode == "QUAL") {
    $sAudit .= "selected";
}

$sAudit .= ">QUAL</option>";

$sNotAudit     = "";
$rQueryCmdCode = @pg_query($conn, $sSqlCmdCode);
$iCmsNb        = @pg_num_rows($rQueryCmdCode);
for ($i = 0; $i < $iCmsNb; $i++) {
    $aData    = @pg_fetch_array($rQueryCmdCode, $i);
    $sCmdCode = $aData['idcommande'];
    $sNotAudit .= "<option value='{$sCmdCode}'";
    if ($sCommandCode == $sCmdCode) {
        $sNotAudit .= " selected";
    }

    $sNotAudit .= ">{$sCmdCode}</option>";
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
    if ($iCmsNb == 0 or empty($sClientName)) {
        $sToPrint .= $sAudit . $sNotAudit;
    } else {
        $sToPrint .= $sNotAudit;
    }

}

$sToPrint .= "</select>";

echo $sToPrint;
