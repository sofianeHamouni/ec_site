<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>D-Foods</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php require_once "header.php"?>
    <main>
        <div class="main-visual"></div>
        <div class="wrapper">
            <div class="section-title">
                <h2>メニュー一覧</h2>
            </div>
            <div class="container">
                <div class="mini-header">
                    <h3>カテゴリーを選択</h3>
                    <form method="post">
                        <div class="category">
                            <ul>
                                <li>
                                    <button type="submit" name="category">
                                        <img class="icon all_categories" src="sample-images/icon_frypan.png">
                                        全て
                                    </button>
                                </li>
                                <li>
                                    <button type="submit" name="category" value="1">
                                        <img class="icon japanese" src="sample-images/icon_sushi.png">
                                        和食
                                    </button>
                                </li>
                                <li>
                                    <button type="submit" name="category" value="2">
                                        <img class="icon foreign" src="sample-images/icon_steak.png">
                                        洋食
                                    </button>
                                </li>
                                <li>
                                    <button type="submit" name="category" value="3">
                                        <img class="icon chinese" src="sample-images/icon_ramen.png">
                                            中華
                                    </button>
                                </li>
                                <li>
                                    <button type="submit" name="category" value="4">
                                        <img class="icon others" src="sample-images/fork_knife.png">
                                            その他
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="product-container">
                    <?php if (empty($product_list)) : ?>
                        <p class="not-found">商品が見つかりませんでした。</p>
                        <?php endif; ?>
                    <?php foreach($product_list as $value):?>
                    <div class="product">
                        <?php if ($value['stock_qty'] < 1) : ?>
                        <div class="sold">
                            <p>申し訳ありません、品切れ中です。</p>
                        </div>
                        <?php endif; ?>
                        <img src="<?php echo $value['image_path']?>">
                        <p><?php echo $value['product_name']?></p>
                        <p>\<?php echo number_format($value['price'])?></p>
                        <label class="open" for="popup-<?php echo $value['product_id'] ?>">商品を表示</label>
                        <input type="checkbox" style="display: none" id="popup-<?php echo $value['product_id'] ?>">
                        <div class="gray">
                            <div class="window">
                                <label class="close" for="popup-<?php echo $value['product_id'] ?>">×</label>
                                <div class="product">
                                    <img src="<?php echo $value['image_path']?>">
                                    <div class="product-data">
                                        <h4><?php echo $value['product_name']?></h4>
                                        <h4>\<?php echo number_format($value['price'])?></h4>
                                    </div>
                                </div>
                                <form method="post">
                                    <div class="qty-select">
                                        <label for="qty-select">個数を選択</label>
                                        <select name="product_qty" id="qty-select">
                                            <?php for ($i = 1; $i <= $value['stock_qty']; $i++) : ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                    <button type="submit" name="add_cart">カートに追加</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
                </div>
            </div>
        </div>
    </main>
    <?php require_once "footer.php"?>
</body>

</html>