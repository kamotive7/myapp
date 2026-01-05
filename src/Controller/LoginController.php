<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use App\Model\Table\UsersTable;
use Cake\Log\Log;

class LoginController extends AppController
{
    protected ?UsersTable $Users = null;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->Users = $this->fetchTable('Users');
    }

    //GET/login
    public function index()
    {
        //ログインしてるならダッシュボードへ
        if ($this->request->getSession()->check('user')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
    }

    //POST/login
    public function auth()
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'index']);
        }

        $data = $this->request->getData();

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

       Log::debug('Username: '. $username);
       Log::debug('Password: '. $password);

        //ユーザー取得
        $user = $this->Users
            ->find()
            ->where(['username' => $username])
            ->first();

        if ($user && password_verify($password, $user->password)) {

            $this->request->getSession()->write('user', [
                'id' => $user->id,
                'name' => $user->name,
            ]);

            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $this->Flash->error('ユーザー名またはパスワードが違います。');
        return $this->redirect(['action' => 'index']);
    }

    //POST/login/create - アカウント作成処理
    public function create()
    {
        if(!$this->request->is('post')) {
            return $this->redirect(['action' => 'register']);
        }

        $data = $this->request->getData();

        //バリデーション
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $passwordConfirm = $data['password_confirm'] ?? '';
        $name = $data['name'] ?? '';

        //入力チェック
        if(empty($username) || empty($password) || empty($name)) {
            $this->Flash->error('すべての項目を入力してください。');
            return $this->redirect(['action' => 'register']);
        }
        
        //パスワード確認
        if($password !== $passwordConfirm) {
            $this->Flash->error('パスワードが一致しません。');
            return $this->redirect(['action' => 'register']);
        }

        //ユーザー名の重複チェック
        $exsistingUser = $this->Users
            ->find()
            ->where(['username' => $username])
            ->first();

        if($exsistingUser) {
            $this->Flash->error('このユーザー名は既に使用されています。');
            return $this->redirect(['action' => 'register']);
        }

        //ユーザー作成
        $user = $this->Users->newEmptyEntity();
        $user = $this->Users->patchEntity($user, [
            'username' => $username,
            'password' => $password,
            'name' => $name,
        ]);

        if($this->Users->save($user)) {
            $this->Flash->success('アカウントを作成しました。ログインしてください。');
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error('アカウント作成に失敗しました。');
        return $this->redirect(['action' => 'register']);
    }

    //GET/login/register - アカウント作成
    public function register()
    {
        //ログインしてるならダッシュボードへ
        if($this->request->is('post')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
    }

    public function logout()
    {
        $this->request->getSession()->destroy();
        return $this->redirect(['action' => 'index']);
    }
}
