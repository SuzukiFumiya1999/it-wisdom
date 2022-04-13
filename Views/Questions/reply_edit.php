<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$user = $questions->userInfo();
$find = $questions->find_replies_questions();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

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
            $questions->update_reply();

            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $dir = 'C:\xampp\htdocs\7-2\public\reply_images/';
            $save_filename = $dir . date('YmdHis') . $file_name;

            if (is_uploaded_file($tmp_name)) {
                move_uploaded_file($tmp_name, $save_filename);
                unlink($dir . $find['回答画像']);
            }
        }
    }
}

$data = e($find['content']);
$data2 = str_replace("<br>", "", $data);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <title>IT知恵袋-回答編集画面</title>
</head>

<body>
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>

    <main class="reply-edit_main">
        <div class="replies_edit">
            <div class="reply-user_info">
                <img src="../user_images/<?= $find['ユーザーイメージ'] ?>" alt="">
                <p><?= e($find['name']) ?></p>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="reply-image">
                    <input type="file" name="image" id="edit-reply_image" accept="image/*">
                    <img id="edit-reply_preview" alt="" src="../reply_images/<?= $find['回答画像'] ?>">
                </div>

                <div class="edit-reply_content">
                    <textarea name="content" id="edit-reply_content" rows="14" maxlength="512" placeholder="512文字以内でご入力ください。"><?= $data2 ?></textarea>
                    <p class="count">あと<span id="num5"></span>文字</p>
                    <p class="error">
                        <?php
                        if (isset($errors['content'])) {
                            echo $errors['content'];
                        }
                        ?>
                    </p>
                </div>

                <div class="edit-reply_tab">
                    <input type="hidden" name="question_id" value="<?= $find['question_id'] ?>">
                    <button type="submit">編集</button>
                    <a href="reply_delete.php?id=<?= $_GET['id'] ?>&question_id=<?= $_GET['question_id'] ?>">削除</a>
                    <a href="#" onclick="window.history.back()">戻る</a>
                </div>
            </form>
        </div>
    </main>
    <script src="js/reply_edit.js"></script>
</body>

</html>