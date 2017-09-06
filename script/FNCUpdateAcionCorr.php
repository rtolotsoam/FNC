<?php
require_once "DBConnect.php";

$etat     = $_REQUEST['etat'];
$idfnc    = $_REQUEST['idfnc'];
$id_infos = $_REQUEST['id_inf'];
$matricule = $_REQUEST['matricule'];

if (isset($_REQUEST['etat'])) {
    
    $sql = "update nc_fnc_infos SET etat = '" . $etat . "' where id =  '" . $id_infos . "'";
    $queryS   = @pg_query($sql);

    if($queryS){
        $etat            = 1;
        addLog("Update : \t etats_realisation_actions ==> ".$etat."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
    }else{
        $etat            = 0;
        addLog("Erreur connexion : \t base de donne indisponible, \t Update : \tetats_realisation_actions ==> ".$etat."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
    }


}else{

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
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t commenatire ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $content_comment)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat = 1;
                addLog("Update : \t commentaire ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $content_comment)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);   
            }

            break;

        case 'date_fin':
            $date_fin = $_REQUEST['data'];
            $sql      = "update nc_fnc_infos SET date_fin = '" . $date_fin . "' where id =" . $id_infos;
            $query    = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t date_fin ==> ".$date_fin."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat = 1;
                addLog("Update : \t datet_fin ==> ".$date_fin."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);    
            }

            break;

        case 'date_suivi':
            $date_suivi = $_REQUEST['data'];
            $sql        = "update nc_fnc_infos SET date_suivi = '" . $date_suivi . "' where id =" . $id_infos;
            $query      = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t date_suivi ==> ".$date_suivi."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat       = 1;
                addLog("Update : \t datet_suivi ==> ".$date_suivi."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);    
            }
            break;

        case 'update_gen':
            $gen   = utf8_decode($_REQUEST['data']);
            $sql   = "update nc_fnc_infos SET generalisation = '" . pg_escape_string($gen) . "' where id =" . $id_infos;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t generalisation ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $gen)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat  = 1;
                addLog("Update : \t generalisation ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $gen)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule); 
            }
            break;

        case 'valid_action':
            $valid = $_REQUEST['data'];
            $sql   = "update nc_fnc_infos SET valid_action = '" . $valid . "' where id =" . $id_infos;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t validation_action ==> ".$valid."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat  = 1; 
                addLog("Update : \t validation_action ==> ".$valid."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);    
            }
            break;

        case 'taux_avcmnt':

            $tx_avcmnt_span = $_REQUEST['tx_avcmnt_span'];

            $sql   = "update nc_fnc_infos SET tx_avacmnt = " . $tx_avcmnt_span . " where id =" . $id_infos;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;
                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t taux_avancement ==> ".$tx_avcmnt_span."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);
            }else{
                $etat  = 1;
                addLog("Update : \t taux_avancement ==> ".$tx_avcmnt_span."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);        
            }
            break;

        case 'faille':

            $faille = utf8_decode($_REQUEST['data']);

            $sql = "
                        UPDATE
                            NC_FNC_INFOS
                        SET FAILLE_IDENTIFIEE   =   '" . pg_escape_string($faille) . "'
                        WHERE
                            ID  =   " . $id_infos
            ;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;

                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t faille_identifie ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $faille)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);

            }else{
                $etat  = 1;

                addLog("Update : \t faille_identifiee ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $faille)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);  
            }
            break;

        case 'impact':

            $impact = utf8_decode($_REQUEST['data']);

            $sql = "
                        UPDATE
                            NC_FNC_INFOS
                        SET IMPACT   =   '" . pg_escape_string($impact) . "'
                        WHERE
                            ID  =   " . $id_infos
            ;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;

                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t impact ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $impact)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);

            }else{
                $etat  = 1;    

                addLog("Update : \t impact ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $impact)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule); 
            }
            break;

        case 'description':

            $description = utf8_decode($_REQUEST['data']);

            $sql = "
                    UPDATE
                        NC_ACTION_LISTE
                    SET LIBELLE =   '" . pg_escape_string($description) . "'
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
            
            if (!$query) {
                $etat = 0;

                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t description ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $description)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);

            }else{
                $etat  = 1;

                addLog("Update : \t description ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $description)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule); 
            }
            break;

        case 'efficacite':

            $efficacite = utf8_decode($_REQUEST['data']);

            $sql = "
                        UPDATE
                            NC_FNC_INFOS
                        SET INDIC_EFFICACITE   =   '" . pg_escape_string($efficacite) . "'
                        WHERE
                            ID  =   " . $id_infos
            ;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;

                addLog("Erreur connexion : \t base de donne indisponible, \t Update : \t indicateur_efficacite ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $efficacite)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);

            }else{
                $etat  = 1; 

                addLog("Update : \t indicateur_efficacite ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $efficacite)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule); 

            }
            break;

        case 'objectif':

            $obj = utf8_decode($_REQUEST['data']);

            $sql = "
                        UPDATE
                            NC_FNC_INFOS
                        SET OBJ_ECHEANCE   =   '" . pg_escape_string($obj) . "'
                        WHERE
                            ID  =   " . $id_infos
            ;
            $query = @pg_query($sql);
            
            if (!$query) {
                $etat = 0;

                addLog("Erreur connexion ; \t base de donne indisponible ; \t Update :  objectif_echeance ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $obj)."\t; id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);

            }else{
                $etat  = 1; 

                addLog("Update : \t objectif_echeance ==> ".preg_replace(array("#\\n#", "#\\t#"), " ", $obj)."\t, id_info : ".$id_infos."\t, id_fnc : ".$idfnc." \t, matricule : ".$matricule);    
            }
            break;
    }

}

echo $etat;
