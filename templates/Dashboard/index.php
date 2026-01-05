<div class="dashboard-container" style="max-width: 900px; margin: 0 auto; padding: 20px;">
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
        <?php if (isset($tasks) && $tasks->count() > 0): ?>
            <?php foreach ($tasks as $task): ?>
                <!-- 親タスク -->
                <div class="task-parent" style="margin-bottom: 20px; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <div class="task-item" style="background: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; <?= $task->completed ? 'opacity: 0.6;' : '' ?>">
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
                                <span style="font-size: 18px; font-weight: bold; <?= $task->completed ? 'text-decoration: line-through;' : '' ?>">
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
                        <div style="display: flex; gap: 10px;">
                            <button 
                                onclick="document.getElementById('subtask-form-<?= $task->id ?>').style.display='block'; this.style.display='none';"
                                style="background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                サブタスク追加
                            </button>
                            <?= $this->Form->postLink('削除', 
                                ['action' => 'delete', $task->id],
                                [
                                    'confirm' => 'このタスク（サブタスクも含む）を削除しますか？',
                                    'style' => 'background: #f44336; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px;'
                                ]
                            ) ?>
                        </div>
                    </div>

                    <!-- サブタスク追加フォーム（初期非表示） -->
                    <div id="subtask-form-<?= $task->id ?>" style="display: none; background: #f9f9f9; padding: 15px; border-top: 1px solid #ddd;">
                        <?= $this->Form->create(null, ['url' => ['action' => 'addSubtask', $task->id]]) ?>
                        <div style="display: flex; gap: 10px; align-items: flex-end;">
                            <div style="flex: 2;">
                                <?= $this->Form->control('title', [
                                    'label' => 'サブタスク名',
                                    'required' => true,
                                    'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;'
                                ]) ?>
                            </div>
                            <div style="flex: 1;">
                                <?= $this->Form->control('due_date', [
                                    'label' => '期限',
                                    'type' => 'date',
                                    'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;'
                                ]) ?>
                            </div>
                            <div>
                                <?= $this->Form->button('追加', [
                                    'style' => 'background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;'
                                ]) ?>
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('subtask-form-<?= $task->id ?>').style.display='none';"
                                    style="background: #999; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
                                    キャンセル
                                </button>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>

                    <!-- サブタスク一覧 -->
                    <?php if (!empty($task->child_tasks)): ?>
                        <div style="background: #fafafa; padding: 10px 15px 10px 45px; border-top: 1px solid #e0e0e0;">
                            <?php foreach ($task->child_tasks as $subtask): ?>
                                <div class="subtask-item" style="background: white; border-left: 3px solid #2196F3; padding: 10px; margin-bottom: 8px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; <?= $subtask->completed ? 'opacity: 0.6;' : '' ?>">
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $subtask->id]]) ?>
                                            <input 
                                                type="checkbox" 
                                                <?= $subtask->completed ? 'checked' : '' ?>
                                                onchange="this.form.submit()"
                                                style="width: 18px; height: 18px; cursor: pointer;"
                                            >
                                            <?= $this->Form->end() ?>
                                            <span style="font-size: 15px; <?= $subtask->completed ? 'text-decoration: line-through;' : '' ?>">
                                                <?= h($subtask->title) ?>
                                            </span>
                                        </div>
                                        <?php if ($subtask->due_date): ?>
                                            <div style="margin-top: 5px; margin-left: 28px; font-size: 13px; color: #666;">
                                                期限: <?= $subtask->due_date->format('Y年m月d日') ?>
                                                <?php
                                                $today = new \DateTime();
                                                $dueDate = $subtask->due_date;
                                                if (!$subtask->completed && $dueDate < $today) {
                                                    echo '<span style="color: red; font-weight: bold;"> (期限切れ)</span>';
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?= $this->Form->postLink('削除', 
                                            ['action' => 'delete', $subtask->id],
                                            [
                                                'confirm' => 'このサブタスクを削除しますか？',
                                                'style' => 'background: #f44336; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px;'
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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