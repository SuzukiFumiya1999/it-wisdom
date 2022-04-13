<?php
require_once(ROOT_PATH . '/Models/Db.php');

class Users extends Db
{
    private $table = 'users';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    //新規登録
    public function checkSignup()
    {
        $sql = 'INSERT INTO ' . $this->table . ' (name,email,password)
                VALUES (:name,:email,:password)';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //ユーザー情報
    public function userInfo()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $_SESSION['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function requestUser()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $_GET['user_id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //ログイン
    public function checkLogin($email)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //パスワード再設定
    public function rePass($name, $email)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE name = :name AND email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(":name", $name);
        $sth->bindParam(":email", $email);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function resetting($name, $email, $password)
    {
        $sql = 'UPDATE ' . $this->table . ' SET password = :password WHERE name = :name AND email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(":name", $name);
        $sth->bindParam(":email", $email);
        $sth->bindParam(":password", password_hash($password, PASSWORD_DEFAULT));
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //usersデータ取得
    public function users_countAll(): Int
    {
        $sql = 'SELECT count(*) as count FROM questions WHERE questions.del_flg = 0 AND user_id = ' . $_GET['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    public function users_index($page)
    {
        $sql = 'SELECT users.id as "ユーザーID", users.name, users.image, questions.id, questions.title, questions.user_id, questions.created_at
        FROM questions LEFT JOIN users ON questions.user_id = users.id WHERE questions.user_id = ' . $_GET['id'] . ' AND questions.del_flg = 0 ORDER BY created_at DESC LIMIT 10 OFFSET ' . (10 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //users情報編集
    public function update_user()
    {
        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $sql = 'UPDATE ' . $this->table . ' SET  name = :name, email = :email WHERE id = ' . $_POST['user_id'];
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
            $sth->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $file = $_FILES['image'];
            $file_name = basename($file['name']);
            $tmp_name = $file['tmp_name'];
            $save_name = date('YmdHis') . $file_name;

            $sql = 'UPDATE ' . $this->table . ' SET image = :image, name = :name, email = :email WHERE id = ' . $_POST['user_id'];
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(":image", $save_name, PDO::PARAM_STR);
            $sth->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
            $sth->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }
}
