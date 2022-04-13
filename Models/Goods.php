<?php
require_once(ROOT_PATH . '/Models/Db.php');

class Goods extends Db
{
    private $table = 'goods';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    //goodsテーブルから質問IDとユーザーIDが一致する物を取得
    public function good_question()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE user_id = ' . $_SESSION['id'] . ' AND question_id = ' . $_GET['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //goodsテーブルからいいね数取得
    public function count_good()
    {
        $sql = 'SELECT count(*) as count FROM ' . $this->table . ' WHERE question_id = ' . $_GET['id'];
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }
}
