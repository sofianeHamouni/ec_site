<?php $total_price;?>
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
        <div class="cart-wrapper">
            <h2 class="section-title">
            <?php if ($success_flg){echo "ご注文が確定致しました、ご利用ありがとうございました！";} ?>
            </h2>
            <?php if (!empty($err_msg)) :?>
                <h3 class="err-msg"><?php echo $err_msg?></h3>
                <?php for ($i = 0;$i < count($err_items); $i++) :?>
                    <p class="err_msg">・<?php echo $err_items[$i]?></p>
                <?php endfor?>
            <?php endif?>
            <div class="cart-items">
            <?php foreach ($cart_data as $value) :?> 
                <div class="cart-item">
                    <div class="item-data">
                        <img src="<?php echo $value['image_path'] ?>">
                        <h4><?php echo $value['product_name'] ?></h4>
                    </div>
                    <div class="item-param">
                        <div class="item_qty">
                            <p>単価:\<?php echo $value['price']?></p>
                            <p>個数:<?php echo $value['product_qty']?></p>
                        </div>
                        <div class="item-total">
                            <h4>
                                小計:\
                                <?php $subtotal = $value['price'] * $value['product_qty'];
                                echo number_format($subtotal);
                                $total_price += $subtotal;?>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php endforeach?>
            </div>
            <div class="total">
                <h3>合計:\<?php echo number_format($total_price);?></h3>
                <div class="clear"></div>
            </div>
            <div class="btn-wrapper">
                <a href="product.php">商品一覧に戻る</a>
            </div>

        </div>
    </main>
<?php require_once "footer.php"?>
</body>
</html>