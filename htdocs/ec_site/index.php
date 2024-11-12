<?php
//セッション開始
session_start();

//定数を読み込む
require_once '../../include/config/const.php';

//index.model.phpを読み込む
require_once '../../include/model/index_model.php';


//ログアウト処理がされた場合
if (isset($_POST['logout'])) {
    
    //セッション名を取得する
    $session = session_name();
    //セッションを削除
    $_SESSION = [];


    //セッションID(ユーザ側のCookieに保存されている)を削除
    if (isset($_COOKIE['session'])) {
        //sessionに関連する設定を削除
        $params = session_get_cookie_params();

        //cookie削除
        setcookie($session, '', time()-30, '/');
    }

}

// ログインしている場合はproduct.phpページにリダイレクト
if (isset($_SESSION['user_id'])) {
    header('Location: product.php');
    exit();
}

// POSTで送信された場合の処理
if (isset($_POST['login'])) {
    // ユーザー名とパスワードの取得
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // 入力されたユーザー名とパスワードが空でないかどうかをチェック

    if (empty($user_id) || empty($password)) {
        $err_msg = 'ユーザー名とパスワードを入力してください';
        include '../../include/view/index_view.php';
        exit;
    }
    
    

    //データベースに接続
    $pdo = get_connection();

    $user_list = [];

      // 入力されたユーザー名とパスワードが一致するユーザーを取得
    $user_list = get_user_list($pdo, $user_id, $password);
    $user_list = h_array($user_list);
    $login_success = false;
    foreach ($user_list as $user) {
        if ($user['user_name'] === $user_id && $user['password'] === $password) {
            $login_success = true;
            break;
        }
    }

    // ログイン成功した場合
    if ($login_success) {
        // 管理者の場合、セッションに管理者フラグを保存しない
        if ($user_id === 'ec_admin') {
            $_SESSION['user_id'] = $user_id;
            header('Location: admin.php');
            exit;
        }
        // ユーザーIDをセッションに保存して、ログイン状態とする
        $_SESSION['user_id'] = $user_id;
        header('Location: product.php');
        exit;
    } else {
        // ログイン失敗した場合、エラーメッセージを表示
        $err_msg = 'ユーザー名またはパスワードが間違っています';
    }
}

//ログイン中であるかを判別
if (isset($_SESSION['user_id'])) {
    //ログイン中である場合はproduct.phpにリダイレクトする
    header('Location: product.php');
    exit();
}

//cookieに値がある場合、変数に格納する

if (isset($_COOKIE['cookie_confirmation']) === TRUE) {
    $cookie_confirmation = "checked";
} else {
    $cookie_confirmation = "";
}
if (isset($_COOKIE["user_id"]) === TRUE) {
    $user_id = $_COOKIE["user_id"];
} else {
    $user_id = '';
}


include '../../include/view/index_view.php';