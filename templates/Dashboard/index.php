<h1>ダッシュボード</h1>

<p>
    ようこそ、<?=  h($user['name']) ?>　さん
</p>

<hr>

<p>ここにタスク管理ツールを作っていく予定。</p>

<ul>
    <li>・タスク一覧</li>
    <li>・タスク追加</li>
    <li>・完了/未完了</li>
</ul>

<?= $this->Html->link(
    'ログアウト',
    $this->Url->build(['controller' => 'Login', 'action' => 'logout'])
) ?>