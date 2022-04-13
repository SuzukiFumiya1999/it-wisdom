<?php
require_once(ROOT_PATH . '/Models/Db.php');

class Replies extends Db
{
    private $table = 'replies';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    //回答
    public function reply()
    {
        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $sql = 'INSERT INTO ' . $this->table . ' (content, user_id, question_id)
                VALUES (:content, :user_id, :question_id)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam('content', $_POST['content'], PDO::PARAM_STR);
            $sth->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
            $sth->bindParam('question_id', $_POST['question_id'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $save_name = date('YmdHis') . $file_name;

            $sql = 'INSERT INTO ' . $this->table . ' (image, content, user_id, question_id)
                VALUES (:image, :content, :user_id, :question_id)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':image', $save_name, PDO::PARAM_STR);
            $sth->bindParam('content', $_POST['content'], PDO::PARAM_STR);
            $sth->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
            $sth->bindParam('question_id', $_POST['question_id'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    //repliesテーブルから一致するデータを取得
    public function findReply()
    {
        $sql = 'SELECT replies.id, replies.content, replies.user_id, replies.question_id, replies.image as "回答画像", replies.created_at, users.name, users.image as "ユーザーイメージ" FROM replies
                LEFT JOIN users ON replies.user_id = users.id WHERE replies.question_id = ' . $_GET['id'] . ' AND replies.del_flg = 0';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //questionsテーブルに紐づけされたrepliesテーブル削除（論理削除）
    public function delete_question_reply()
    {
        $sql = $sql = 'UPDATE ' . $this->table . ' SET del_flg = 1 WHERE question_id = ' . $_POST['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //repliesテーブルとusersテーブルからgetした値と同じidデータ取得
    public function find_replies_questions()
    {
        $sql = 'SELECT replies.id, replies.content, replies.user_id, replies.question_id, replies.image as "回答画像", replies.created_at, users.name, users.image as "ユーザーイメージ" FROM replies
        LEFT JOIN users ON replies.user_id = users.id WHERE replies.id = ' . $_GET['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //回答編集
    public function update_reply()
    {
        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $sql = 'UPDATE ' . $this->table . ' SET content = :content WHERE id = ' . $_GET['id'];
            $sth = $this->dbh->prepare($sql);
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

            $sql = 'UPDATE ' . $this->table . ' SET image = :image, content = :content WHERE id = ' . $_GET['id'];
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(":image", $save_name, PDO::PARAM_STR);
            $sth->bindParam(":content", $_POST['content'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    //回答削除(論理削除)
    public function delete_reply()
    {
        $sql = 'UPDATE ' . $this->table . ' SET del_flg = 1 WHERE id = ' . $_POST['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
