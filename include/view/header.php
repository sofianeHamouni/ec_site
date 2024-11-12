<header>
    <div class="header-logo">
        <h1><span>D</span>-FOO<span>D</span>S</h1>
    </div>
    <div class="header-menu">
        <form method="post" action="index.php">
            <ul>
                <?php if (!isset($_SESSION['user_id'])) {?>
                    <li><a href="index.php">ログイン</a></li>
                    <li><a href="sign_up.php">新規登録</a></li>
                <?php } else { ?>
                    <li><p><?php echo $_SESSION["user_id"]?>さん、ログイン中です</p></li>
                    <li><a href="product.php">商品一覧</a></li>
                    <li><a href="cart.php">カートを見る</a></li>
                    <li><input type="submit" name="logout" value="ログアウト"></li>
                <?php } ?>
            </ul>
        </form>
    </div>
    <div class="clear"></div>
</header>