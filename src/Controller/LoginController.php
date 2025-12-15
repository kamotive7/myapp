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

    public function logout()
    {
        $this->request->getSession()->destroy();
        return $this->redirect(['action' => 'index']);
    }
}
