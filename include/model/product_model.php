<?php

/**
* SQL文を実行・結果を配列で取得する
*
* @param PDO $pdo
* @param string $sql 実行されるSQL文章
* @return array 結果セットを配列形式で返す
*/
function get_sql_result($pdo, $sql){
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ユーザーIDの取得
 * @param PDO $pdo
 * @param string $name ユーザー名
 * @return int 取得したユーザーIDを配列形式で返す
 */
function get_user_id($pdo, $name) {
    $sql = "SELECT user_id FROM ec_user
            WHERE user_name = :user_name;";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':user_name', $name);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['user_id'];
}

/**
* 商品データ取得(公開フラグon)
* 
* @param PDO $pdo
* @param int $id 商品のカテゴリID
* @return array 結果セットを配列形式で返す
*/
function get_product_list($pdo, $id){
    if (empty($id)) {
            //全商品データの取得
        $sql = "SELECT ec_product.*, ec_image.image_path, ec_stock.stock_qty FROM ec_product 
                JOIN ec_image ON ec_product.image_id = ec_image.image_id 
                JOIN ec_stock ON ec_product.product_id = ec_stock.product_id
                WHERE ec_product.public_flg = 1
                ORDER BY product_id ASC";
    } else {
            //各カテゴリの商品データの取得
        $sql = "SELECT ec_product.*, ec_image.image_path, ec_stock.stock_qty FROM ec_product 
                JOIN ec_image ON ec_product.image_id = ec_image.image_id 
                JOIN ec_stock ON ec_product.product_id = ec_stock.product_id
                WHERE ec_product.public_flg = 1 AND ec_product.category_id = ".$id."
                ORDER BY product_id ASC";
    }
    return get_sql_result($pdo, $sql);
}

/**
 * カートに追加する関数
 * @param int $user_id カートに追加するユーザーのユーザーID
 * @param int $product_id カートに追加する商品の商品ID
 * @param int $product_qty カートに追加する商品の個数
 * @return bool sql文が実行された場合の真偽値を返す
 */

function add_cart($pdo, $user_id, $product_id, $product_qty) {
    //特定ユーザーのカート内データを取得
    $sql = "SELECT * FROM ec_cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->execute();
    //カート内データを配列として格納
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);
    //カート内に追加したい商品と同じものがあるか判別
    if ($cart_item) {
        //同じ商品がある場合、カート内商品に追加する個数を加える
        $sql = "UPDATE ec_cart SET product_qty = product_qty + :product_qty 
                WHERE user_id = :user_id AND product_id = :product_id";
    } else {
        //同じ商品がない場合、商品のデータを追加する
        $sql = "INSERT INTO ec_cart(user_id, product_id, product_qty, create_date, update_date) 
                VALUES (:user_id, :product_id, :product_qty, CURRENT_DATE, CURRENT_DATE)";
    }
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':user_id', $user_id);
    $stmt -> bindValue(':product_id', $product_id);
    $stmt -> bindValue(':product_qty', $product_qty);
    return $stmt -> execute();
}

/**
 * ユーザー情報の取得
 * 
 * @param PDO $pdo
 * @return array 結果セットを配列形式で返す
 */
function get_user_list($pdo) {
    $sql = 'SELECT user_name, password FROM ec_user';
    return get_sql_result($pdo,$sql);
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