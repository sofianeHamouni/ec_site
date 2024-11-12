<?php

/**
* SQL文を実行・結果を配列で取得する
*
* @param object $pdo
* @param string $sql 実行されるSQL文章
* @return array 結果セットの配列
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
* 全商品の商品名データ取得
* 
* @param object 
* @return array
*/
function get_user_list($pdo) {
    $sql = 'SELECT * FROM ec_user';
    return get_sql_result($pdo, $sql);
}


/**
 * バインドした値をsqlに挿入し、結果を返す
 * 
 */

function sign_up_user($pdo, $user_id, $password) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //INSERTクエリを作成
    $sql = 'INSERT INTO ec_user(user_name, password, create_date, update_date) 
            VALUES (:user_name, :password, CURRENT_DATE, CURRENT_DATE)';
    //prepareメソッドによるクエリの実行準備をする
    $stmt = $pdo -> prepare($sql);
    //値をバインドする
    $stmt -> bindValue(':user_name', $user_id);
    $stmt -> bindValue(':password', $password);
    try {
        $result = $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    return $result;
}

/**
 * 指定したユーザー名がすでに登録済みかどうかをチェックする
 * @param object $pdo PDOオブジェクト
 * @param string $user_id ユーザー名
 * @return int ユーザー名が登録されている場合は1、そうでない場合は0を返す
 */
function check_user_exist($pdo, $user_id) {
    $sql = "SELECT COUNT(*) AS count FROM ec_user WHERE user_name = :user_name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_name', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

