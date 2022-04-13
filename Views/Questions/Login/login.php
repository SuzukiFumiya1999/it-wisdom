<?php
session_start();
$_SESSION = array();
session_destroy();

session_start();

require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$users = new QuestionController();
$form = [
    'email' => "",
    'password' => ""
];

//バリデーション
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

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
        $pattern = '/\A[a-z\d]{1,255}+\z/i';
        if (!preg_match($pattern, $_POST['password'])) {
            $errors['password'] = '*パスワードは正しくご入力ください。';
        }
    }

    if (count($errors) === 0) {
        //ログイン
        if (isset($_POST)) {
            $form["email"] = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $form["password"] = filter_input(INPUT_POST, "password");

            $loginError = $users->login($form["email"], $form["password"]);
            $admin_loginError = $users->admin_login($form["email"], $form["password"]);

            $users->login($form['email'], $form['password']);
            $users->admin_login($form['email'], $form['password']);
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
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://kit.fontawesome.com/9d76df0505.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="../js/login.js"></script>
    <title>IT知恵袋-ログイン</title>
</head>

<body>
    <div class="itw-logo">
        <a href="../index.php"><img src="../images/logo.png" alt=""></a>
    </div>
    <div class="login-title">
        <h2>今すぐあなたも質問してみよう。</h2>
        <p>ログインはこちらから</p>
    </div>
    <div class="login-container">
        <form action="" method="post">
            <dl>
                <dt>メールアドレス</dt>
                <dd><input type="email" name="email" id="email"></dd>
                <p class="error">
                    <?php
                    if (isset($errors['email'])) {
                        echo $errors['email'];
                    }
                    ?>
                </p>
            </dl>
            <dl>
                <dt>パスワード</dt>
                <dd class="dd-pass">
                    <input type="password" name="password" id="password">
                    <i class="toggle-pass fa-regular fa-eye"></i>
                </dd>
                <p class="error">
                    <?php
                    if (isset($errors['password'])) {
                        echo $errors['password'];
                    }
                    ?>
                </p>
            </dl>
            <dl class="form-list">
                <button type="submit">ログイン</button>
                <button type="button" class="sign-up" onclick="location.href='signUp.php'">新規登録</a></button>
            </dl>
            </dl>
            <?php if (isset($loginError['failed']) || isset($admin_loginError['failed'])) : ?>
                <dt class="error"><?= $loginError['failed'] ?></dt>
            <?php endif; ?>
            <dl>
                <a href="rePassword.php" class="re-password">パスワードを忘れた場合</a>
            </dl>
        </form>
    </div>
</body>

</html>