<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

$user = $questions->userInfo();
$admin = $questions->admin_info();
$params = $questions->index();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>IT知恵袋</title>
</head>

<body>
    <header>
        <div class="main-tab">
            <div>
                <a href="index.php"><img src="../images/logo.png"></a>
            </div>
            <div class="new-post">
                <a href="newPost.php">新規投稿</a>
            </div>
            <div class="user-detail">
                <?php if ($user == true) : ?>
                    <a href="user_detail.php?id=<?= $_SESSION['id'] ?>" class="user_image">
                        <img src="../user_images/<?= $user['image'] ?>" alt="">
                    </a>
                    <a href="user_detail.php?id=<?= $_SESSION['id'] ?>">
                        <p><?= e($user['name']) ?></p>
                    </a>
                <?php endif; ?>
                <?php if ($admin == true) : ?>
                    <a href="user_detail.php?id=<?= $_SESSION['id'] ?>" class="user_image">
                        <img src="../user_images/icon.png ?>" alt="">
                    </a>
                    <a href="user_edit.php?id=<?= $_SESSION['id'] ?>">
                        <p><?= e($admin['name']) ?></p>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="index-main">
        <div class="index-posts">
            <table>
                <?php foreach ($params['questions'] as $questions) : ?>
                    <tr>
                        <td class="posts-img"><a class="posts-img" href="user_detail.php?id=<?= $questions['ユーザーID'] ?>"><img class="userImage" src="../user_images/<?= $questions['image'] ?>" alt=""></a></td>
                        <td class="posts-name"><a href="user_detail.php?id=<?= $questions['ユーザーID'] ?>"><?= e($questions['name']) ?></a></td>
                        <td class="posts-title"><a href="request.php?id=<?= $questions['id'] ?>">
                                <pre><?= e($questions['title']) ?></pre>
                            </a></td>
                        <td class="posts-created"><?= e($questions['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="paging">
                <?php
                if (empty($_POST)) {
                    for ($i = 0; $i <= $params['pages']; $i++) {
                        if (isset($_GET['page']) && $_GET['page'] == $i) {
                            echo $i + 1;
                        } else {
                            echo "<a href='?page=" . $i . "'>" . ($i + 1) . "</a>";
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="search">
            <form action="./search.php" method="get">
                <input type="text" name="search" placeholder="キーワードを入力">
                <button type="submit">検索</button>
            </form>
        </div>
    </main>
</body>

</html>