<?php

//セッション開始
session_start();

//定数を読み込む
require_once '../../include/config/const.php';

//product_model.phpを読み込む
require_once '../../include/model/cart_model.php';

$cart_data = [];
$pdo = get_connection();
$user_id = get_user_id($pdo, $_SESSION['user_id']);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_qty'])) {
        $qty = $_POST['product_qty'];
        $cart_id = $_POST['id_value'];
        $update_qty = update_qty($pdo, $qty, $cart_id);
        if (!$update_qty) {
            $err_msg = "エラーが発生しました。";
        } else {
            $success_msg = "商品の個数を変更しました。";
        }
    }

    if (isset($_POST['delete_item'])) {
        $cart_id = $_POST['id_value'];
        $delete_item = delete_item($pdo, $cart_id);
        if (!$delete_item) {
            $err_msg = "エラーが発生しました。";
        } else {
            $success_msg = "カートから商品を削除しました。";
        }
    }

    if (isset($_POST['reset_cart'])) {
        $reset_cart = delete_cart($pdo, $user_id);
        if (!$reset_cart) {
            $err_msg = "エラーが発生しました。";
        } else {
            $success_msg = "ご注文を取り消しました。";
        }
    }
}

//ログイン中のユーザーであるか確認する
if (empty($_SESSION['user_id'])) {
    //ログイン中ではない場合は、index.phpにリダイレクトする
    header('Location: index.php');
    exit();
}

$cart_data = get_cart_data($pdo, $user_id);
$cart_data = h_array($cart_data);

include_once '../../include/view/cart_view.php';