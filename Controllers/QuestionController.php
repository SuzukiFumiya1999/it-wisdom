<?php
require_once(ROOT_PATH . '/Models/Users.php');
require_once(ROOT_PATH . '/Models/Questions.php');
require_once(ROOT_PATH . '/Models/Replies.php');
require_once(ROOT_PATH . '/Models/Admin.php');
require_once(ROOT_PATH . './Models/Goods.php');

class QuestionController
{
    private $request; //リクエストパラメーター（GET,POST）
    private $Questions; //Questionsモデル
    private $Users; //Usersモデル
    private $Replies; //Repliesモデル
    private $Admin; //Adminモデル
    private $Goods; //Goodsモデル

    public function __construct()
    {
        //リクエストパラメーターの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Questions = new Questions();

        //別モデルと連携
        $dbh = $this->Questions->get_db_handler();
        $this->Users = new Users($dbh);
        $this->Replies = new Replies($dbh);
        $this->Admin = new Admin($dbh);
        $this->Goods = new Goods($dbh);
    }

    //全質問取得
    public function index()
    {
        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $questions = $this->Questions->index($page);
        $questions_count = $this->Questions->countAll();
        $params = [
            'questions' => $questions,
            'pages' => $questions_count / 10
        ];
        return $params;
    }

    //ユーザー情報
    public function userInfo()
    {
        $userInfo = $this->Users->userInfo();
        return $userInfo;
    }

    public function requestUser()
    {
        $request = $this->Users->requestUser();
        return $request;
    }

    public function requestQuestion()
    {
        $request = $this->Questions->requestQuestion();
        return $request;
    }

    public function users_index()
    {
        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $users_data = $this->Users->users_index($page);
        $users_count = $this->Users->users_countAll();
        $params = [
            'questions' => $users_data,
            'pages' => $users_count / 10
        ];
        return $params;
    }

    //管理人情報
    public function admin_info()
    {
        $admin_info = $this->Admin->admin_info();
        return $admin_info;
    }


    //新規投稿
    public function insert()
    {
        $insert = $this->Questions->insert();
        header('Location: postComp.php');
        return $insert;
    }

    //新規登録
    public function signup()
    {
        $signup = $this->Users->checkSignup();
        header('Location: signUp_comp.php');
        return $signup;
    }

    //質問投稿編集
    public function edit_question()
    {
        $edit_question = $this->Questions->update_question();
        header('Location: post_edit_comp.php');
        return $edit_question;
    }

    //質問投稿削除
    public function delete_question()
    {
        $delete_question = $this->Questions->delete_question();
        $delete_question_reply = $this->Replies->delete_question_reply();
        header('Location: index.php');
        return [$delete_question, $delete_question_reply];
    }

    //ログイン
    public function login($email, $password)
    {
        $error = [];
        if ($email != "" && $password != "") {
            $login = $this->Users->checkLogin($email);

            if ($login != false) {
                if (password_verify($password, $login['password'])) {
                    session_regenerate_id();
                    header('Location: ../index.php');
                    $_SESSION['id'] = $login['id'];
                } else {
                    $error['failed'] = 'パスワードが正しくありません。';
                    return $error;
                }
            } else {
                $error['failed'] = '入力が正しくありません。';
                return $error;
            }
        } else {
            $error['blank'] = '入力されていない項目があります。';
            return $error;
        }
    }

    //管理人ログイン
    public function admin_login($email, $password)
    {
        $error = [];
        if ($email != "" && $password != "") {
            $login = $this->Admin->admin_login($email);

            if ($login != false) {
                $hash = password_hash($login['password'], PASSWORD_DEFAULT);
                if (password_verify($password, $hash)) {
                    session_regenerate_id();
                    header('Location: ../index.php');
                    $_SESSION['id'] = 0;
                } else {
                    $error['failed'] = 'パスワードが正しくありません。';
                    return $error;
                }
            } else {
                $error['failed'] = '入力が正しくありません。';
                return $error;
            }
        } else {
            $error['blank'] = '入力されていない項目があります。';
            return $error;
        }
    }

    //パスワード再設定
    public function rePass($name, $email, $password)
    {
        if ($name != "" && $email != "" && $password != "") {
            $rePass = $this->Users->rePass($name, $email);

            if ($rePass != false) {
                $this->Users->resetting($name, $email, $password);
                header('Location: rePass_comp.php');
            }
        }
    }

    //回答
    public function reply()
    {
        $reply = $this->Replies->reply();
        header('Location: request_comp.php?id=' . $_POST['question_id']);
        return $reply;
    }

    //回答編集
    public function update_reply()
    {
        $update_reply = $this->Replies->update_reply();
        header('Location: reply_edit_comp.php?id=' . $_POST['question_id']);
        return $update_reply;
    }

    //回答削除（論理削除）
    public function delete_reply()
    {
        $delete_reply = $this->Replies->delete_reply();
        header('Location: request.php?id=' . $_GET['question_id']);
    }

    //repliesテーブルから一致するデータを取得
    public function findReply()
    {
        $findReplies = $this->Replies->findReply();
        return $findReplies;
    }

    public function find_replies_questions()
    {
        $find = $this->Replies->find_replies_questions();
        return $find;
    }

    //users情報編集
    public function update_user()
    {
        $update_user = $this->Users->update_user();
        header('Location: user_detail.php?id=' . $_POST['user_id']);
        return $update_user;
    }

    //管理人情報編集
    public function update_admin()
    {
        $update_admin = $this->Admin->update_admin();
        header('Location: index.php');
        return $update_admin;
    }

    //goodsテーブルから質問IDとユーザーIDが一致する物を取得
    public function good_question()
    {
        $good_question = $this->Goods->good_question();
        return $good_question;
    }

    public function count_good()
    {
        $count_good = $this->Goods->count_good();
        return $count_good;
    }

    //questionsテーブルからいいねしたものを取得
    public function good_list()
    {
        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $good_list = $this->Questions->good_list($page);
        $count_good_question = $this->Questions->count_good_question();

        $params = [
            'questions' => $good_list,
            'pages' => $count_good_question / 10
        ];
        return $params;
    }
}
