<?php

//セッション開始
session_start();

//定数を読み込む
require_once '../../include/config/const.php';

//product_model.phpを読み込む
require_once '../../include/model/product_model.php';


$product_list = [];
$pdo = get_connection();
$product_list = get_product_list($pdo, $id);
$product_list = h_array($product_list);


//Cookieの保存期間
$cookie_expiration = time() + EXPIRATION_PERIOD * 60 * 24;

//POSTされたフォームの値を変数に格納する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cookie_confirmation'])) {
        $cookie_confirmation = $_POST['cookie_confirmation'];
    } else {
        $cookie_confirmation = '';
    }
    if (isset($_POST["user_id"]) && preg_match('/^[a-zA-Z0-9]+$/',$_POST['user_id'])) {
        $user_name = $_POST["user_id"];
        $_SESSION['user_id'] = $user_name;
        
    } else {
        $user_name = '';
        $_SESSION['err_flg'] = true;
    }
    if (isset($_POST['password']) === true) {
        $password = $_POST['password'];
    } else {
        $password = '';
        $_SESSION['err_flg'] = true;
    }
    if (isset($_POST['category'])) {
        $product_list = get_product_list($pdo, $_POST['category']);
        $product_list = h_array($product_list);
    }
    if (isset($_POST['add_cart'])) {
        $user_id = get_user_id($pdo, $_SESSION['user_id']);
        $product_id = $_POST['product_id'];
        $product_qty = $_POST['product_qty'];
        if (add_cart($pdo, $user_id, $product_id, $product_qty)) {
            echo '<script>alert("正常にカートに追加されました。")</script>';
        } else {
            echo '<script>alert("エラーが発生しました。")</script>';
        }
    }
}



// ユーザー名の保存チェックがされている場合はCookieを保存
if ($cookie_confirmation === 'checked') {
    setcookie('cookie_confirmation', $cookie_confirmation, $cookie_expiration);
    setcookie('user_id', $user_name, $cookie_expiration);
} else {
    // チェックされていない場合はCookieを削除する
    setcookie('cookie_confirmation', '', time() - 30);
    setcookie('user_id', '', time() - 30);
}

//ログイン中のユーザーであるか確認する
if (empty($_SESSION['user_id'])) {
    //ログイン中ではない場合は、index.phpにリダイレクトする
    header('Location: index.php');
    exit();
}

include_once '../../include/view/product_view.php';