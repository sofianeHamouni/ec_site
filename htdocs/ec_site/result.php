<?php
session_start();
//定数を読み込む
require_once '../../include/config/const.php';

//admin.model.phpを読み込む
require_once '../../include/model/result_model.php';


if($_SERVER['HTTP_REFERER'] != BASE_URL.'/htdocs/ec_site/cart.php') {
    header('Location: product.php');
    exit;
}
$pdo = get_connection();
$cart_data = [];
$user_id = get_user_id($pdo, $_SESSION['user_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm_order'])) {
        //ログイン中のユーザーのカート内データを取得
        $cart_data = get_cart_data($pdo, $user_id);
        $cart_data = h_array($cart_data);
        $reduce = reduce_all($cart_data, $pdo);
        if($reduce === true) {
            $delete = delete_cart($pdo, $user_id);
            if ($delete === true) {
                $success_flg = true;
            } else {
                $err_msg = "申し訳ありません、下記の商品の購入処理でエラーが発生しました。";
                $err_items = $reduce;
            }
        } else {
            $err_msg = "申し訳ありません、下記の商品の購入処理でエラーが発生しました。";
            $err_items = $reduce;
        }
    }
}
//ビューデータを出力する
include_once '../../include/view/result_view.php';
