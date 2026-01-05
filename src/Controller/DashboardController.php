<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\TasksTable;

class DashboardController extends AppController
{
    protected TasksTable $Tasks;

    public function initialize(): void
    {
        parent::initialize();
        $this->Tasks = $this->fetchTable('Tasks');
    }

    public function index()
    {
        $session = $this->request->getSession();

        //未ログインならログイン画面へ
        if (!$session->check('user')) {
            return $this->redirect([
                'controller' => 'Login',
                'action' => 'index'
            ]);
        }

        //ログインユーザ情報をViewに渡す
        $this->set('user', $session->read('user'));

        $tasks = $this->Tasks->find('all', [
            'order' => ['Tasks.due_date' => 'ASC', 'Tasks.created' => 'DESC']
        ]);

        $this->set(compact('tasks'));

        //タスク追加処理
        if ($this->request->is('post')) {
            $task = $this->Tasks->newEmptyEntity();
            $task = $this->Tasks->patchEntity($task, $this->request->getData());

            if ($this->Tasks->save($task)) {
                $this->Flash->success('タスクを追加しました。');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('タスクの追加に失敗しました。');
        }
    }

    public function toggleComplete($id = null)
    {
        $this->request->allowMethod(['post']);
        $task = $this->Tasks->get($id);
        $task->completed = !$task->completed;

        if ($this->Tasks->save($task)) {
            $this->Flash->success('タスクを更新しました。');
        } else {
            $this->Flash->error('更新に失敗しました。');
        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);

        if ($this->Tasks->delete($task)) {
            $this->Flash->success('タスクを削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。');
        }

        return $this->redirect(['action' => 'index']);
    }
}
