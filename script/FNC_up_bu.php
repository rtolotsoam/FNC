<?php
require_once "DBConnect.php";

$id_fnc       = $_REQUEST['id_fnc'];
$lib_bu_opt   = $_REQUEST['lib_bu_opt'];
$arr_sql_f_bu = 'NULL';

if ($lib_bu_opt != '') {
    $sql_find_bu       = "SELECT distinct id_bu FROM business_unit where lib_bu ilike '%" . $lib_bu_opt . "%'";
    $query_sql_find_bu = @pg_query($conn, $sql_find_bu);
    $res_f_bu          = @pg_fetch_array($query_sql_find_bu);
    $arr_sql_f_bu      = $res_f_bu['id_bu'];
}

echo $up_bu_fnc = "UPDATE nc_fiche SET fnc_bu =" . $arr_sql_f_bu . " where fnc_id = '" . $id_fnc . "'";
@pg_query($conn, $up_bu_fnc);
