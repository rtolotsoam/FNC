<?php
require_once "DBConnect.php";

$etat     = $_REQUEST['etat'];
$idfnc    = $_REQUEST['idfnc'];
$id_infos = $_REQUEST['id_inf'];
if (isset($_REQUEST['etat'])) {
    echo $sql = "update nc_fnc_infos SET etat = '" . $etat . "' where id =  '" . $id_infos . "'";
    $queryS   = @pg_query($sql);
}

$type = trim($_REQUEST['comment']);
// $date_fin = ;
// $date_suivi = ;
$etat = 0;

switch ($type) {

    case 'comment_only':
        $content_comment = $_REQUEST['data'];
        $content_comment = pg_escape_string(utf8_decode($_REQUEST['data']));
        $sql             = "update nc_fnc_infos SET commentaire = '" . $content_comment . "' where id =" . $id_infos;
        $query           = @pg_query($sql);
        $etat            = 1;
        if (!$query) {
            $etat = 0;
        }

        break;

    case 'date_fin':
        $date_fin = $_REQUEST['data'];
        $sql      = "update nc_fnc_infos SET date_fin = '" . $date_fin . "' where id =" . $id_infos;
        $query    = @pg_query($sql);
        $etat     = 1;
        if (!$query) {
            $etat = 0;
        }

        break;

    case 'date_suivi':
        $date_suivi = $_REQUEST['data'];
        $sql        = "update nc_fnc_infos SET date_suivi = '" . $date_suivi . "' where id =" . $id_infos;
        $query      = @pg_query($sql);
        $etat       = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'update_gen':
        $gen   = $_REQUEST['data'];
        $sql   = "update nc_fnc_infos SET generalisation = '" . $gen . "' where id =" . $id_infos;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'valid_action':
        $valid = $_REQUEST['data'];
        $sql   = "update nc_fnc_infos SET valid_action = '" . $valid . "' where id =" . $id_infos;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'taux_avcmnt':

        $tx_avcmnt_span = $_REQUEST['tx_avcmnt_span'];

        $sql   = "update nc_fnc_infos SET tx_avacmnt = " . $tx_avcmnt_span . " where id =" . $id_infos;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'faille':

        $faille = $_REQUEST['data'];

        $sql = "
                    UPDATE
                        NC_FNC_INFOS
                    SET FAILLE_IDENTIFIEE   =   '" . pg_escape_string(utf8_decode($faille)) . "'
                    WHERE
                        ID  =   " . $id_infos
        ;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'impact':

        $impact = $_REQUEST['data'];

        $sql = "
                    UPDATE
                        NC_FNC_INFOS
                    SET IMPACT   =   '" . pg_escape_string(utf8_decode($impact)) . "'
                    WHERE
                        ID  =   " . $id_infos
        ;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'description':

        $description = $_REQUEST['data'];

        $sql = "
                UPDATE
                    NC_ACTION_LISTE
                SET LIBELLE =   '" . pg_escape_string(utf8_decode($description)) . "'
                WHERE
                    ID  =   (
                        SELECT
                            ACTION_LISTE_ID
                        FROM
                            NC_FNC_ACTION
                        WHERE
                            FNC_ID      =   '" . $idfnc . "'
                        AND FNC_INFO_ID =   " . $id_infos . "
                    )"
        ;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'efficacite':

        $efficacite = $_REQUEST['data'];

        $sql = "
                    UPDATE
                        NC_FNC_INFOS
                    SET INDIC_EFFICACITE   =   '" . pg_escape_string(utf8_decode($efficacite)) . "'
                    WHERE
                        ID  =   " . $id_infos
        ;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;

    case 'objectif':

        $obj = $_REQUEST['data'];

        $sql = "
                    UPDATE
                        NC_FNC_INFOS
                    SET OBJ_ECHEANCE   =   '" . pg_escape_string(utf8_decode($obj)) . "'
                    WHERE
                        ID  =   " . $id_infos
        ;
        $query = @pg_query($sql);
        $etat  = 1;
        if (!$query) {
            $etat = 0;
        }
        break;
}

echo $etat;
