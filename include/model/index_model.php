<?php

/**
* DB接続を行いPDOインスタンスを返す
* 
* @return object $pdo
*/

function get_sql_result($pdo, $sql) {
    $data = [];
    if ($result = $pdo->query($sql)) {
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch()) {
                $data[] = $row;
            }
        }
    }
    return $data;
}

/**
 * ユーザー情報の取得
 * 
 * @param PDO $pdo
 * @param string $user_name ユーザー名
 * @param string $password パスワード
 * @return array 取得したユーザー情報を配列形式で返す
 */

function get_user_list($pdo, $user_name, $password) {
    $sql = 'SELECT * FROM ec_user WHERE user_name = :user_name AND password = :password';
    $stmt = $pdo->prepare($sql);
    $stmt -> bindValue(':user_name', $user_name);
    $stmt -> bindValue(':password', $password);
    $stmt -> execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return h_array($data);
}

/**
* htmlspecialchars（特殊文字の変換）のラッパー関数
*
* @param string 
* @return string 
*/
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
* 特殊文字の変換（二次元配列対応）
* 
* @param array
* @return array 
*/
function h_array($array) {
  //二次元配列をforeachでループさせる
    foreach ($array as $keys => $values) {
        foreach ($values as $key => $value) {
      //ここの値にh関数を使用して置き換える
            $array[$keys][$key] = h($value);
        }
    }
    return $array;
}
