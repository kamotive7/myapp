<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class LoginController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
    }

    //GET/login
    public function index()
    {
        //ログインしてるならダッシュボードへ
        if($this->request->getSession()->check('user')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
    }

    //POST/login
    public function auth() 
    {
        $data = $this->request->getData();

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if($username === 'admin' && $password === 'password') {

            $this->request->getSession()->write('user', [
                'name' => 'Admin User'
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