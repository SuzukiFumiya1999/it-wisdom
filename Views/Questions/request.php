<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$request = $questions->requestQuestion();
$user = $questions->userInfo();
$admin = $questions->admin_info();
$findReply = $questions->findReply();
$good = $questions->good_question();
$count_good = $questions->count_good();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//回答
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_POST['content'])) {
        $errors['content'] = '*回答内容は必須入力です。';
    } else {
        if (512 < strlen($_POST['content'])) {
            $errors['content']  = '512文字以内でご入力ください。';
        }
    }

    if (count($errors) === 0) {
        //回答
        if (isset($_POST)) {
            $questions->reply();

            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $dir = 'C:\xampp\htdocs\7-2\public\reply_images/';
            $save_filename = $dir . date('YmdHis') . $file_name;

            if (is_uploaded_file($tmp_name)) {
                move_uploaded_file($tmp_name, $save_filename);
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
    <script src="https://kit.fontawesome.com/9d76df0505.js" crossorigin="anonymous"></script>
    <title>IT知恵袋</title>
</head>

<body>
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>
    <div class="request-container">
        <div class="top-flex">
            <div class="q-user_detail">
                <a href="user_detail.php?id=<?= $request['ユーザーID'] ?>"><img class="userImage" src="../user_images/<?= $request['ユーザーイメージ'] ?>"></a>
                <a href="user_detail.php?id=<?= $request['ユーザーID'] ?>">
                    <p class="userName"><?= $request['name'] ?></p>
                </a>
            </div>
            <div class="q-title"><?= e($request['title']) ?></div>
        </div>

        <div class="q-image">
            <img src="../question_images/<?= $request['質問画像'] ?>" alt="">
        </div>

        <div class="q-content">
            <pre><?= nl2br(e($request['content'])) ?></pre>
        </div>

        <div class="bot-flex">
            <div class="good">
                <button class="good-btn" data-question_id="<?= $request['id'] ?>">
                    <?php if (empty($good)) { ?>
                        <i class="fa-regular fa-heart"></i>
                    <?php } else { ?>
                        <i class="fa-solid fa-heart"></i>
                    <?php } ?>
                </button>
                <span class="good-count"><?= e($count_good) ?></span>
            </div>
            <div class="edit">
                <?php if ($user == true) {
                    if ($user['id'] === $request['user_id']) {
                ?>
                        <a href="post_edit.php?id=<?= $request['id'] ?>">編集</a>
                <?php }
                } ?>
                <?php if ($admin == true) : ?>
                    <a href="post_edit.php?id=<?= $request['id'] ?>">編集</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="replies">
            <div class="reply-title">
                <h1>この質問への回答</h1>
            </div>
            <?php foreach ($findReply as $reply) : ?>
                <div class="reply-user_info">
                    <a href="user_detail.php?id=<?= $reply['user_id'] ?>"><img src="../user_images/<?= $reply['ユーザーイメージ'] ?>" alt=""></a>
                    <a href="user_detail.php?id=<?= $reply['user_id'] ?>">
                        <p><?= e($reply['name']) ?></p>
                    </a>
                </div>
                <div class="reply-image">
                    <img src="../reply_images/<?= $reply['回答画像'] ?>" alt="">
                </div>
                <div class="reply-content">
                    <pre><?= nl2br(e($reply['content'])) ?></pre>
                    <div class="reply_edit">
                        <?php if ($user == true) {
                            if ($user['id'] === $reply['user_id']) {
                        ?>
                                <a href="reply_edit.php?id=<?= $reply['id'] ?>&question_id=<?= $request['id'] ?>">編集</a>
                        <?php }
                        } ?>
                        <?php if ($admin == true) : ?>
                            <a href="reply_edit.php?id=<?= $reply['id'] ?>&question_id=<?= $request['id'] ?>">編集</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="answer-title">
                <h1>この質問に回答する</h1>
            </div>
            <dl>
                <dt><label for="a-image">画像を貼り付ける</label></dt>
                <dt><input type="file" name="image" id="a-image" accept="image/*"></dt>
                <dt><img id="a-preview"></dt>
            </dl>

            <dl>
                <dt><label for="a-content">回答内容<span class="required">(必須)</span></label></dt>
                <dt><textarea name="content" id="a-content" rows="14" maxlength="512" placeholder="512文字以内でご入力ください。"></textarea></dt>
                <dt>
                    <p class="count">あと<span id="num3"></span>文字</p>
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
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <input type="hidden" name="question_id" value="<?= $request['id'] ?>">
                <button type="submit">回答</button>
            </dl>
        </form>
    </div>
    <script src="./js/good.js"></script>
    <script src="/js/request.js"></script>
</body>

</html>