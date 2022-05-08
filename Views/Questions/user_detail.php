<?php
session_start();
require_once(ROOT_PATH . 'Controllers/QuestionController.php');
$questions = new QuestionController;

$users_data = $questions->users_index();

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
    <link rel="stylesheet" href="css/style.css">
    <title>IT知恵袋</title>

</head>

<body>
    <div class="user_detail-tab">
        <a href="index.php" class="user_detail-logo"><img src="../images/logo.png"></a>
        <div class="user_detail-right_tab">
            <a href="./good_list.php?id=<?= $_GET['id'] ?>">いいね</a>
            <?php if ($_GET['id'] === $_SESSION['id']) { ?>
                <a href="user_edit.php?id=<?= $_GET['id'] ?>">プロフィール編集</a>
            <?php } ?>
            <?php if ($_GET['id'] === $_SESSION['id']) { ?>
                <a href="./Login/login.php" id="logout">ログアウト</a>
            <?php } ?>
        </div>
    </div>

    <div class="posts">
        <table>
            <?php foreach ($users_data['questions'] as $questions) : ?>
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
        if ($users_data['pages'] === 0) { ?>
            <div class="not-question">
                <h1>投稿がありません</h1>
            </div>
        <?php } else {
            for ($i = 0; $i <= $users_data['pages']; $i++) {
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