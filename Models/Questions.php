<?php
require_once(ROOT_PATH . '/Models/Db.php');

class Questions extends Db
{
    private $table = 'questions';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    //questionsテーブルから全データ数を取得
    public function countAll(): Int
    {
        $sql = 'SELECT count(*) as count FROM ' . $this->table . ' WHERE questions.del_flg = 0';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    //questionsテーブルから情報取得
    public function requestQuestion()
    {
        $sql = 'SELECT users.id as "ユーザーID", users.name, users.image as "ユーザーイメージ", questions.id , questions.title, questions.content, questions.image as "質問画像", questions.user_id, questions.created_at
        FROM questions LEFT JOIN users ON questions.user_id = users.id WHERE questions.id = ' . $_GET['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //questionsテーブルからすべて取得
    public function index($page)
    {
        $sql = 'SELECT users.id as "ユーザーID", users.name, users.image, questions.id, questions.title, questions.user_id, questions.created_at
                FROM questions LEFT JOIN users ON questions.user_id = users.id WHERE questions.del_flg = 0 ORDER BY created_at DESC LIMIT 10 OFFSET ' . (10 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //新規投稿
    public function insert()
    {
        $sqlUserId = 'SELECT id FROM users WHERE id = ' . $_SESSION['id'];
        $sthUserId = $this->dbh->prepare($sqlUserId);
        $sthUserId->execute();
        $resultUserId = $sthUserId->fetch(PDO::FETCH_ASSOC);

        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $sql = "INSERT INTO " . $this->table . " (title, content, created_at, user_id)
                VALUES (:title, :content, current_timestamp(), " . $resultUserId['id'] . ")";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $sth->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $save_name = date('YmdHis') . $file_name;

            $sql2 = "INSERT INTO " . $this->table . " (title, content, image, created_at, user_id)
            VALUES (:title, :content, :image, current_timestamp(), " . $resultUserId['id'] . ")";;
            $sth2 = $this->dbh->prepare($sql2);
            $sth2->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $sth2->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $sth2->bindParam(":image", $save_name, PDO::PARAM_STR);
            $sth2->execute();
            $result = $sth2->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    //質問投稿編集
    public function update_question()
    {

        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $sql = 'UPDATE ' . $this->table . ' SET title = :title, content = :content WHERE id = ' . $_GET['id'];
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(":title", $_POST['title'], PDO::PARAM_STR);
            $sth->bindParam(":content", $_POST['content'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $save_name = date('YmdHis') . $file_name;

            $sql = 'UPDATE ' . $this->table . ' SET title = :title, image = :image, content = :content WHERE id = ' . $_GET['id'];
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(":title", $_POST['title'], PDO::PARAM_STR);
            $sth->bindParam(":image", $save_name, PDO::PARAM_STR);
            $sth->bindParam(":content", $_POST['content'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    //質問投稿削除（論理削除）
    public function delete_question()
    {
        $sql = 'UPDATE ' . $this->table . ' SET del_flg = 1 WHERE id = ' . $_POST['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //goodsテーブルからuser_idと一致するデータを取得
    public function good_list($page)
    {
        $sql = 'SELECT users.id as "ユーザーID", users.name, users.image, questions.id, questions.title, questions.user_id, questions.created_at
                FROM goods LEFT JOIN questions ON questions.id = goods.question_id LEFT JOIN users ON users.id = questions.user_id WHERE questions.del_flg = 0 AND goods.user_id = ' . $_GET['id'] . ' AND questions.id = goods.question_id ORDER BY created_at DESC LIMIT 10 OFFSET ' . (10 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function count_good_question(): Int
    {
        $sql = 'SELECT count(*) as count FROM goods LEFT JOIN questions ON questions.id = goods.question_id WHERE goods.user_id = ' . $_GET['id'] . ' AND goods.question_id = questions.id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    //検索機能
    public function search_question($page)
    {
        $sql = 'SELECT users.id as "ユーザーID", users.name, users.image, questions.id, questions.title, questions.user_id, questions.created_at
                FROM questions LEFT JOIN users ON questions.user_id = users.id WHERE questions.del_flg = 0 AND questions.title LIKE "%' . $_GET['search'] . '%" ORDER BY created_at DESC LIMIT 10 OFFSET ' . (10 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function search_countAll(): Int
    {
        $sql = 'SELECT count(*) as count FROM ' . $this->table . ' WHERE del_flg = 0 AND questions.title LIKE "%' . $_GET['search'] . '%"';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }
}
