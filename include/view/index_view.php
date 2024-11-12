<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>D-Foods</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            let userId = document.forms["login"]["user_id"].value;
            let password = document.forms["login"]["password"].value;
            if (userId == "") {
                return false;
            }
            if (!/^[a-zA-Z0-9]+$/.test(userId)) {
                return false;
            }
            if (password == "") {
                return false;
            }
            if (!/^[a-zA-Z0-9]+$/.test(password)) {
                return false;
            }
        }
    </script>
</head>

<body>
<?php require_once "header.php" ?>
    <main>
        <form action="index.php" method="post" onsubmit="return validateForm()">
        <h2 class="section-title">ログイン</h2>
            <div class="login">
                <div class="err_msg">
                    <?php if(!empty($err_msg)) {echo $err_msg;}?>
                </div>
                <p>ユーザーID</p>
                <input type="text" class="user-id" name="user_id" autocomplete="off" required="required" minlength="5">
                <p>パスワード</p>
                <input type="password" class="password" name="password" required="required" minlength="8" autocomplete="current-password">
                <label for="checkbox"><input id="checkbox" type="checkbox" class="checkbox" name="cookie_confirmation">ログイン状態を保存する</label>
                <input class="btn" type="submit" name="login" value="ログイン">
                <a class="sign-in" href="sign_up.php">新規登録の方はこちら</a>
                <div class="clear"></div>
            </div>
        </form>
    </main>
</body>

</html>