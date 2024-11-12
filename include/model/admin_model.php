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
* 全商品の商品名データ取得
* 
* @param PDO $pdo
* @return array
*/
function get_product_list($pdo){
    $sql = "SELECT ec_product.*, ec_image.image_path, ec_stock.stock_qty FROM ec_product 
            JOIN ec_image ON ec_product.image_id = ec_image.image_id 
            JOIN ec_stock ON ec_product.product_id = ec_stock.product_id;
            ORDER BY product_id DESC";
    return get_sql_result($pdo, $sql);
}

/**
* htmlspecialchars（特殊文字の変換）のラッパー関数
*
* @param string 
* @return string 
*/
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
* 特殊文字の変換（二次元配列対応）
* 
* @param array
* @return array 
*/
function h_array($array){
    foreach ($array as &$value) {
        if (is_array($value)) {
            $value = h_array($value);
        } else {
            $value = h($value);
        }
    }
    unset($value); // 参照を解除する
    return $array;
}

/**
* 商品を追加する
*
* @param array $params 登録する商品のデータを配列形式で引数に渡す
* @return bool $result1 商品画像を登録するsqlの結果の真偽値を返す
* @return bool $result2 商品情報を登録するsqlの結果の真偽値を返す
* @return bool $result3 商品在庫を登録するsqlの結果の真偽値を返す
*/
function create_product($params) {
    $pdo = get_connection();

    //引数の値を変数に格納
    $product_name = $params['product_name'];
    $category_id = $params['category_id'];
    $price = $params['price'];
    $image_path = 'sample_images/'.$params['image_path'];
    $public_flg = $params['public_flg'];
    $stock_qty = $params['stock_qty'];

    //商品画像の登録
    $sql = "INSERT INTO ec_image(image_name, image_path, public_flg, create_date, update_date)
            VALUES (:image_name, :image_path, :public_flg, CURRENT_DATE, CURRENT_DATE);";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':image_name', $product_name);
    $stmt -> bindValue(':image_path', $image_path);
    $stmt -> bindValue(':public_flg', $public_flg);
    $result = $stmt -> execute();

    //登録した画像のidを取得
    $image_id = $pdo -> lastInsertId();

    //商品情報の登録
    $sql2 = "INSERT INTO ec_product (product_name, category_id, price, image_id, public_flg, create_date, update_date) 
            VALUES (:product_name, :category_id, :price, :image_id, :public_flg, CURRENT_DATE, CURRENT_DATE);";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2 -> bindValue(':product_name', $product_name);
    $stmt2 -> bindValue(':category_id', $category_id);
    $stmt2 -> bindValue(':price', $price);
    $stmt2 -> bindValue(':image_id', $image_id);
    $stmt2 -> bindValue(':public_flg', $public_flg);
    $result2 = $stmt2 -> execute();

    // 登録した商品のidを取得
    $product_id = $pdo -> lastInsertId();

    //在庫情報の登録
    $sql3 = "INSERT INTO ec_stock (product_id, stock_qty, create_date, update_date)
            VALUES(:product_id, :stock_qty, CURRENT_DATE, CURRENT_DATE);";
    $stmt3 = $pdo -> prepare($sql3);
    $stmt3 -> bindValue(':product_id', $product_id);
    $stmt3 -> bindValue(':stock_qty', $stock_qty);
    $result3 = $stmt3 -> execute();

    return array($result, $result2, $result3);
}

/**
 * 商品の在庫数を変更する
 * @param int $stock 変更後の在庫数
 * @param int $id 在庫を変更したい商品の商品ID
 * @return bool 在庫を変更するsqlの結果の真偽値を返す
 */
function update_stock_qty($stock, $id) {
    $pdo = get_connection();
    $sql = "UPDATE ec_stock SET stock_qty = :stock_qty WHERE product_id = :product_id;";

    $stmt = $pdo->prepare($sql);
    $stmt -> bindValue(':stock_qty', $stock);
    $stmt -> bindValue(':product_id', $id);
    return $stmt -> execute();
}

/**
*商品の公開フラグを更新する
*@param int $id フラグを変更したい商品の商品ID
*@param int $flg 変更後のフラグ
*@param string $update_date
*@return bool フラグを変更するsqlの結果の真偽値を返す
*/

function update_public_flg($id, $flg, $update_date) {
    $pdo = get_connection();

    $sql = "UPDATE ec_product SET public_flg = :flg, update_date = ".$update_date." WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindValue(':product_id', $id);
    //商品のフラグが"公開"の場合、"非公開"にする
    if ($flg == 0) {
        $stmt -> bindValue(':flg', 1);
    //商品のフラグが"非公開"の場合、"公開"にする
    } else {
        $stmt -> bindValue(':flg', 0);
    }

    return $stmt->execute();

}

/**
 * 商品のデータを削除する
 * @param int $product_id 削除したい商品の商品ID
 * @return bool 削除するsqlの結果の真偽値を返す
 */

function delete_product($product_id) {
    $pdo = get_connection();
    $sql = "DELETE ec_product, ec_stock, ec_image 
            FROM ec_product 
            JOIN ec_stock ON ec_product.product_id = ec_stock.product_id 
            JOIN ec_image ON ec_product.image_id = ec_image.image_id 
            WHERE ec_product.product_id = :product_id;";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(':product_id', $product_id);

    return $stmt -> execute();
}
