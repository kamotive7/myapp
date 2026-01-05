<div class="dashboard-container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 30px;">タスク管理</h1>

    <!-- タスク追加フォーム -->
    <div class="task-form" style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <?= $this->Form->create(null, ['type' => 'post']) ?>
        <div style="margin-bottom: 15px;">
            <?= $this->Form->control('title', [
                'label' => 'タスク名',
                'placeholder' => '新しいタスクを入力...',
                'required' => true,
                'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'
            ]) ?>
        </div>
        <div style="margin-bottom: 15px;">
            <?= $this->Form->control('due_date', [
                'label' => '期限',
                'type' => 'date',
                'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'
            ]) ?>
        </div>
        <?= $this->Form->button('タスクを追加', [
            'style' => 'background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'
        ]) ?>
        <?= $this->Form->end() ?>
    </div>

    <!-- タスク一覧 -->
    <div class="tasks-list">
        <h2 style="margin-bottom: 20px;">タスク一覧</h2>
        <?php if ($tasks->count() > 0): ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-item" style="background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; <?= $task->completed ? 'opacity: 0.6;' : '' ?>">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $task->id]]) ?>
                            <input 
                                type="checkbox" 
                                <?= $task->completed ? 'checked' : '' ?>
                                onchange="this.form.submit()"
                                style="width: 20px; height: 20px; cursor: pointer;"
                            >
                            <?= $this->Form->end() ?>
                            <span style="font-size: 16px; <?= $task->completed ? 'text-decoration: line-through;' : '' ?>">
                                <?= h($task->title) ?>
                            </span>
                        </div>
                        <?php if ($task->due_date): ?>
                            <div style="margin-top: 5px; margin-left: 30px; font-size: 14px; color: #666;">
                                期限: <?= $task->due_date->format('Y年m月d日') ?>
                                <?php
                                $today = new \DateTime();
                                $dueDate = $task->due_date;
                                if (!$task->completed && $dueDate < $today) {
                                    echo '<span style="color: red; font-weight: bold;"> (期限切れ)</span>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?= $this->Form->postLink('削除', 
                            ['action' => 'delete', $task->id],
                            [
                                'confirm' => 'このタスクを削除しますか？',
                                'style' => 'background: #f44336; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px;'
                            ]
                        ) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #999; padding: 40px 0;">タスクがありません。新しいタスクを追加してください。</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->Html->link(
    'ログアウト',
    $this->Url->build(['controller' => 'Login', 'action' => 'logout'])
) ?>