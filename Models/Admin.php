<?php
require_once(ROOT_PATH . '/Models/Db.php');

class Admin extends Db
{
    private $table = 'admin';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    //ログイン
    public function admin_login($email)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam('email', $email, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //管理人情報
    public function admin_info()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE 0 = ' . $_SESSION['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //管理人情報編集
    public function update_admin()
    {
        $sql = 'UPDATE ' . $this->table . ' SET name = :name, email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam('name', $_POST['name'], PDO::PARAM_STR);
        $sth->bindParam('email', $_POST['email'], PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
