<?php
session_start();
$_SESSION = array();
session_destroy();

session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/rePass_comp.css">
    <title>IT知恵袋-パスワード再設定完了</title>
</head>

<body>
    <div class="itw-logo">
        <a href="../index.php"><img src="../images/logo.png" alt=""></a>
    </div>
    <div class="comp-container">
        <div class="comp-title">
            <h2>パスワード再設定が完了しました</h2>
        </div>
        <div class="comp-content">
            <p>今すぐ質問を始めよう。</p>
        </div>
        <div class="btn">
            <button type="button" onclick=location.href='login.php'>ログイン画面に戻る</button>
        </div>
    </div>
</body>

</html>