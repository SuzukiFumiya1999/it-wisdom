<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$count_good_list = $questions->good_list();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>IT知恵袋-いいね</title>
</head>

<body>
    <header class="good_list-header">
        <a href="index.php" class="good_list-img"><img src="../images/logo.png" alt=""></a>
        <a href="#" onclick="history.back()">
            <p>ユーザー詳細画面に戻る</p>
        </a>
    </header>

    <div class="posts">
        <table>
            <?php foreach ($count_good_list['questions'] as $questions) : ?>
                <tr>
                    <td class="posts-img"><a class="posts-img" href="user_detail.php?id=<?= $questions['ユーザーID'] ?>"><img class="userImage" src="../user_images/<?= $questions['image'] ?>" alt=""></a></td>
                    <td class="posts-name"><a href="user_detail.php?id=<?= $questions['ユーザーID'] ?>"><?= e($questions['name']) ?></a></td>
                    <td class="posts-title"><a href="request.php?id=<?= $questions['id'] ?>"><?= e($questions['title']) ?></a></td>
                    <td class="posts-created"><?= e($questions['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="paging">
        <?php
        if ($count_good_list['pages'] === 0) { ?>
            <div class="not-question">
                <h1>投稿は見つかりませんでした。</h1>
            </div>
        <?php } else {
            for ($i = 0; $i <= $count_good_list['pages']; $i++) {
                if (isset($_GET['page']) && $_GET['page'] == $i) {
                    echo $i + 1;
                } else {
                    echo "<a href='?id=" . $_GET['id'] . "&page=" . $i . "'>" . ($i + 1) . "</a>";
                }
            }
        }
        ?>
    </div>
</body>

</html>