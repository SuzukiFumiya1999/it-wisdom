<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;
$user = $questions->userInfo();
$admin = $questions->admin_info();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_POST['name'])) {
        $errors['content'] = '*回答内容は必須入力です。';
    } else {
        if (255 < mb_strlen($_POST['name'])) {
            $errors['name']  = '255文字以内でご入力ください。';
        }
    }

    if (empty($_POST['email'])) {
        $errors['content'] = '*回答内容は必須入力です。';
    } else {
        if (255 < mb_strlen($_POST['email'])) {
            $errors['email']  = '255文字以内でご入力ください。';
        }
    }

    if (count($errors) === 0) {
        //回答
        if ($user == true) {
            if (isset($_POST)) {
                $questions->update_user();

                $file = $_FILES['image'];
                $file_name = basename($file['name']);
                $tmp_name = $file['tmp_name'];
                $dir = 'C:\xampp\htdocs\itw\public\user_images/';
                $save_filename = $dir . date('YmdHis') . $file_name;

                if (is_uploaded_file($tmp_name)) {
                    move_uploaded_file($tmp_name, $save_filename);
                    if ($user['image'] != 'icon.png') {
                        unlink($dir . $user['image']);
                    }
                }
            }
        }

        if ($admin == true) {
            if (isset($_POST)) {
                $questions->update_admin();
            }
        }
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <title>IT知恵袋</title>
</head>

<body>
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>

    <div class="admin-logout">
        <?php if ($_SESSION['id'] === 0) : ?>
            <a href="./Login/login.php">ログアウト</a>
        <?php endif; ?>
    </div>

    <main class="user_detail-form">
        <form action="" method="post" enctype="multipart/form-data">
            <?php if ($user == true) : ?>
                <div class="user_edit-image">
                    <label for="user_detail-image">
                        <input type="file" name="image" id="user_detail-image" accept="images/*" value="../user_images/<?= $user['image'] ?>">
                        <img id="edit-user_image_preview" alt="" src="../user_images/<?= $user['image'] ?>">
                    </label>
                </div>
            <?php endif; ?>

            <?php if ($user == true) : ?>
                <div class="user_edit-name">
                    <label for="user_detail-name">名前</label>
                    <input type="text" name="name" id="user_detail-name" value="<?= $user['name'] ?>">
                    <p class="error">
                        <?php
                        if (isset($errors['name'])) {
                            echo $errors['name'];
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($admin == true) : ?>
                <div class="user_edit-name">
                    <label for="user_detail-name">名前</label>
                    <input type="text" name="name" id="user_detail-name" value="<?= $admin['name'] ?>">
                    <p class="error">
                        <?php
                        if (isset($errors['name'])) {
                            echo $errors['name'];
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($user == true) : ?>
                <div class="user_edit-email">
                    <label for="user_detail-email">メールアドレス</label>
                    <input type="text" name="email" id="user_detail-email" value="<?= $user['email'] ?>">
                    <p class="error">
                        <?php
                        if (isset($errors['email'])) {
                            echo $errors['email'];
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($admin == true) : ?>
                <div class="user_edit-email">
                    <label for="user_detail-email">メールアドレス</label>
                    <input type="text" name="email" id="user_detail-email" value="<?= $admin['email'] ?>">
                    <p class="error">
                        <?php
                        if (isset($errors['email'])) {
                            echo $errors['email'];
                        }
                        ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="user_edit-btn">
                <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
                <button type="submit">保存</button>
                <a href="#" onclick="window.history.back()">キャンセル</a>
            </div>
        </form>
    </main>
    <script src="js/user_edit.js"></script>
</body>

</html>