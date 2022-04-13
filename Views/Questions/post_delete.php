<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$request = $questions->requestQuestion();
$user = $questions->userInfo();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user['id'] === $_SESSION['id']) {
        $questions->delete_question();

        $dir = 'C:\xampp\htdocs\7-2\public\question_images/';
        unlink($dir . $request['質問画像']);
    }
}

?>

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
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>

    <div class="delete-container">
        <div class="delete-content">
            <p>本当に削除しますか？</p>
        </div>
        <div class="delete-btn">
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $request['id'] ?>">
                <button type="submit">削除</button>
                <a href="#" onclick="window.history.back()">戻る</a>
            </form>
        </div>
    </div>
</body>

</html>