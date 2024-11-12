<?php $total_price;?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>D-Foods</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript">
		function check_delete_item() {
			if (window.confirm("カートから商品を削除します。本当によろしいですか？")) {
				return true;
			} else {
				return false;
			}
		}
        function check_reset_cart() {
			if (window.confirm("ご注文内容を破棄します。本当によろしいですか？")) {
				return true;
			} else {
				return false;
			}
		}
        function check_confirm_order() {
			if (window.confirm("ご注文を確定します。よろしいですか？")) {
				return true;
			} else {
				return false;
			}
		}
	</script>
</head>

<body>
<?php require_once "header.php" ?>
    <main>
    <div class="cart-wrapper">
    <h2 class="section-title">ご注文内容</h2>
    <?php if (!empty($err_msg)) : ?>
        <h3 class="err_msg"><?php echo $err_msg?></h3>
    <?php elseif (!empty($success_msg)) :?>
        <h3 class="success_msg"><?php echo $success_msg?></h3>
    <?php endif?>
    <?php if (count($cart_data) == 0) :?>
            <h4 class="no-items">カートに商品がありません</h4>
        <?php else :?>
            <?php $total_price = 0; ?>
            <div class="cart-items">
            <?php foreach ($cart_data as $value) :?> 
                <div class="cart-item">
                    <div class="item-data">
                        <img src="<?php echo $value['image_path'] ?>">
                        <h4><?php echo $value['product_name'] ?></h4>
                    </div>
                    <div class="item-param">
                        <div class="item-qty">
                            <p>単価:\<?php echo number_format($value['price'])?></p>
                            <form method="post">
                                <label for="qty-select">数量:</label>
                                <select name="product_qty" id="qty-select">
                                <?php for ($i = 1; $i <= $value['stock_qty']; $i++) :?>
                                    <?php if ($i == $value['product_qty']) :?>
                                    <option value="<?php echo $i ?>" selected><?php echo $i?></option>
                                    <?php else :?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php endif;?>
                                <?php endfor;?>
                                </select>
                                <button type="submit" name="update_qty">変更</button>
                                <input type="hidden" name="id_value" value="<?php echo $value['cart_id']?>">
                            </form>
                        </div>
                        <div class="item-total">
                            <h4>
                                小計:\
                                <?php $subtotal = $value['price'] * $value['product_qty'];
                                echo number_format($subtotal);
                                $total_price += $subtotal;?>
                            </h4>
                            <form method="post" onSubmit="return check_delete_item()">
                                <input  class="btn" type="submit" name="delete_item" value="カートから削除">
                                <input type="hidden" name="id_value" value="<?php echo $value['cart_id']?>">
                            </form>
                        </div>
                    </div>
                    
                </div>
                <?php endforeach ?>
            <?php endif?>
            </div>
            <?php if (count($cart_data) != 0) :?>
            <div class="total">
                <h3>合計:\<?php echo number_format($total_price);?></h3>
                <div class="clear"></div>
            </div>
            <div class="btn-wrapper">
                <form method="post"  onSubmit="return check_reset_cart()">
                <button type="submit" class="reset-cart" name="reset_cart">注文を取り消す</button>
                </form>
                <form action="result.php" method="post"  onSubmit="return check_confirm_order()">
                    <button type="submit" class="confirm-order" name="confirm_order">注文を確定する</button>
                </form>
            </div>
            <?php endif?>
        </div>
    </main>
<?php require_once "footer.php"?>
</body>
</html>