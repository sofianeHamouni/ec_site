<?php
//セッション開始
session_start();

//定数を読み込む
require_once '../../include/config/const.php';

//sign_up.model.phpを読み込む
require_once '../../include/model/sign_up_model.php';
$pdo = get_connection();
$err_msg;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    //入力されているかどうかをチェック
    if (empty($user_id)) {
        $err_msg = 'ユーザー名を入力してください';
        include '../../include/view/sign_up_view.php';
        exit;
    }

    if (empty($password)) {
        $err_msg = 'パスワードを入力してください';
        include '../../include/view/sign_up_view.php';
        exit;
    }

    // バリデーションを実装する
    if (!preg_match('/^[a-zA-Z0-9]{5,}+$/', $user_id)) {
        $err_msg = 'ユーザー名は5文字以上の半角英数字で入力して下さい。';
        include '../../include/view/sign_up_view.php';
        exit;
    }
    if (!preg_match('/^[a-zA-Z0-9]{8,}+$/', $password)) {
        $err_msg = 'パスワードは8文字以上の半角英数字で入力して下さい。';
        include '../../include/view/sign_up_view.php';
        exit;    
    }

    if (check_user_exist($pdo, $user_id) > 0) {
        // ユーザー名がすでに存在する場合は、エラーメッセージをセットする
        $err_msg = 'このユーザー名は既に登録されています。別のユーザー名をお試しください。';
        include '../../include/view/sign_up_view.php';
        exit;    
    }

    // ユーザー情報を保存する
    if (empty($err_msg) && sign_up_user($pdo, $user_id, $password)) {
        // ログイン状態にするため、セッションにユーザー情報を保存する
        $_SESSION['user_id'] = $user_id;
        $_SESSION['password'] = $password;
        // 保存に成功した場合は、product.phpに遷移する
        echo "<script>alert('ユーザー登録に成功しました。{$user_id}さん、ようこそ！'); location.href='product.php';</script>";
        exit;
    } else {
        // 保存に失敗した場合は、エラーメッセージを表示する
        $err_msg =  'ユーザー登録に失敗しました。再度お試しください。';
    }
}

//ログイン中であるかを判別
if (isset($_SESSION['user_id'])) {
    //ログイン中である場合はproduct.phpにリダイレクトする
    header('Location: product.php');
    exit();
}

include '../../include/view/sign_up_view.php';