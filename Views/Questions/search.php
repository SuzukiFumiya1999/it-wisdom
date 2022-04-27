<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: Login/login.php');
}

require_once(ROOT_PATH . 'Controllers/QuestionController.php');

function e($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

$questions = new QuestionController;
$search = $questions->search_question();
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
    <div class="itw-logo">
        <a href="index.php"><img src="../images/logo.png" alt=""></a>
    </div>

    <div class="search_question">
        <form action="./search.php" method="get">
            <input type="text" name="search" placeholder="キーワードを入力">
            <button type="submit">検索</button>
        </form>
    </div>

    <div class="posts">
        <table>
            <?php foreach ($search['questions'] as $questions) : ?>
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
                for ($i = 0; $i <= $search['pages']; $i++) {
                    if (isset($_GET['page']) && $_GET['page'] == $i) {
                        echo $i + 1;
                    } else {
                        echo "<a href='?search=" . $_GET['search'] . "&page=" . $i . "'>" . ($i + 1) . "</a>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>