<?php
define('DSN','mysql:host=mysql34.conoha.ne.jp;dbname=bcdhm_hoj_pf0005;');
define('LOGIN_USER','bcdhm_hoj_pf0005');
define('PASSWORD','vL3jte_k');
define('EXPIRATION_PERIOD', 60);
define('BASE_URL','https://portfolio.dc-itex.com/hachioji/0005');

/**
* DB接続を行いPDOインスタンスを返す
* 
* @return object $pdo 
*/
function get_connection() {
    try{
        // PDOインスタンスの生成
    $pdo = new PDO(DSN,LOGIN_USER,PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
return $pdo;
}