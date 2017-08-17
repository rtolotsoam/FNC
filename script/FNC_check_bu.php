<?php
session_start();
require_once "DBConnect.php";

$id_fnc      = $_REQUEST['id_fnc'];

$sql_find_bu = "
SELECT DISTINCT
    RES_CODE3.FNC_CODE
,   RES_CODE3.CODE3
,   BUSS_U.LIB_BU
FROM
    (
        SELECT
            DISTINCT
            FNC_CODE
        ,   FNC_ID
        ,   CASE
                WHEN
                    CHAR_LENGTH(FNC_CODE)   >   6
                THEN
                    SUBSTR(FNC_CODE, 2, 3)
                ELSE
                    SUBSTR(FNC_CODE, 0, 4)
            END         AS  CODE3
        FROM
            NC_FICHE
        WHERE
            FNC_CODE    !=  ''
        ORDER BY
            CODE3   ASC
    )   AS  RES_CODE3
LEFT JOIN
    GU_APPLICATION  GUA
ON
    GUA.CODE    =   RES_CODE3.CODE3
LEFT JOIN
    BUSINESS_UNIT   BUSS_U
ON
    BUSS_U.ID_BU    =   GUA.ID_BU
WHERE
    FNC_ID  =   '" . $id_fnc . "'
AND RES_CODE3.FNC_CODE  NOT ILIKE   '%0VVT001%' /*Demande Mme 606 semaine 24 2016*/
ORDER BY
    RES_CODE3.CODE3 ASC"
;

$query_sql_find_bu = @pg_query($conn, $sql_find_bu);
$nb_row_bu         = @pg_num_rows($query_sql_find_bu);
$res_f_bu          = @pg_fetch_array($query_sql_find_bu);
$arr_sql_f_bu      = $res_f_bu['lib_bu'];
$fnc_code          = trim($res_f_bu['fnc_code']);
if ($nb_row_bu > 0 && $arr_sql_f_bu != '') {
    echo 0;
} else {
    echo 1;
}
