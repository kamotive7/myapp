<?php
declare(strict_types=1);

namespace App\Controller;

class DashboardController extends AppController
{
    public function index()
    {
        $session = $this->request->getSession();

        //未ログインならログイン画面へ
        if(!$session->check('user')) {
            return $this->redirect([
                'controller' => 'Login',
                'action' => 'index'
            ]);
        }

        //ログインユーザ情報をViewに渡す
        $this->set('user', $session->read('user'));
    }
}