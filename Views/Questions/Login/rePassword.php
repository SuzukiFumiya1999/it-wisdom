<?php
session_start();
$_SESSION = array();
session_destroy();

session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$users = new QuestionController();
$form = [
    'name' => "",
    'email' => "",
    'password' => ""
];

//バリデーション
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    if (empty($_POST['name'])) {
        $errors['name'] = '*名前は必須入力です。';
    }

    if (empty($_POST['email'])) {
        $errors['email'] = '*メールアドレスは必須入力です。';
    } else {
        $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/uiD';
        if (!preg_match($pattern, $_POST['email'])) {
            $errors['email'] = '*メールアドレスは正しくご入力ください。';
        }
    }

    if (empty($_POST['password'])) {
        $errors['password'] = '*パスワードは必須入力です。';
    } else {
        $pattern = '/\A[a-z\d]{0,255}+\z/i';
        if (!preg_match($pattern, $_POST['password'])) {
            $errors['password'] = '*パスワードは正しくご入力ください。';
        }
    }

    if (empty($_POST['re-pass'])) {
        $errors['re-pass'] = '*パスワード再確認は必須入力です。';
    } else {
        if ($_POST['password'] != $_POST['re-pass']) {
            $errors['re-pass'] = '*パスワードが一致しません。';
        }
    }

    if (count($errors) === 0) {
        //ログイン
        if (isset($_POST)) {
            $form['name'] = filter_input(INPUT_POST, 'name');
            $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $users->rePass($form['name'], $form['email'], $form['password']);
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
    <link rel="stylesheet" href="/css/rePass.css">
    <script src="https://kit.fontawesome.com/9d76df0505.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="../js/login.js"></script>
    <title>IT知恵袋-パスワード再設定</title>
</head>

<body>
    <div class="itw-logo">
        <a href="../index.php"><img src="../images/logo.png" alt=""></a>
    </div>
    <div class="rePass">
        <div class="rePass-container">
            <div class="rePass-title">
                <h2>パスワード再設定</h2>
            </div>
            <form action="" method="post">
                <dl>
                    <span>
                        <td><label for="name">氏名</label></td>
                    </span>
                    <td><input type="text" name="name" placeholder="山田太郎" id="name"></td>
                    <p class="error">
                        <?php
                        if (isset($errors['name'])) {
                            echo $errors['name'];
                        }
                        ?>
                    </p>
                </dl>
                <dl>
                    <span>
                        <td><label for="email">メールアドレス</label></td>
                    </span>
                    <td><input type="email" name="email" id="email" placeholder="it-tiebukuro@it.com"></td>
                    <p class="error">
                        <?php
                        if (isset($errors['email'])) {
                            echo $errors['email'];
                        }
                        ?>
                    </p>
                </dl>
                <dl>
                    <span>
                        <td><label for="password">パスワード</label></td>
                    </span>
                    <td class="td-pass">
                        <input type="password" name="password" id="password">
                        <i class="toggle-pass fa-regular fa-eye"></i>
                    </td>
                    <p class="error">
                        <?php
                        if (isset($errors['password'])) {
                            echo $errors['password'];
                        }
                        ?>
                    </p>
                </dl>
                <dl>
                    <span>
                        <td><label for="re-pass">パスワード再確認</label></td>
                    </span>
                    <td><input type="text" name="re-pass" id="re-pass"></td>
                    <p class="error">
                        <?php
                        if (isset($errors['re-pass'])) {
                            echo $errors['re-pass'];
                        }
                        ?>
                    </p>
                </dl>
                <dl class="btn">
                    <td>
                        <button type="submit" class="btn-submit">パスワードを再設定</button>
                    </td>
                </dl>
            </form>
        </div>
    </div>
</body>

</html>