<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <title>IT知恵袋-新規投稿</title>
</head>

<body>
    <?php
    //バリデーション
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $errors = [];

        if (empty($_POST['title'])) {
            $errors['title'] = '*質問タイトルは必須入力です。';
        } else {
            if (255 < mb_strlen($_POST['title'])) {
                $errors['title'] = '255文字以内で入力してください。';
            }
        }

        if (empty($_POST['content'])) {
            $errors['content'] = '*質問内容は必須入力です。';
        } else {
            if (512 < mb_strlen($_POST['content'])) {
                $errors['content'] = '*512文字以内でご入力ください。';
            }
        }

        if (count($errors) === 0) {
            //新規投稿
            if (isset($_POST)) {
                $questions->insert();

                $file = $_FILES['image'];
                $file_name = basename($file['name']);
                $tmp_name = $file['tmp_name'];
                $dir = 'C:\xampp\htdocs\itw\public\question_images/';
                $save_filename = $dir . date('YmdHis') . $file_name;

                if (is_uploaded_file($tmp_name)) {
                    move_uploaded_file($tmp_name, $save_filename);
                }
            }
        }
    }
    ?>
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png"></a>
    </div>
    <main class="new-post_main">
        <form action="" method="post" enctype="multipart/form-data" class="newPost">
            <dl>
                <dt><label for="title">質問タイトル<span class="required">（必須）</span></label></dt>
                <dt><textarea name="title" id="title" maxlength="255" rows="7" placeholder="255文字以内で入力してください"></textarea></dt>
                <dt>
                    <p class="count">あと<span id="num1"></span>文字</p>
                </dt>
                <p class="error">
                    <?php
                    if (isset($errors['title'])) {
                        echo $errors['title'];
                    }
                    ?>
                </p>
            </dl>
            <dl>
                <dt><label for="image" class="upimg-label">画像を貼り付ける</label></dt>
                <dt><input type="file" name="image" id="image" accept="image/*"></dt>
                <dt><img id="preview"> </dt>
            </dl>
            <dl>
                <dt><label for="content">質問内容<span class="required">（必須）</span></label></dt>
                <dt><textarea name="content" id="content" rows="14" maxlength="512" placeholder="512文字以内で入力してください"></textarea></dt>
                <dt>
                    <p class="count">あと<span id="num2"></span>文字</p>
                </dt>
                <p class="error">
                    <?php
                    if (isset($errors['content'])) {
                        echo $errors['content'];
                    }
                    ?>
                </p>
            </dl>
            <dl>
                <div class="btnSubmit">
                    <button type="submit">投稿</button>
                    <button type="button" onclick=history.back()>戻る</button>
                </div>
            </dl>
        </form>
    </main>

    <script src="/js/script.js"></script>
</body>

</html>