<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>D-Foods</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php require_once "header.php"?>
    <main>
        <form method="post">
            <h2 class="section-title">新規登録</h2>
            <div class="login">
                <div class="err_msg">
                    <?php if(!empty($err_msg)) {echo $err_msg;}?>
                </div>
                <p>ユーザーID(半角英数字5文字以上)</p>
                <input type="text" class="user_id" name="user_id" required minlength="5">
                <p>パスワード(半角英数字8文字以上)</p>
                <input type="password" class="password" name="password" required minlength="8">
                <input class="btn" name="signup" type="submit" value="登録">
                <a class="sign-up" href="index.php">ログインの方はこちら</a>
                <div class="clear"></div>
            </div>
        </form>
    </main>
</body>

</html>