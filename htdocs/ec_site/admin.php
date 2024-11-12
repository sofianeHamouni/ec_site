<?php

session_start();
//定数を読み込む
require_once '../../include/config/const.php';

//admin.model.phpを読み込む
require_once '../../include/model/admin_model.php';

//ログイン中のユーザーが管理者であるか確認する
if ($_SESSION['user_id'] !== 'ec_admin') {
    //管理者でない場合は、product.phpにリダイレクトする
    header('Location: product.php');
    exit();
}

$error_msg = [];
$row = [];

$pdo = get_connection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    if (isset($_POST['submit'])) {
        $insert = $_POST['submit'];
        $image_path = basename($_FILES['upload_image']['name']);
        if ($_POST['product_name'] == null) {
            $error_msg[] = "商品名がありません。";
        } elseif ($_POST['price'] == null) {
            $error_msg[] = "商品価格を入力してください。";
        } elseif ($_POST['price'] < 0) {
            $error_msg[] = "価格には正の値を入力してください。マイナス値は受け付けられません。";
        } elseif (!preg_match("/^[0-9]+$/",$_POST['price'])) {
            $error_msg[] = "金額は半角数字で入力してください。";
        } elseif ($_POST['new_stock'] == null) {
            $error_msg[] = "在庫数を入力してください。";
        } elseif ($_POST['new_stock'] < 0) {
            $error_msg[] = "在庫数には正の値を入力してください。マイナス値は受け付けられません。";
        } elseif (!preg_match("/^[0-9]+$/",$_POST['new_stock'])) {
            $error_msg[] = "在庫数は半角数字で入力してください。";
        } elseif ($_POST['category_id'] == null) {
            $error_msg[] = "カテゴリを選択してください。";
        } elseif ($image_path == null) {
            $error_msg[] = "画像を選択してください。";
        }elseif (preg_match("/^.*(?<!jpeg|jpg|png)$/", $image_path)) {
            $error_msg[] = "登録できる画像形式はjpeg,jpg,pngのみです。";
        } else {
            $params = [
                'product_name' => $_POST['product_name'],
                'category_id' => $_POST['category_id'],
                'price' => $_POST['price'],
                'image_path' => $image_path,
                'public_flg' => $_POST['public_flg'],
                'stock_qty' => $_POST['new_stock'],
                'create_date' => 'CURRENT_DATE',
                'update_date' => 'CURRENT_DATE'
            ];
            if (isset($_POST['new_flg'])) {
                $params['public_flg'] = 1;
            } else {
                $params['public_flg'] = 0;
            }
            $insert = create_product($params);
            if (!$insert) {
                $error_msg[] = 'INSERT実行エラー [実行SQL]' . $insert;
            }
            if (count($error_msg) == 0) {
                if (move_uploaded_file($_FILES['upload_image']['tmp_name'], 'sample_images/'.$image_path)) {
                    $success_msg = '商品を追加しました';
                } else {
                    $error_msg[] = 'アップロードに失敗しました';
                }
            }
        }
    }

    if (isset($_POST['update_stock'])) {
        $update_stock = $_POST['update_stock'];
        $getProduct_id = $_POST['id_value'];
        if (!preg_match('/^[1-9][0-9]*$/', $update_stock)) {
            $error_msg[] = '在庫数には正の整数を入力してください。マイナス値や小数は受け付けられません。';
        } else {
            $update_stock = update_stock_qty($_POST['stock'], $getProduct_id);
            if (!$update_stock) {
                $error_msg[] = 'UPDATE実行エラー [実行SQL]' . $update_stock;
            } else {
                $success_msg =  "在庫数を更新しました";
            }
        }

    }
    
    if(isset($_POST['update_flg'])) {
        $getProduct_id = $_POST['id_value'];
        $getPublic_flg = $_POST['public_flg'];
        $update_flg = update_public_flg($getProduct_id, $getPublic_flg, 'CURRENT_DATE');
        if (!$update_flg) {
            $error_msg[] = 'UPDATE実行エラー [実行SQL]' . $update_flg;
        } else {
            if ($getPublic_flg == 1) {
                $success_msg = '非表示にしました';
            } else {
                $success_msg = '表示にしました';
            }
        }
    }

    if (isset($_POST['delete'])) {
        $getProduct_id = $_POST['id_value'];
        $delete = delete_product($getProduct_id);
        if (!$delete) {
            $error_msg[] = 'UPDATE実行エラー [実行SQL]' . $delete;
        } else {
            $success_msg = 'データを削除しました';
        }
    }
}

//ログイン中のユーザーであるか確認する
if (empty($_SESSION['user_id'])) {
    //ログイン中ではない場合は、index.phpにリダイレクトする
    header('Location: index.php');
    exit();
}

$product_data = [];
$product_data = get_product_list($pdo);
$product_data = h_array($product_data);

include '../../include/view/admin_view.php';