<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>D-Foods_mgt</title>
	<style>
		.products-wrapper {
			flex-wrap: wrap;
			padding: 10px 0;
			display: flex;
			width: 90%;
			margin: 0 auto;
		}
		.product-wrapper {
			width: 25%;
			height: 150px;
			border: 1px solid #000;
			margin-right: 10px;
			margin-bottom: 10px;
		}
		
		.product-wrapper p {
			text-align: center;
		}
		.button {
			display: block;
			margin: 0 auto;
		}

		.err-msg {
			color: red;
		}

		.success-message {
			color: green;
		}

		table {
			border: 3px double black;
			width: 100%;
			margin: 0 auto;
		}

		td, th {
			border: 1px solid black;
			text-align: center;
			width: 10%;
		}

		img {
			width: 100%;
		}

		
	</style>
	<script type="text/javascript">
		function check() {
			if (window.confirm("データを削除します。本当によろしいですか？")) {
				return true;
			} else {
				return false;
			}
		}
	</script>
</head>
<body>
	<h2>商品管理ページ</h2>
	<form action="index.php" method="post">
		<input type="submit" name="logout" value="ログアウト">
	</form>
	
	<form method="post" enctype="multipart/form-data">
		<h3>商品登録</h3>
		<p>商品名:<input type="text" name="product_name"></p>
        <p>価格　:<input type="text" name="price"></p>
		<p>在庫数:<input type="text" name="new_stock"></p>
        <p>カテゴリ:<br>
        <input type="radio" name="category_id" value="1">和食
        <input type="radio" name="category_id" value="2">洋食
        <input type="radio" name="category_id" value="3">中華
        <input type="radio" name="category_id" value="4">その他</p>
		<p>画像ファイルを選択</p>
		<input type="file" name="upload_image">
		<br>
		<p>登録した商品を公開する：<input type="checkbox" name="new_flg" checked></p>
		<br>
		<input type="submit" name="submit" value="登録">
		<br>
	</form>
	<table>
		<caption>商品一覧</caption>
		<div class="success-message"><?php echo $success_msg?></div>
		<div class="err-msg">
		<?php if (count($error_msg) != 0) {
			foreach ($error_msg as $error) {
				echo '<div><font color = #ff0000>'.$error.'</font></div>';
			}
		}?>
		</div>
		<tr>
			<th class="id">商品ID</th>
			<th class="name">商品名</th>
			<th class="image">商品画像</th>
			<th class="price">価格</th>
			<th class="category">カテゴリ</th>
			<th class="stock">在庫数</th>
			<th class="flg">表示/非表示</th>
			<th class="delete">削除</th>
		</tr>

		<?php foreach ($product_data as $value) :?>
			<?php if($value["public_flg"] == 1):?>
				<tr class="product-wrapper" style="background:#fff">
			<?php else: ?>
				<tr class="product-wrapper" style="background:#444">
			<?php endif ?>
				<td class="id"><?php echo $value['product_id']?></td>
				<td class="name"><?php echo $value['product_name']?></td>
				<td class="image"><img src ="<?php echo $value['image_path']?>"></td>
				<td class="price"><?php echo number_format($value['price'])?></td>
				<td class="category">
					<?php switch($value['category_id']) {
						case 1: echo '和食';
						break;
						case 2: echo '洋食';
						break;
						case 3: echo '中華';
						break;
						case 4: echo 'その他';
						break;
					}?>
				</td>
				<td class="stock">
					<form method="post">
						<input type="number" name="stock" value=<?php echo $value['stock_qty']?>>
						<input type="submit" name="update_stock" value="変更">
						<input type="hidden" name="id_value" value="<?php echo $value["product_id"] ?>">
					</form>
				</td>
				<td class="flg">
					<form method="post">
							<input type="submit" name="update_flg" value="<?php if($value["public_flg"] == 1) {echo "非表示にする";} else {echo "表示にする";}?>">
							<input type="hidden" name="id_value" value="<?php echo $value["product_id"] ?>">
							<input type="hidden" name="public_flg" value="<?php echo $value["public_flg"] ?>">
					</form>
				</td>
				<td class="delete">
					<form method="post" onSubmit="return check()">
					<input type="hidden" name="id_value" value="<?php echo $value["product_id"] ?>">
					<input type="submit" name="delete" value="削除">
					</form>
				</td>
			</tr>
		<?php endforeach?>
	</table>
</body>
</html>