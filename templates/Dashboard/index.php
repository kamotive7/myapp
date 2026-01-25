<style>
  /* デフォルト（ライトモード） */
  :root {
    --bg-color: #ffffff;
    --container-bg: #f5f5f5;
    --card-bg: #ffffff;
    --text-color: #333333;
    --border-color: #dddddd;
    --tab-active: #2196F3;
    --header-bg: #ffffff;
    --calendar-cell-bg: #ffffff;
    --calendar-header-bg: #f5f5f5;
    --task-form-bg: #f5f5f5;
    --subtask-container-bg: #fafafa;
  }

  /* ダークモード（VSCode風） */
  [data-theme="dark"] {
    --bg-color: #1e1e1e;
    --container-bg: #252526;
    --card-bg: #2d2d2d;
    --text-color: #d4d4d4;
    --border-color: #3e3e42;
    --tab-active: #007acc;
    --header-bg: #1e1e1e;
    --calendar-cell-bg: #2d2d2d;
    --calendar-header-bg: #252526;
    --task-form-bg: #252526;
    --subtask-container-bg: #252526;
  }

  /* 全体のスタイルに変数を使用 */
  body {
    background-color: var(--bg-color);
    color: var(--text-color);
    transition: background-color 0.3s, color 0.3s;
  }

  .dashboard-wrapper,
  .tab-content,
  .task-item {
    background-color: var(--card-bg) !important;
    border-color: var(--border-color) !important;
    color: var(--text-color) !important;
  }

  /* ヘッダー（タスク管理システムの部分） */
  .dashboard-wrapper>div:first-child {
    background-color: var(--header-bg) !important;
    color: var(--text-color) !important;
  }

  /* タブナビゲーション */
  .tab-navigation {
    background-color: var(--header-bg) !important;
    border-bottom-color: var(--border-color) !important;
  }

  /* タブボタン */
  .tab-btn {
    background-color: var(--header-bg) !important;
    color: var(--text-color) !important;
  }

  .tab-btn.active {
    color: var(--tab-active) !important;
    border-bottom-color: var(--tab-active) !important;
  }

  /* タスクフォーム */
  .task-form {
    background-color: var(--task-form-bg) !important;
    color: var(--text-color) !important;
  }

  /* サブタスクコンテナ */
  [data-theme="dark"] .task-parent>div:last-child {
    background-color: var(--subtask-container-bg) !important;
  }

  /* サブタスクアイテム */
  .subtask-item {
    background-color: var(--card-bg) !important;
    color: var(--text-color) !important;
  }

  /* グラフ・カレンダーの背景 */
  [data-theme="dark"] .dashboard-wrapper>div>div,
  [data-theme="dark"] #calendar-container>div,
  [data-theme="dark"] #gantt-container>div {
    background-color: var(--card-bg) !important;
  }

  /* カレンダーセル */
  [data-theme="dark"] #calendar-container>div>div {
    background-color: var(--calendar-cell-bg) !important;
    color: var(--text-color) !important;
  }

  /* グラフ・カレンダーの背景 */
  [data-theme="dark"] .dashboard-wrapper>div>div {
    background-color: var(--card-bg) !important;
  }

  /* カレンダーヘッダー */
  [data-theme="dark"] #calendar-container>div>div:first-child {
    background-color: var(--container-bg) !important;
  }


  /* h1, h2, h3 などの見出し */
  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    color: var(--text-color) !important;
  }

  /* 入力フォーム */
  [data-theme="dark"] input,
  [data-theme="dark"] textarea,
  [data-theme="dark"] select {
    background-color: var(--card-bg) !important;
    color: var(--text-color) !important;
    border-color: var(--border-color) !important;
  }
</style>

<h2>
  <?= $today->format('n月j日') ?>
  （<?= ['日', '月', '火', '水', '木', '金', '土'][$today->dayOfWeek % 7] ?>）
  <?= h($user['name']) ?>さん、こんにちは
</h2>

<div style="text-align: right; padding: 10px;">
  <button id="theme-toggle" style="padding: 5px 15px; cursor: pointer; border-radius: 20px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-color);">
    🌙 ダークモード
  </button>
</div>

<div class="dashboard-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; position: sticky; top: 0; background: white; z-index: 100; padding: 10px 0;">
    <h1 style="margin: 0; font-size: 24px;">タスク管理システム</h1>
    <?= $this->Html->link('ログアウト', $this->Url->build(['controller' => 'Login', 'action' => 'logout']), ['style' => 'color: #f44336; text-decoration: none; font-size: 14px; white-space: nowrap;']) ?>
  </div>

  <!-- タブナビゲーション -->
  <div class="tab-navigation" style="border-bottom: 2px solid #ddd; margin-bottom: 25px; background: white; position: sticky; top: 60px; z-index: 99; padding-top: 5px;">
    <button class="tab-btn active" onclick="switchTab('dashboard')" style="padding: 10px 20px; border: none; background: white; cursor: pointer; border-bottom: 3px solid #2196F3; font-weight: bold; font-size: 15px; color: #2196F3; margin-right: 5px;">ダッシュボード</button>
    <button class="tab-btn" onclick="switchTab('list')" style="padding: 10px 20px; border: none; background: white; cursor: pointer; border-bottom: 3px solid transparent; font-size: 15px; color: #666; margin-right: 5px;">タスクリスト</button>
    <button class="tab-btn" onclick="switchTab('calendar')" style="padding: 10px 20px; border: none; background: white; cursor: pointer; border-bottom: 3px solid transparent; font-size: 15px; color: #666;">カレンダー</button>
  </div>

  <!-- ダッシュボード画面 -->
  <div id="dashboard-view" class="tab-content">
    <!-- 統計カード -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
      <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">タスク総数</div>
        <div style="font-size: 36px; font-weight: bold;"><?= $statistics['total'] ?></div>
      </div>
      <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">完了</div>
        <div style="font-size: 36px; font-weight: bold;"><?= $statistics['completed'] ?></div>
      </div>
      <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">未完了</div>
        <div style="font-size: 36px; font-weight: bold;"><?= $statistics['incomplete'] ?></div>
      </div>
      <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">期限超過</div>
        <div style="font-size: 36px; font-weight: bold;"><?= $statistics['overdue'] ?></div>
      </div>
    </div>

    <!-- グラフエリア -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 40px;">
      <!-- 棒グラフ -->
      <div style="background: var(--card-bg) !important; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: #333;">タスクの分類</h3>
        <div style="position: relative; height: 300px;">
          <canvas id="barChart"></canvas>
        </div>
      </div>

      <!-- 円グラフ -->
      <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: #333;">今後一か月のタスク<br><span style="font-size: 14px; color: #666; font-weight: normal;">（完了ステータス別）</span></h3>
        <div style="position: relative; height: 300px; display: flex; align-items: center; justify-content: center;">
          <canvas id="doughnutChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- タスクリスト画面 -->
  <div id="list-view" class="tab-content" style="display: none;">
    <div class="task-form" style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
      <?= $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Dashboard', 'action' => 'index']]) ?>
      <div style="margin-bottom: 15px;">
        <?= $this->Form->control('title', ['label' => 'タスク名', 'placeholder' => '新しいタスクを入力...', 'required' => true, 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
      </div>
      <div style="margin-bottom: 15px;">
        <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'タスクの詳細を入力...', 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
        <div>
          <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;']) ?>
        </div>
        <div>
          <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'style' => 'width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;', 'empty' => true]) ?>
        </div>
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
                    <?php if ($task->start_date || $task->end_date): ?>
                      <div style="font-size: 13px; margin-top: 4px; display: flex; gap: 10px;">
                        <?php if ($task->start_date): ?>
                          <span style="color: #4CAF50;">開始: <?= $task->start_date->format('Y/m/d') ?></span>
                        <?php endif; ?>
                        <?php if ($task->end_date): ?>
                          <span style="color: #e91e63;">期限: <?= $task->end_date->format('Y/m/d') ?></span>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div id="edit-form-<?= $task->id ?>" style="display: none; flex: 1; background: #fff; padding: 10px; border: 1px solid #2196F3; border-radius: 4px;">
                    <?= $this->Form->create(null, ['url' => ['action' => 'edit', $task->id]]) ?>
                    <?= $this->Form->control('title', ['label' => 'タスク名', 'value' => $task->title, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                    <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $task->description, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                      <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'value' => $task->start_date ? $task->start_date->format('Y-m-d') : '', 'style' => 'width: 100%;']) ?>
                      <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'value' => $task->end_date ? $task->end_date->format('Y-m-d') : '', 'style' => 'width: 100%;']) ?>
                    </div>
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
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                  <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;']) ?>
                  <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'style' => 'width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;']) ?>
                </div>
                <div style="display: flex; gap: 10px;">
                  <?= $this->Form->button('追加', ['style' => 'background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;']) ?>
                  <button type="button" onclick="document.getElementById('subtask-form-<?= $task->id ?>').style.display='none'; document.querySelector('[onclick*=\'subtask-form-<?= $task->id ?>\']').style.display='inline-block';" style="background: #999; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;">キャンセル</button>
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
                          <?php if ($subtask->start_date || $subtask->end_date): ?>
                            <div style="font-size: 11px; margin-top: 2px; display: flex; gap: 8px;">
                              <?php if ($subtask->start_date): ?>
                                <span style="color: #4CAF50;">開始: <?= $subtask->start_date->format('Y/m/d') ?></span>
                              <?php endif; ?>
                              <?php if ($subtask->end_date): ?>
                                <span style="color: #e91e63;">期限: <?= $subtask->end_date->format('Y/m/d') ?></span>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>
                        </div>

                        <div id="edit-form-<?= $subtask->id ?>" style="display: none; flex: 1; background: #fff; padding: 8px; border: 1px solid #2196F3; border-radius: 4px;">
                          <?= $this->Form->create(null, ['url' => ['action' => 'edit', $subtask->id]]) ?>
                          <?= $this->Form->control('title', ['label' => 'サブタスク名', 'value' => $subtask->title, 'style' => 'width: 100%; font-size: 14px; margin-bottom: 5px;']) ?>
                          <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $subtask->description, 'style' => 'width: 100%; font-size: 13px; margin-bottom: 5px;']) ?>
                          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; margin-bottom: 8px;">
                            <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'value' => $subtask->start_date ? $subtask->start_date->format('Y-m-d') : '', 'style' => 'width: 100%; font-size: 13px;']) ?>
                            <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'value' => $subtask->end_date ? $subtask->end_date->format('Y-m-d') : '', 'style' => 'width: 100%; font-size: 13px;']) ?>
                          </div>
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

  <!-- カレンダー画面 -->
  <div id="calendar-view" class="tab-content" style="display: none;">
    <!-- 月表示カレンダー -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px;">月表示</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
          <button onclick="changeMonth(-1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">前月</button>
          <h3 id="calendar-title" style="margin: 0;"></h3>
          <button onclick="changeMonth(1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">翌月</button>
        </div>
      </div>
      <div id="calendar-container"></div>
    </div>

    <!-- 週表示ガントチャート -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px;">週表示（ガントチャート）</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
          <button onclick="changeWeek(-1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">前週</button>
          <button onclick="goToToday()" style="background: #f5f5f5; color: #333; border: 1px solid #ddd; padding: 8px 16px; border-radius: 4px; cursor: pointer;">今日</button>
          <button onclick="changeWeek(1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">翌週</button>
          <span id="week-title" style="margin-left: 15px; font-size: 14px; color: #666;"></span>
        </div>
      </div>
      <div id="gantt-container" style="overflow-x: auto;"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  const toggleBtn = document.getElementById('theme-toggle');
  const htmlElement = document.documentElement;

  toggleBtn.addEventListener('click', () => {
    if (htmlElement.getAttribute('data-theme') === 'dark') {
      htmlElement.removeAttribute('data-theme');
      toggleBtn.innerText = '🌙 ダークモード';
      localStorage.setItem('theme', 'light'); // 設定を保存
    } else {
      htmlElement.setAttribute('data-theme', 'dark');
      toggleBtn.innerText = '☀️ ライトモード';
      localStorage.setItem('theme', 'dark'); // 設定を保存
    }
  });

  // カレンダーの再描画
  if (document.getElementById('calendar-view').style.display !== 'none') {
    renderCalendar();
    renderGanttChart();
  }

  // ページ読み込み時に保存された設定を反映
  if (localStorage.getItem('theme') === 'dark') {
    htmlElement.setAttribute('data-theme', 'dark');
    toggleBtn.innerText = '☀️ ライトモード';
  }

  // ページ読み込み時の初期表示設定
  document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');

    //tabパラメータがあればそのタブを、なければ現在のactive（ダッシュボード）を表示
    if (tabParam && document.getElementById(tabParam + '-view')) {
      // いったんすべてのタブを非表示に
      const tabs = document.querySelectorAll('.tab-content');
      const buttons = document.querySelectorAll('.tab-btn');
      tabs.forEach(tab => tab.style.display = 'none');
      buttons.forEach(btn => {
        btn.classList.remove('active');
        btn.style.borderBottomColor = 'transparent';
        btn.style.color = '#666';
        btn.style.fontWeight = 'normal';
      });

      //対象のタブを表示
      document.getElementById(tabParam + '-view').style.display = 'block';
      //対応するボタンを探してアクティブにする
      buttons.forEach(btn => {
        if (btn.getAttribute('onclick').includes(`'${tabParam}'`)) {
          btn.classList.add('active');
          btn.style.borderBottomColor = '#2196F3';
          btn.style.color = '#2196F3';
          btn.style.fontWeight = 'bold';
        }
      });

      //カレンダー表示が必要な場合
      if (tabParam === 'calendar') {
        renderCalendar();
        renderGanttChart();
      }
    }
  });

  // タブ切り替え
  function switchTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-btn');

    tabs.forEach(tab => tab.style.display = 'none');
    buttons.forEach(btn => {
      btn.classList.remove('active');
      btn.style.borderBottomColor = 'transparent';
      btn.style.fontWeight = 'normal';
      btn.style.color = '#666';
    });

    document.getElementById(tabName + '-view').style.display = 'block';

    // イベントが発生したボタンをアクティブにする
    if (event && event.target) {
      event.target.classList.add('active');
      event.target.style.borderBottomColor = '#2196F3';
      event.target.style.fontWeight = 'bold';
      event.target.style.color = '#2196F3';
    }

    // --- ここからが重要：タブ切り替え時にグラフを更新する ---
    if (tabName === 'dashboard') {
      // ダッシュボードが表示された瞬間にChart.jsにサイズを再計算させる
      if (typeof barChartInstance !== 'undefined') barChartInstance.resize();
      if (typeof doughnutChartInstance !== 'undefined') doughnutChartInstance.resize();
    }

    if (tabName === 'calendar') {
      renderCalendar();
      const today = new Date();
      currentWeekStart = new Date(today);
      currentWeekStart.setDate(today.getDate() - today.getDay());
      renderGanttChart();
    }
  }

  // タスク編集トグル
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

  // 統計データ
  const statistics = <?= json_encode($statistics) ?>;

  // 棒グラフ
  const barCtx = document.getElementById('barChart').getContext('2d');
  const barData = statistics.barChart;
  const maxValue = Math.max(barData.recentAssigned, barData.today, barData.nextWeek, barData.later);
  const stepSize = maxValue <= 10 ? 2 : maxValue <= 20 ? 4 : maxValue <= 50 ? 10 : 20;

  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['最近の割り当て', '今日の作業', '来週の作業', 'あとにする'],
      datasets: [{
        label: 'タスク数',
        data: [barData.recentAssigned, barData.today, barData.nextWeek, barData.later],
        backgroundColor: [
          'rgba(102, 126, 234, 0.8)',
          'rgba(245, 87, 108, 0.8)',
          'rgba(79, 172, 254, 0.8)',
          'rgba(250, 112, 154, 0.8)'
        ],
        borderColor: [
          'rgb(102, 126, 234)',
          'rgb(245, 87, 108)',
          'rgb(79, 172, 254)',
          'rgb(250, 112, 154)'
        ],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: stepSize
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });

  // ドーナツグラフ
  const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
  new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['完了', '未完了'],
      datasets: [{
        data: [statistics.nextMonthCompleted, statistics.nextMonthIncomplete],
        backgroundColor: [
          'rgba(76, 175, 80, 0.8)',
          'rgba(33, 150, 243, 0.8)'
        ],
        borderColor: [
          'rgb(76, 175, 80)',
          'rgb(33, 150, 243)'
        ],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.label + ': ' + context.parsed + '件';
            }
          }
        }
      },
      cutout: '60%'
    },
    plugins: [{
      id: 'centerText',
      beforeDraw: function(chart) {
        const width = chart.width;
        const height = chart.height;
        const ctx = chart.ctx;
        ctx.restore();
        const fontSize = (height / 200).toFixed(2);
        ctx.font = 'bold ' + fontSize + 'em sans-serif';
        ctx.textBaseline = 'middle';
        const text = statistics.nextMonthTotal + '件';
        const textX = Math.round((width - ctx.measureText(text).width) / 2);
        const textY = height / 2;
        ctx.fillStyle = '#333';
        ctx.fillText(text, textX, textY);
        ctx.save();
      }
    }]
  });

  // カレンダー機能
  const calendarTasks = <?= json_encode($calendarTasks) ?>;
  const allTasksForGantt = <?= json_encode($ganttTasks) ?>;
  let currentDate = new Date();
  let currentWeekStart = new Date();

  function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById('calendar-title').textContent = `${year}年${month + 1}月`;

    // ダークモード判定 
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const cellBg = isDark ? '#2d2d2d' : 'white';
    const headerBg = isDark ? '#252526' : '#f5f5f5';
    const textColor = isDark ? '#d4d4d4' : '#333';
    const borderColor = isDark ? '#3e3e42' : '#ddd';
    const taskBg = isDark ? '#4a7c9e' : '#bbdefb'; // ★ダークモード時を濃い青に変更
    const completedTaskBg = isDark ? '#5a5a5a' : '#e0e0e0'; // ★ダークモード時を少し明るく
    const todayBorder = isDark ? '#007acc' : '#2196F3';

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // ★バッククォートに修正
    let html = `<div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: ${borderColor};">`;

    // 曜日ヘッダー
    const days = ['日', '月', '火', '水', '木', '金', '土'];
    days.forEach(day => {
      html += `<div style="background: ${headerBg}; padding: 10px; text-align: center; font-weight: bold; color: ${textColor};">${day}</div>`;
    });

    // 空白セル
    for (let i = 0; i < firstDay; i++) {
      html += `<div style="background: ${cellBg}; min-height: 120px; padding: 8px;"></div>`;
    }

    // 日付セル
    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const tasksForDay = calendarTasks[dateStr] || [];
      const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();

      html += `<div style="background: ${cellBg}; min-height: 120px; padding: 8px; ${isToday ? `border: 2px solid ${todayBorder};` : ''}">`;
      html += `<div style="font-weight: bold; margin-bottom: 5px; color: ${isToday ? todayBorder : textColor}; font-size: 14px;">${day}</div>`; // ★フォントサイズ追加

      tasksForDay.forEach(task => {
        const bgColor = task.completed ? completedTaskBg : taskBg;
        const textDecoration = task.completed ? 'line-through' : 'none';
        const taskTextColor = isDark ? '#e8e8e8' : '#1a1a1a'; // ★より明るく/暗く
        html += `<div style="background: ${bgColor}; padding: 4px 6px; margin-bottom: 3px; border-radius: 3px; font-size: 11px; text-decoration: ${textDecoration}; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: ${taskTextColor}; font-weight: 500;" title="${task.title}">${task.title}</div>`; // ★font-weight追加
      });

      html += '</div>';
    }

    html += '</div>';
    document.getElementById('calendar-container').innerHTML = html;
  }

  function renderGanttChart() {
    const weekStart = new Date(currentWeekStart);
    weekStart.setHours(0, 0, 0, 0);
    const weekDays = [];

    // 週の日付を生成（日曜日から土曜日）
    for (let i = 0; i < 7; i++) {
      const day = new Date(weekStart);
      day.setDate(weekStart.getDate() + i);
      weekDays.push(day);
    }

    // 週のタイトル更新
    const weekEnd = new Date(weekDays[6]);
    document.getElementById('week-title').textContent =
      `${weekStart.getFullYear()}年${weekStart.getMonth() + 1}月${weekStart.getDate()}日 - ${weekEnd.getMonth() + 1}月${weekEnd.getDate()}日`;

    // ★ダークモード判定追加
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const headerBg = isDark ? '#252526' : '#f5f5f5';
    const textColor = isDark ? '#d4d4d4' : '#333';
    const borderColor = isDark ? '#3e3e42' : '#ddd';
    const rowBg = isDark ? '#2d2d2d' : 'white';
    const todayBg = isDark ? '#1a3a4a' : '#e3f2fd';
    const todayTextColor = isDark ? '#4fc3f7' : '#2196F3';

    let html = '<div style="min-width: 900px;">';

    // ヘッダー（曜日と日付）
    html += `<div style="display: grid; grid-template-columns: 200px repeat(7, 1fr); border-bottom: 2px solid ${borderColor}; background: ${headerBg}; position: sticky; top: 0; z-index: 10;">`;
    html += `<div style="padding: 12px; font-weight: bold; border-right: 1px solid ${borderColor}; color: ${textColor};">タスク名</div>`;

    const dayNames = ['日', '月', '火', '水', '木', '金', '土'];
    weekDays.forEach((day, index) => {
      const isToday = day.toDateString() === new Date().toDateString();
      const isWeekend = index === 0 || index === 6;
      const weekendColor = isDark ? '#888' : '#999';
      html += `<div style="padding: 12px; text-align: center; font-weight: bold; border-right: 1px solid ${borderColor}; ${isToday ? `background: ${todayBg}; color: ${todayTextColor};` : `color: ${isWeekend ? weekendColor : textColor};`}">
      <div style="font-size: 12px;">${dayNames[index]}</div>
      <div style="font-size: 16px; margin-top: 2px;">${day.getDate()}</div>
    </div>`;
    });
    html += '</div>';

    // タスク行
    allTasksForGantt.forEach(task => {
      if (!task.start_date || !task.end_date) return;

      const taskStartDate = new Date(task.start_date);
      taskStartDate.setHours(0, 0, 0, 0);

      const taskEndDate = new Date(task.end_date);
      taskEndDate.setHours(0, 0, 0, 0);

      const weekEndDate = new Date(weekDays[6]);
      weekEndDate.setHours(23, 59, 59, 999);

      if (taskEndDate >= weekStart && taskStartDate <= weekEndDate) {
        html += `<div style="display: grid; grid-template-columns: 200px repeat(7, 1fr); border-bottom: 1px solid ${borderColor}; min-height: 70px; position: relative; background: ${rowBg};">`;

        const completedStyle = task.completed ? 'text-decoration: line-through; opacity: 0.6;' : '';
        const indent = task.type === 'child' ? 'padding-left: 28px;' : '';
        const descColor = isDark ? '#999' : '#666';
        const dateColor = isDark ? '#888' : '#999';

        html += `<div style="padding: 12px; ${indent} border-right: 1px solid ${borderColor}; ${completedStyle} display: flex; flex-direction: column; justify-content: center; color: ${textColor};">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">${task.title}</div>
        <div style="font-size: 11px; color: ${descColor};">${task.description || ''}</div>
        <div style="font-size: 10px; color: ${dateColor}; margin-top: 2px;">
          ${new Date(task.start_date).toLocaleDateString('ja-JP', {month: 'short', day: 'numeric'})} - 
          ${new Date(task.end_date).toLocaleDateString('ja-JP', {month: 'short', day: 'numeric'})}
        </div>
      </div>`;

        html += '<div style="grid-column: 2 / 9; position: relative; display: grid; grid-template-columns: repeat(7, 1fr);">';

        weekDays.forEach((day) => {
          const isToday = day.toDateString() === new Date().toDateString();
          const cellTodayBg = isDark ? '#263238' : '#f0f8ff';
          html += `<div style="border-right: 1px solid ${borderColor}; ${isToday ? `background: ${cellTodayBg};` : ''}"></div>`;
        });

        let barStartCol = 0;
        let barSpan = 0;

        weekDays.forEach((day, index) => {
          const dayStart = new Date(day);
          dayStart.setHours(0, 0, 0, 0);

          if (dayStart.toDateString() === taskStartDate.toDateString() ||
            (taskStartDate < weekStart && index === 0)) {
            barStartCol = index;
          }

          if (dayStart >= taskStartDate && dayStart <= taskEndDate) {
            barSpan++;
          }
        });

        if (taskStartDate < weekStart) {
          barStartCol = 0;
        }

        if (taskEndDate > weekEndDate) {
          barSpan = 7 - barStartCol;
        }

        let barColor = '#42a5f5';

        if (task.type === 'child') {
          barColor = '#90caf9';
        }
        if (task.completed) {
          barColor = '#9e9e9e';
        }

        const isStartInWeek = taskStartDate >= weekStart;
        const isEndInWeek = taskEndDate <= weekEndDate;
        const borderRadiusLeft = isStartInWeek ? '16px' : '0';
        const borderRadiusRight = isEndInWeek ? '16px' : '0';

        const leftPercent = (barStartCol / 7) * 100;
        const widthPercent = (barSpan / 7) * 100;

        html += `<div style="position: absolute; top: 50%; transform: translateY(-50%); left: ${leftPercent}%; width: ${widthPercent}%; padding: 0 4px;">
        <div style="background: ${barColor}; height: 36px; border-radius: ${borderRadiusLeft} ${borderRadiusRight} ${borderRadiusRight} ${borderRadiusLeft}; display: flex; align-items: center; padding: 0 12px; color: white; font-size: 12px; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
          ${isStartInWeek ? task.title : ''}
          ${isEndInWeek ? '<span style="margin-left: auto;">●</span>' : ''}
        </div>
      </div>`;

        html += '</div>';
        html += '</div>';
      }
    });

    html += '</div>';
    document.getElementById('gantt-container').innerHTML = html;
  }

  function changeMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    renderCalendar();
  }

  function changeWeek(direction) {
    currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
    renderGanttChart();
  }

  function goToToday() {
    const today = new Date();
    currentWeekStart = new Date(today);
    currentWeekStart.setDate(today.getDate() - today.getDay()); // 日曜日に設定
    renderGanttChart();
  }
</script>