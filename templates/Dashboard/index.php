<div class="dashboard-container" style="max-width: 900px; margin: 0 auto; padding: 20px;">
  <h1 style="margin-bottom: 30px;">タスク管理</h1>

  <div class="task-form" style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <?= $this->Form->create(null, ['type' => 'post']) ?>
    <div style="margin-bottom: 15px;">
      <?= $this->Form->control('title', ['label' => 'タスク名', 'placeholder' => '新しいタスクを入力...', 'required' => true, 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
    </div>
    <div style="margin-bottom: 15px;">
      <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'タスクの詳細を入力...', 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
    </div>
    <div style="margin-bottom: 15px;">
      <?= $this->Form->control('due_date', ['label' => '期限', 'type' => 'date', 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
    </div>
    <?= $this->Form->button('タスクを追加', ['style' => 'background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;']) ?>
    <?= $this->Form->end() ?>
  </div>

  <div class="tasks-list">
    <h2 style="margin-bottom: 20px;">タスク一覧</h2>
    <?php if (isset($tasks) && $tasks->count() > 0): ?>
      <?php foreach ($tasks as $task): ?>
        <div class="task-parent" style="margin-bottom: 20px; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
          <div class="task-item" style="background: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; <?= $task->completed ? 'opacity: 0.6;' : '' ?>">
            <div style="flex: 1;">
              <div style="display: flex; align-items: flex-start; gap: 10px;">
                <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $task->id]]) ?>
                <input type="checkbox" <?= $task->completed ? 'checked' : '' ?> onchange="this.form.submit()" style="width: 20px; height: 20px; cursor: pointer; margin-top: 5px;">
                <?= $this->Form->end() ?>

                <div id="display-container-<?= $task->id ?>" style="flex: 1;">
                  <span style="font-size: 18px; font-weight: bold; <?= $task->completed ? 'text-decoration: line-through;' : '' ?>"><?= h($task->title) ?></span>
                  <div style="font-size: 14px; color: #666; margin-top: 4px;"><?= nl2br(h($task->description)) ?></div>
                  <?php if ($task->due_date): ?>
                    <div style="font-size: 13px; color: #e91e63; margin-top: 4px;">期限: <?= $task->due_date->format('Y年m月d日') ?></div>
                  <?php endif; ?>
                </div>

                <div id="edit-form-<?= $task->id ?>" style="display: none; flex: 1; background: #fff; padding: 10px; border: 1px solid #2196F3; border-radius: 4px;">
                  <?= $this->Form->create(null, ['url' => ['action' => 'edit', $task->id]]) ?>
                  <?= $this->Form->control('title', ['label' => 'タスク名', 'value' => $task->title, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                  <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $task->description, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                  <?= $this->Form->control('due_date', ['label' => '期限', 'type' => 'date', 'value' => $task->due_date ? $task->due_date->format('Y-m-d') : '', 'style' => 'width: 100%; margin-bottom: 10px;']) ?>
                  <div style="display: flex; gap: 5px;">
                    <?= $this->Form->button('保存', ['style' => 'background: #4CAF50; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;']) ?>
                    <button type="button" onclick="toggleEdit(<?= $task->id ?>, false)" style="background: #999; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">戻る</button>
                  </div>
                  <?= $this->Form->end() ?>
                </div>

                <button id="edit-btn-<?= $task->id ?>" onclick="toggleEdit(<?= $task->id ?>, true)" style="background: none; border: none; color: #2196F3; cursor: pointer; font-size: 12px;">[編集]</button>
              </div>
            </div>
            <div style="display: flex; gap: 10px;">
              <button onclick="document.getElementById('subtask-form-<?= $task->id ?>').style.display='block'; this.style.display='none';" style="background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">サブタスク追加</button>
              <?= $this->Form->postLink('削除', ['action' => 'delete', $task->id], ['confirm' => '削除しますか？', 'style' => 'background: #f44336; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px;']) ?>
            </div>
          </div>

          <div id="subtask-form-<?= $task->id ?>" style="display: none; background: #f9f9f9; padding: 15px; border-top: 1px solid #ddd;">
            <?= $this->Form->create(null, ['url' => ['action' => 'addSubtask', $task->id]]) ?>
            <div style="display: flex; flex-direction: column; gap: 10px;">
              <?= $this->Form->control('title', ['label' => 'サブタスク名', 'required' => true, 'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;']) ?>
              <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'サブタスクの詳細...', 'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;']) ?>
              <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 1;">
                  <?= $this->Form->control('due_date', ['label' => '期限', 'type' => 'date', 'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;']) ?>
                </div>
                <div>
                  <?= $this->Form->button('追加', ['style' => 'background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;']) ?>
                  <button type="button" onclick="document.getElementById('subtask-form-<?= $task->id ?>').style.display='none'; document.querySelector('[onclick*=\'subtask-form-<?= $task->id ?>\']').style.display='inline-block';" style="background: #999; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">キャンセル</button>
                </div>
              </div>
            </div>
            <?= $this->Form->end() ?>
          </div>

          <?php if (!empty($task->child_tasks)): ?>
            <div style="background: #fafafa; padding: 10px 15px 10px 45px; border-top: 1px solid #e0e0e0;">
              <?php foreach ($task->child_tasks as $subtask): ?>
                <div class="subtask-item" style="background: white; border-left: 3px solid #2196F3; padding: 10px; margin-bottom: 8px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; <?= $subtask->completed ? 'opacity: 0.6;' : '' ?>">
                  <div style="flex: 1;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                      <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $subtask->id]]) ?>
                      <input type="checkbox" <?= $subtask->completed ? 'checked' : '' ?> onchange="this.form.submit()" style="width: 18px; height: 18px; cursor: pointer; margin-top: 3px;">
                      <?= $this->Form->end() ?>

                      <div id="display-container-<?= $subtask->id ?>" style="flex: 1;">
                        <span style="font-size: 15px; <?= $subtask->completed ? 'text-decoration: line-through;' : '' ?>"><?= h($subtask->title) ?></span>
                        <div style="font-size: 12px; color: #777; margin-top: 2px;"><?= nl2br(h($subtask->description)) ?></div>
                        <?php if ($subtask->due_date): ?>
                          <div style="font-size: 11px; color: #e91e63; margin-top: 2px;">期限: <?= $subtask->due_date->format('Y/m/d') ?></div>
                        <?php endif; ?>
                      </div>

                      <div id="edit-form-<?= $subtask->id ?>" style="display: none; flex: 1; background: #fff; padding: 8px; border: 1px solid #2196F3; border-radius: 4px;">
                        <?= $this->Form->create(null, ['url' => ['action' => 'edit', $subtask->id]]) ?>
                        <?= $this->Form->control('title', ['label' => 'サブタスク名', 'value' => $subtask->title, 'style' => 'width: 100%; font-size: 14px; margin-bottom: 5px;']) ?>
                        <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $subtask->description, 'style' => 'width: 100%; font-size: 13px; margin-bottom: 5px;']) ?>
                        <?= $this->Form->control('due_date', ['label' => '期限', 'type' => 'date', 'value' => $subtask->due_date ? $subtask->due_date->format('Y-m-d') : '', 'style' => 'width: 100%; font-size: 13px; margin-bottom: 8px;']) ?>
                        <div style="display: flex; gap: 5px;">
                          <?= $this->Form->button('保存', ['style' => 'background: #4CAF50; color: white; border: none; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;']) ?>
                          <button type="button" onclick="toggleEdit(<?= $subtask->id ?>, false)" style="background: #999; color: white; border: none; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">戻る</button>
                        </div>
                        <?= $this->Form->end() ?>
                      </div>

                      <button id="edit-btn-<?= $subtask->id ?>" onclick="toggleEdit(<?= $subtask->id ?>, true)" style="background: none; border: none; color: #2196F3; cursor: pointer; font-size: 11px;">[編集]</button>
                    </div>
                  </div>
                  <div>
                    <?= $this->Form->postLink('削除', ['action' => 'delete', $subtask->id], ['confirm' => '削除しますか？', 'style' => 'background: #f44336; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px;']) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center; color: #999; padding: 40px 0;">タスクがありません。</p>
    <?php endif; ?>
  </div>
</div>

<?= $this->Html->link('ログアウト', $this->Url->build(['controller' => 'Login', 'action' => 'logout'])) ?>

<script>
  function toggleEdit(id, isEdit) {
    const displayContainer = document.getElementById('display-container-' + id);
    const editFormDiv = document.getElementById('edit-form-' + id);
    const editBtn = document.getElementById('edit-btn-' + id);

    if (isEdit) {
      if (displayContainer) displayContainer.style.display = 'none';
      editBtn.style.display = 'none';
      editFormDiv.style.display = 'block';
    } else {
      if (displayContainer) displayContainer.style.display = 'block';
      editBtn.style.display = 'inline-block';
      editFormDiv.style.display = 'none';
    }
  }
</script>