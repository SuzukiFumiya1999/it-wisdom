<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$request = $questions->requestQuestion();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_POST['title'])) {
        $errors['title'] = '*質問タイトルは必須入力です。';
    } else {
        if (255 < mb_strlen($_POST['title'])) {
            $errors['title'] = '255文字以内で入力してください。';
        }
    }

    if (empty($_POST['content'])) {
        $errors['content'] = '*回答内容は必須入力です。';
    } else {
        if (512 < mb_strlen($_POST['content'])) {
            $errors['content']  = '512文字以内でご入力ください。';
        }
    }

    if (count($errors) === 0) {
        //回答
        if (isset($_POST)) {
            $edit_question = $questions->edit_question();

            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $dir = 'C:\xampp\htdocs\itw\public\question_images/';
            $save_filename = $dir . date('YmdHis') . $file_name;

            if (is_uploaded_file($tmp_name)) {
                move_uploaded_file($tmp_name, $save_filename);
                unlink($dir . $request['質問画像']);
            }
        }
    }
}

$data = e($request['content']);
$data2 = str_replace("<br>", "", $data);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <title>IT知恵袋-投稿編集画面</title>
</head>

<body>
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>
    <div class="edit-post_form">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="edit-top_flex">
                <div class="edit-user_detail">
                    <a href="user_detail.php?id=<?= $request['ユーザーID'] ?>"><img src="../user_images/<?= $request['ユーザーイメージ'] ?>" alt="" class="edit-userImage"></a>
                    <a href="user_detail.php?id=<?= $request['ユーザーID'] ?>" class="edit-userName">
                        <p><?= e($request['name']) ?></p>
                    </a>
                </div>
                <div class="edit-q_title">
                    <input type="text" name="title" value="<?= e($request['title']) ?>">
                    <p class="error">
                        <?php
                        if (isset($errors['title'])) {
                            echo $errors['title'];
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="edit-post_image">
                <input type="file" name="image" id="edit-image" accept="image/*">
                <img id="edit-preview" alt="" src="../question_images/<?= $request['質問画像'] ?>">
            </div>

            <div class="edit-content">
                <textarea name="content" id="e-content" rows="14" maxlength="512" placeholder="512文字以内でご入力ください。"><?= $data2 ?></textarea>
                <p class="count">あと<span id="num4"></span>文字</p>
                <p class="error">
                    <?php
                    if (isset($errors['content'])) {
                        echo $errors['content'];
                    }
                    ?>
                </p>
            </div>

            <div class="edit_tab">
                <button type="submit">編集</button>
                <a href="post_delete.php?id=<?= $request['id'] ?>">削除</a>
                <a href="#" onclick="window.history.back()">戻る</a>
            </div>
        </form>
    </div>
    <script src="js/post_edit.js"></script>
</body>

</html>