<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>IT知恵袋</title>
</head>

<body>
    <div class="logo">
        <img src="../images/logo.png" alt="">
    </div>
    <div class="comp-container">
        <div class="comp-title">
            <h2>編集が完了しました</h2>
        </div>
        <div class="comp-content">
            <p>いろんな質問をみつけよう。</p>
        </div>
        <div class="btn">
            <button type="button" onclick=location.href='request.php?id=<?= $_GET["id"] ?>'>投稿に戻る</button>
        </div>
    </div>
</body>

</html>