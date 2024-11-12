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
    $sql = "SELECT ec_cart.cart_id, ec_product.product_id, ec_product.product_name, 
                    ec_image.image_path, ec_product.price, ec_stock.stock_qty, 
                    ec_cart.user_id, ec_cart.product_qty FROM ec_product 
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
 * 注文確定時の処理(在庫数の変更1)
 * @param PDO $pdo
 * @param int $product_qty カート内の商品の個数
 * @param int $stock_qty 商品の在庫数
 * @param int $id 在庫数を変更する商品の商品ID
 * @return bool 在庫数変更のSQLの結果の真偽値を返す
 */

function reduce_stock($pdo, $product_qty, $stock_qty, $id) {
    if ($product_qty <= $stock_qty) {
        $sql = "UPDATE ec_stock SET stock_qty = stock_qty - :qty
                WHERE product_id = :id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindValue(':qty', $product_qty);
        $stmt -> bindValue(':id', $id);
        $result = $stmt -> execute();
        if ($result) {
            return true;
        } else {
            // SQL実行エラーの場合のエラー処理
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 注文確定時の処理(在庫数の変更2)
 * @param PDO $pdo
 * @param array $cart_items カート内全商品を配列で取得
 * @return bool reduce_stock()関数にエラーがなかった場合、trueを返す
 * @return array $err_items reduce_stock()にエラーがあった場合、エラーがあった商品の名前を配列として返す
 */

function reduce_all($cart_items, $pdo) {

    try {
        $pdo->beginTransaction();
        $err_items = array();
        foreach($cart_items as $item) {
            $result = reduce_stock($pdo, $item['product_qty'], $item['stock_qty'], $item['product_id']);
            if(!$result) {
                $err_items[] = $item['product_name'];
            }
        }
        if(empty($err_items)) {
            $pdo->commit();
            return true;
        } else {
            $pdo->rollBack();
            return $err_items;
        }

    } catch (PDOException $e) {
        $pdo->rollBack();
    }
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