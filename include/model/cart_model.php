<?php

/**
* SQL文を実行・結果を配列で取得する
*
* @param PDO $pdo
* @param string $sql 実行されるSQL文章
* @return array 結果セットの配列
*/
function get_sql_result($pdo, $sql){
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ユーザーIDの取得
 * @param PDO $pdo
 * @param string $name 利用中のユーザー名
 * @return int $user_id 利用中のユーザーのユーザーID
 */
function get_user_id($pdo,$name) {
    $sql = "SELECT user_id FROM ec_user
            WHERE user_name = :user_name;";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':user_name', $name);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $row['user_id'];
    return $user_id;
}

/**
* カート内の商品データ取得
* 
* @param PDO $pdo
* @param int $id ユーザーID
* @return array 結果セットの配列
*/
function get_cart_data($pdo, $id){
    $sql = "SELECT ec_cart.cart_id, ec_product.product_name, ec_image.image_path, ec_product.price, ec_stock.stock_qty, ec_cart.user_id, ec_cart.product_qty FROM ec_product 
            JOIN ec_stock ON ec_product.product_id = ec_stock.product_id
            JOIN ec_cart ON ec_product.product_id = ec_cart.product_id
            JOIN ec_image ON ec_product.image_id = ec_image.image_id
            WHERE ec_cart.user_id = :id;";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue(':id', $id);
            $stmt -> execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * カート内の商品の個数を変更
 * 
 * @param PDO $pdo
 * @param int $qty 変更後の個数
 * @param int $id 変更したい商品のカートID
 * @return bool 個数変更のSQLの結果の真偽値を返す
 */
function update_qty($pdo, $qty, $id) {
    $sql = "UPDATE ec_cart SET product_qty = :qty
            WHERE cart_id = :id";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':qty', $qty);
    $stmt -> bindValue(':id', $id);
    return $stmt -> execute();
}

/**
 * カート内商品の削除
 * @param PDO $pdo
 * @param int $id 削除したい商品のカートID
 * @return bool 商品削除のSQLの結果の真偽値を返す
 */

function delete_item($pdo, $id) {
    $sql = "DELETE FROM ec_cart WHERE cart_id = :id";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':id', $id);
    return $stmt -> execute();
}

/**
 * 注文確定時の処理(カート内商品の削除)
 * @param PDO $pdo
 * @param int $id 削除するカート内商品のユーザーID
 * @return bool カート内商品削除のSQLの結果の真偽値を返す
 */
function delete_cart($pdo, $id) {
    $sql = "DELETE FROM ec_cart WHERE user_id = :id";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue('id', $id);
    return $stmt -> execute();
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