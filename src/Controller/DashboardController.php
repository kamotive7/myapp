<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\TasksTable;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;

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

        // ユーザー情報
        $user = $session->read('user');

        // 今日の日付
        $today = FrozenTime::now();

        $this->set(compact('user', 'today'));

        //ログインユーザ情報をViewに渡す
        $this->set('user', $session->read('user'));

        //現在のタブを取得・保存
        if ($this->request->getQuery('tab')) {
            $session->write('current_tab', $this->request->getQuery('tab'));
        }

        $currentTab = $session->read('current_tab') ?: 'dashboard';

        $this->set('currentTab', $currentTab);

        $tasks = $this->Tasks->find('all', [
            'conditions' => ['Tasks.parent_id IS' => null],
            'contain' => ['ChildTasks'],
            'order' => ['Tasks.end_date' => 'ASC', 'Tasks.created' => 'DESC']
        ]);

        $this->set(compact('tasks'));

        // ダッシュボード用の統計データ
        $this->set('statistics', $this->getStatistics());

        //カレンダー用のタスクデータ（親タスクのみに修正）
        $this->set('calendarTasks', $this->getCalendarTasks());

        //ガントチャート用のデータ（親タスクのみに修正）
        $this->set('ganttTasks', $this->getGanttTasks());


        //タスク追加処理
        if ($this->request->is('post')) {
            $task = $this->Tasks->newEmptyEntity();
            $data = $this->request->getData();

            //end_dateが設定されていてstart_dateが未設定の場合、3日前をstart_dateに設定
            if (!empty($data['end_date']) && empty($data['start_date'])) {
                $endDate = new \DateTime($data['end_date']);
                $endDate->modify('-3 days');
                $data['start_date'] = $endDate->format('Y-m-d');
            }

            $task = $this->Tasks->patchEntity($task, $data);

            if ($this->Tasks->save($task)) {
                $this->Flash->success('タスクを追加しました。');
                $session->write('current_tab', 'list');
                return $this->redirect(['action' => 'index', '?' => ['tab' => 'list']]);
            }
            $this->Flash->error('タスクの追加に失敗しました。');
            debug($task->getErrors());
        }
    }

    private function getStatistics()
    {
        $allTasks = $this->Tasks->find('all')->toArray();
        $now = \Cake\I18n\FrozenDate::now();
        $oneMonthLater = $now->addMonths(1);

        // 基本統計
        $totalTasks = count($allTasks);
        $completedTasks = count(array_filter($allTasks, fn($task) => $task->completed));
        $incompleteTasks = $totalTasks - $completedTasks;

        // 期限超過タスク
        $overdueTasks = count(array_filter($allTasks, function ($task) use ($now) {
            return !$task->completed && $task->end_date && $task->end_date < $now;
        }));

        // 今後一か月のタスク（完了ステータス別）
        $nextMonthTasks = array_filter($allTasks, function ($task) use ($now, $oneMonthLater) {
            return $task->end_date && $task->end_date >= $now && $task->end_date <= $oneMonthLater;
        });
        $nextMonthCompleted = count(array_filter($nextMonthTasks, fn($task) => $task->completed));
        $nextMonthIncomplete = count($nextMonthTasks) - $nextMonthCompleted;

        //棒グラフ用データ
        $today = $now;
        $tomorrow = $today->addDays(1);
        $nextWeekStart = $today->addDays(1);
        $nextWeekEnd = $today->addWeeks(1);

        $recentAssigned = count(array_filter($allTasks, function ($task) use ($now) {
            return $task->created >= $now->subDays(7);
        }));

        $todayTasks = count(array_filter($allTasks, function ($task) use ($today, $tomorrow) {
            return $task->end_date && $task->end_date >= $today && $task->end_date < $tomorrow;
        }));

        $nextWeekTasks = count(array_filter($allTasks, function ($task) use ($nextWeekStart, $nextWeekEnd) {
            return $task->end_date && $task->end_date >= $nextWeekStart && $task->end_date < $nextWeekEnd;
        }));

        $laterTasks = count(array_filter($allTasks, function ($task) use ($nextWeekEnd) {
            return !$task->end_date || $task->end_date >= $nextWeekEnd;
        }));

        return [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'incomplete' => $incompleteTasks,
            'overdue' => $overdueTasks,
            'nextMonthTotal' => count($nextMonthTasks),
            'nextMonthCompleted' => $nextMonthCompleted,
            'nextMonthIncomplete' => $nextMonthIncomplete,
            'barChart' => [
                'recentAssigned' => $recentAssigned,
                'today' => $todayTasks,
                'nextWeek' => $nextWeekTasks,
                'later' => $laterTasks
            ]
        ];
    }

    private function getCalendarTasks()
    {
        // 親タスクのみを取得（タスクリストと同じ条件）
        $tasks = $this->Tasks->find('all', [
            'conditions' => ['Tasks.parent_id IS' => null],
            'contain' => ['ChildTasks'],
            'order' => ['Tasks.end_date' => 'ASC']
        ])->toArray();

        $calendarData = [];
        
        // 親タスクをカレンダーに追加
        foreach ($tasks as $task) {
            if ($task->end_date) {
                $date = $task->end_date->format('Y-m-d');
                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }
                $calendarData[$date][] = [
                    'id' => $task->id,
                    'title' => $task->title,
                    'completed' => $task->completed,
                    'priority' => $task->priority ?? 'medium'
                ];
            }
            
            // サブタスクもカレンダーに追加
            foreach ($task->child_tasks as $subtask) {
                if ($subtask->end_date) {
                    $date = $subtask->end_date->format('Y-m-d');
                    if (!isset($calendarData[$date])) {
                        $calendarData[$date] = [];
                    }
                    $calendarData[$date][] = [
                        'id' => $subtask->id,
                        'title' => '┗ ' . $subtask->title,
                        'completed' => $subtask->completed,
                        'priority' => $subtask->priority ?? 'medium'
                    ];
                }
            }
        }

        return $calendarData;
    }

    private function getGanttTasks(): array
    {
        // 親タスクのみを取得（タスクリストと同じ条件）
        $tasks = $this->Tasks->find('all', [
            'conditions' => ['Tasks.parent_id IS' => null],
            'contain' => ['ChildTasks'],
            'order' => ['Tasks.start_date' => 'ASC']
        ])->toArray();

        $ganttTasks = [];

        foreach ($tasks as $task) {
            //親タスク
            if ($task->start_date && $task->end_date) {
                $ganttTasks[] = [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'start_date' => $task->start_date->format('Y-m-d'),
                    'end_date' => $task->end_date->format('Y-m-d'),
                    'completed' => $task->completed,
                    'priority' => $task->priority ?? 'medium',
                    'type' => 'parent'
                ];
            }

            //サブタスク
            foreach ($task->child_tasks as $subtask) {
                if ($subtask->start_date && $subtask->end_date) {
                    $ganttTasks[] = [
                        'id' => $subtask->id,
                        'title' => '┗ ' . $subtask->title,
                        'description' => $subtask->description,
                        'start_date' => $subtask->start_date->format('Y-m-d'),
                        'end_date' => $subtask->end_date->format('Y-m-d'),
                        'completed' => $subtask->completed,
                        'priority' => $subtask->priority ?? 'medium',
                        'type' => 'child',
                        'parent_id' => $task->id
                    ];
                }
            }
        }

        return $ganttTasks;
    }

    public function addSubtask($parentId = null)
    {
        //POSTメソッドのみ許可（セキュリティ）
        $this->request->allowMethod(['post']);

        //親タスクの存在確認（エラー防止)
        $parentTask = $this->Tasks->get($parentId);

        //新しい空のタスクエンティティを作成
        $subtask = $this->Tasks->newEmptyEntity();

        //フォームデータ全体を反映（title, description, end_date などが一気にセットされる）
        $subtask = $this->Tasks->patchEntity($subtask, $this->request->getData());

        //親IDを直接セット
        $subtask->parent_id = $parentId;

        //保存
        if ($this->Tasks->save($subtask)) {
            $this->Flash->success('サブタスクを追加しました。');
        } else {
            $this->Flash->error('サブタスクの追加に失敗しました。');
        }

        $session = $this->request->getSession();
        $session->write('current_tab', 'list');
        return $this->redirect(['action' => 'index', '?' => ['tab' => 'list']]);
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

        $session = $this->request->getSession();
        $session->write('current_tab', 'list');
        return $this->redirect(['action' => 'index', '?' => ['tab' => 'list']]);
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);

        //タスクを取得
        $task = $this->Tasks->get($id);

        //フォームから送られたデータで更新
        $task = $this->Tasks->patchEntity($task, $this->request->getData());

        if ($this->Tasks->save($task)) {
            $this->Flash->success('タスクを更新しました。');
        } else {
            $this->Flash->error('更新に失敗しました。');
        }

        $session = $this->request->getSession();
        $session->write('current_tab', 'list');
        return $this->redirect(['action' => 'index', '?' => ['tab' => 'list']]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id, ['contain' => ['ChildTasks']]);

        if ($this->Tasks->delete($task, ['cascadeCallbacks' => true])) {
            $this->Flash->success('タスクを削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。');
        }

        $session = $this->request->getSession();
        $session->write('current_tab', 'list');
        return $this->redirect(['action' => 'index', '?' => ['tab' => 'list']]);
    }
}