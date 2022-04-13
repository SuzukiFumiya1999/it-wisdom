<?php
session_start();

$dbh = new PDO("mysql:dbname=itw", "root", "makaron1009");

if ($_POST['question_id']) {
    $count_sql = 'SELECT count(*) as count FROM goods WHERE question_id = ' . $_POST['question_id'];
    $count_sth = $dbh->prepare($count_sql);
    $count_sth->execute();
    $count = $count_sth->fetchColumn();

    $find_sql = ' SELECT * FROM goods WHERE user_id = ' . $_SESSION['id'] . ' AND question_id = ' . $_POST['question_id'];
    $find_sth = $dbh->prepare($find_sql);
    $find_sth->execute();
    $find_result = $find_sth->fetch(PDO::FETCH_ASSOC);

    if (!empty($find_result)) {
        $sql = 'DELETE FROM goods WHERE user_id = ' . $_SESSION['id'] . ' AND question_id = ' . $_POST['question_id'];
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        echo $count - 1;
        return $result;
    } else {
        $sql = 'INSERT INTO goods (user_id, question_id)
                VALUES (' . $_SESSION['id'] . ', ' . $_POST['question_id'] . ')';
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        echo $count + 1;
        return $result;
    }
}
