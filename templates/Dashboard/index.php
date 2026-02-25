<style>
  /* --- 1. 変数定義（変更なし） --- */
  :root {
    --bg-color: #f4f7f6;
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
    --grid-color: #dddddd;
    --text-muted: #666666;
  }

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
    --text-muted: #999999;
  }

  /* --- 2. 基盤スタイル（修正点：wrapperの背景をbg-colorに） --- */
  body {
    background-color: var(--bg-color);
    color: var(--text-color);
    transition: background-color 0.3s, color 0.3s;
  }

  /* 背景色の不一致を直すための重要修正：
     wrapper自体は背景色(bg-color)に。中身(カード)だけをcard-bgにする。 */
  .dashboard-wrapper {
    background-color: var(--bg-color) !important;
    color: var(--text-color) !important;
  }

  .tab-content,
  .task-item {
    background-color: var(--card-bg) !important;
    border: 1px solid var(--border-color) !important;
    color: var(--text-color) !important;
  }

  /* --- 3. ヘッダー・タブ（背景色を完全統一） --- */
  .dashboard-wrapper>div:first-child,
  .tab-navigation {
    background-color: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color) !important;
  }

  .tab-btn {
    background: transparent !important;
    /* ボタン自体の背景を消して透過させる */
    color: var(--text-muted) !important;
  }

  .tab-btn.active {
    color: var(--tab-active) !important;
    border-bottom: 3px solid var(--tab-active) !important;
  }

  /* --- 4. その他の要素（保持：削らずに残しています） --- */
  .task-form {
    background-color: var(--task-form-bg) !important;
    color: var(--text-color) !important;
  }

  [data-theme="dark"] .task-parent>div:last-child {
    background-color: var(--subtask-container-bg) !important;
  }

  .subtask-item {
    background-color: var(--card-bg) !important;
    color: var(--text-color) !important;
  }

  /* グラフ・カレンダーのコンテナ（背景から浮かせる） */
  [data-theme="dark"] #calendar-container,
  [data-theme="dark"] #gantt-container,
  [data-theme="dark"] .dashboard-wrapper>div>div {
    background-color: var(--card-bg) !important;
  }

  /* カレンダーセル */
  [data-theme="dark"] #calendar-container>div>div {
    background-color: var(--calendar-cell-bg) !important;
    color: var(--text-color) !important;
  }

  /* カレンダーヘッダー（曜日部分） */
  [data-theme="dark"] #calendar-container>div>div:nth-child(-n+7) {
    background-color: var(--calendar-header-bg) !important;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    color: var(--text-color) !important;
  }

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
  <button id="theme-toggle" style="padding: 8px 16px; cursor: pointer; border-radius: 20px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-color); display: inline-flex; align-items: center; justify-content: center; gap: 6px; line-height: 1;">
    <span>🌙</span> <span>ダークモード</span>
  </button>
</div>

<div class="dashboard-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; position: sticky; top: 0; background: inherit; color: var(--text-color); z-index: 100; padding: 10px 0; transition: background 0.3s;">
    <h1 style="margin: 0; font-size: 24px;">タスク管理システム</h1>
    <?= $this->Html->link('ログアウト', $this->Url->build(['controller' => 'Login', 'action' => 'logout']), ['style' => 'color: #f44336; text-decoration: none; font-size: 14px; white-space: nowrap;']) ?>
  </div>

  <div class="tab-navigation" style="border-bottom: 2px solid var(--border-color); margin-bottom: 25px; background: inherit; position: sticky; top: 60px; z-index: 99; padding-top: 5px; transition: background 0.3s;">
    <button class="tab-btn active" onclick="switchTab('dashboard')" style="padding: 10px 20px; border: none; cursor: pointer; border-bottom: 3px solid #2196F3; font-weight: bold; font-size: 15px; color: #2196F3; margin-right: 5px;">ダッシュボード</button>
    <button class="tab-btn" onclick="switchTab('list')" style="padding: 10px 20px; border: none; cursor: pointer; border-bottom: 3px solid transparent; font-size: 15px; color: var(--text-muted); margin-right: 5px;">タスクリスト</button>
    <button class="tab-btn" onclick="switchTab('calendar')" style="padding: 10px 20px; border: none; cursor: pointer; border-bottom: 3px solid transparent; font-size: 15px; color: var(--text-muted);">カレンダー</button>
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
      <div style="background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: background 0.3s ease;">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--text-color);">
          今後一か月のタスク<br>
          <span style="font-size: 14px; color: var(--text-muted); font-weight: normal;">（完了ステータス別）</span>
        </h3>
        <div style="position: relative; height: 300px; display: flex; align-items: center; justify-content: center;">
          <canvas id="doughnutChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- タスクリスト画面 -->
  <div id="list-view" class="tab-content" style="display: none;">
    <!-- 検索・フィルターエリア -->
    <div style="background: var(--card-bg); padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border-color);">
      <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index', '?' => ['tab' => 'list']]]) ?>
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
        <!-- 検索キーワード -->
        <div>
          <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--text-color);">検索</label>
          <input type="text" name="search" value="<?= h($searchKeyword) ?>" placeholder="タスク名・概要で検索..." style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--card-bg); color: var(--text-color);">
        </div>

        <!-- 完了状態フィルター -->
        <div>
          <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--text-color);">状態</label>
          <select name="completed" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--card-bg); color: var(--text-color);">
            <option value="">すべて</option>
            <option value="0" <?= $filterCompleted === '0' ? 'selected' : '' ?>>未完了</option>
            <option value="1" <?= $filterCompleted === '1' ? 'selected' : '' ?>>完了</option>
          </select>
        </div>

        <!-- 優先度フィルター -->
        <div>
          <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--text-color);">優先度</label>
          <select name="priority" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--card-bg); color: var(--text-color);">
            <option value="">すべて</option>
            <option value="high" <?= $filterPriority === 'high' ? 'selected' : '' ?>>高</option>
            <option value="medium" <?= $filterPriority === 'medium' ? 'selected' : '' ?>>中</option>
            <option value="low" <?= $filterPriority === 'low' ? 'selected' : '' ?>>低</option>
          </select>
        </div>

        <!-- ソート -->
        <div>
          <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--text-color);">並び順</label>
          <select name="sort" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--card-bg); color: var(--text-color);">
            <option value="end_date" <?= $sortBy === 'end_date' ? 'selected' : '' ?>>期限順</option>
            <option value="priority" <?= $sortBy === 'priority' ? 'selected' : '' ?>>優先度順</option>
            <option value="created" <?= $sortBy === 'created' ? 'selected' : '' ?>>作成日順</option>
            <option value="title" <?= $sortBy === 'title' ? 'selected' : '' ?>>タスク名順</option>
          </select>
        </div>

        <!-- 検索ボタン -->
        <div style="display: flex; gap: 5px;">
          <button type="submit" style="background: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; white-space: nowrap;">絞り込み</button>
          <a href="<?= $this->Url->build(['action' => 'index', '?' => ['tab' => 'list']]) ?>" style="background: var(--text-muted); color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">クリア</a>
        </div>
      </div>
      <input type="hidden" name="tab" value="list">
      <?= $this->Form->end() ?>

      <!-- 検索結果表示 -->
      <?php if (!empty($searchKeyword) || $filterCompleted !== '' || !empty($filterPriority)): ?>
        <div style="margin-top: 15px; padding: 10px; background: var(--task-form-bg); border-radius: 4px; font-size: 14px; color: var(--text-color);">
          <strong>絞り込み中:</strong>
          <?php if (!empty($searchKeyword)): ?>
            <span style="margin-left: 10px; padding: 3px 8px; background: #2196F3; color: white; border-radius: 12px; font-size: 12px;">検索: <?= h($searchKeyword) ?></span>
          <?php endif; ?>
          <?php if ($filterCompleted === '0'): ?>
            <span style="margin-left: 10px; padding: 3px 8px; background: #4CAF50; color: white; border-radius: 12px; font-size: 12px;">未完了のみ</span>
          <?php elseif ($filterCompleted === '1'): ?>
            <span style="margin-left: 10px; padding: 3px 8px; background: #9e9e9e; color: white; border-radius: 12px; font-size: 12px;">完了のみ</span>
          <?php endif; ?>
          <?php if (!empty($filterPriority)): ?>
            <?php 
              $priorityLabels = ['high' => '高', 'medium' => '中', 'low' => '低'];
              $priorityColors = ['high' => '#c62828', 'medium' => '#ef6c00', 'low' => '#2e7d32'];
            ?>
            <span style="margin-left: 10px; padding: 3px 8px; background: <?= $priorityColors[$filterPriority] ?>; color: white; border-radius: 12px; font-size: 12px;">優先度: <?= $priorityLabels[$filterPriority] ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div style="margin-bottom: 20px;">
      <button id="main-form-toggle-btn" onclick="toggleMainForm()" style="background: #4CAF50; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; line-height: 1;">
        <span id="main-form-icon">+</span> <span>新しいタスクを追加</span>
      </button>
    </div>

    <div id="main-task-form" class="task-form" style="display: none; background: var(--task-form-bg); padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid var(--border-color);">
      <?= $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Dashboard', 'action' => 'index']]) ?>
      <div style="margin-bottom: 15px;">
        <?= $this->Form->control('title', ['label' => 'タスク名', 'placeholder' => '新しいタスクを入力...', 'required' => true, 'style' => 'width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
      </div>
      <div style="margin-bottom: 15px;">
        <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'タスクの詳細を入力...', 'style' => 'width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
      </div>
      <div style="margin-bottom: 15px;">
        <?= $this->Form->control('priority', [
          'label' => '優先度',
          'type' => 'select',
          'options' => [
            'high' => '高',
            'medium' => '中',
            'low' => '低'
          ],
          'style' => 'width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;'
        ]) ?>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
        <div>
          <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'style' => 'width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
        </div>
        <div>
          <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'style' => 'width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;', 'empty' => true]) ?>
        </div>
      </div>
      <div style="display: flex; gap: 10px;">
        <?= $this->Form->button('タスクを追加', ['style' => 'background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;']) ?>
        <button type="button" onclick="toggleMainForm()" style="background: var(--text-muted); color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">キャンセル</button>
      </div>
      <?= $this->Form->end() ?>
    </div>

    <div class="tasks-list">
      <h2 style="margin-bottom: 20px;">タスク一覧</h2>
      <?php if (isset($tasks) && $tasks->count() > 0): ?>
        <?php foreach ($tasks as $task): ?>
          <div class="task-parent" style="margin-bottom: 20px; border: 2px solid var(--border-color); border-radius: 8px; overflow: hidden; background: var(--card-bg);">
            <div class="task-item" style="padding: 15px; display: flex; justify-content: space-between; align-items: center; <?= $task->completed ? 'opacity: 0.6;' : '' ?>">
              <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                <button onclick="toggleSubtasks(<?= $task->id ?>)" id="arrow-<?= $task->id ?>" style="background: none; border: none; color: var(--text-color); cursor: pointer; font-size: 18px; padding: 0; margin: 0; transition: transform 0.2s; line-height: 1;">▶</button>

                <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                  <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $task->id]]) ?>
                  <input type="checkbox" <?= $task->completed ? 'checked' : '' ?> onchange="this.form.submit()" style="width: 20px; height: 20px; cursor: pointer; margin: 0;">
                  <?= $this->Form->end() ?>

                  <div id="display-container-<?= $task->id ?>" style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                      <span style="font-size: 18px; font-weight: bold; <?= $task->completed ? 'text-decoration: line-through;' : '' ?>"><?= h($task->title) ?></span>
                      <?php
                        $priorityColors = [
                          'high' => ['bg' => '#ffebee', 'text' => '#c62828', 'label' => '高'],
                          'medium' => ['bg' => '#fff3e0', 'text' => '#ef6c00', 'label' => '中'],
                          'low' => ['bg' => '#e8f5e9', 'text' => '#2e7d32', 'label' => '低']
                        ];
                        $priority = $task->priority ?? 'medium';
                        $color = $priorityColors[$priority];
                      ?>
                      <span style="background: <?= $color['bg'] ?>; color: <?= $color['text'] ?>; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;"><?= $color['label'] ?></span>
                    </div>
                    <div style="font-size: 14px; color: var(--text-muted); margin-top: 4px;"><?= nl2br(h($task->description)) ?></div>
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

                  <div id="edit-form-<?= $task->id ?>" style="display: none; flex: 1; background: var(--card-bg); padding: 10px; border: 1px solid #2196F3; border-radius: 4px;">
                    <?= $this->Form->create(null, ['url' => ['action' => 'edit', $task->id]]) ?>
                    <?= $this->Form->control('title', ['label' => 'タスク名', 'value' => $task->title, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                    <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $task->description, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                    <?= $this->Form->control('priority', [
                      'label' => '優先度',
                      'type' => 'select',
                      'options' => ['high' => '高', 'medium' => '中', 'low' => '低'],
                      'value' => $task->priority ?? 'medium',
                      'style' => 'width: 100%; margin-bottom: 8px;'
                    ]) ?>
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
              <div style="display: flex; gap: 10px; align-items: center;">
                <button onclick="toggleSubtaskForm(<?= $task->id ?>)" id="subtask-add-btn-<?= $task->id ?>" style="background: #2196F3; color: white; padding: 0 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; height: 36px; white-space: nowrap; line-height: 1; box-sizing: border-box; margin: 0;">サブタスク追加</button>
                <?= $this->Form->postLink('削除', ['action' => 'delete', $task->id], ['confirm' => '削除しますか？', 'style' => 'background: #f44336; color: white; padding: 0 15px; border-radius: 4px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; height: 36px; white-space: nowrap; line-height: 1; box-sizing: border-box; margin: 0;']) ?>
              </div>
            </div>

            <div id="expanded-area-<?= $task->id ?>" style="display: none; border-top: 1px solid var(--border-color);">
              <div id="subtask-form-<?= $task->id ?>" style="display: none; background: var(--container-bg); padding: 15px; border-bottom: 1px solid var(--border-color);">
                <?= $this->Form->create(null, ['url' => ['action' => 'addSubtask', $task->id]]) ?>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                  <?= $this->Form->control('title', ['label' => 'サブタスク名', 'required' => true, 'style' => 'width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
                  <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'placeholder' => 'サブタスクの詳細...', 'style' => 'width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
                  <?= $this->Form->control('priority', [
                    'label' => '優先度',
                    'type' => 'select',
                    'options' => ['high' => '高', 'medium' => '中', 'low' => '低'],
                    'style' => 'width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;'
                  ]) ?>
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'style' => 'width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
                    <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'style' => 'width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;']) ?>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <?= $this->Form->button('追加', ['style' => 'background: #2196F3; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;']) ?>
                    <button type="button" onclick="toggleSubtaskForm(<?= $task->id ?>)" style="background: #999; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;">キャンセル</button>
                  </div>
                </div>
                <?= $this->Form->end() ?>
              </div>

              <?php if (!empty($task->child_tasks)): ?>
                <div id="subtask-list-<?= $task->id ?>" style="background: var(--subtask-container-bg); padding: 10px 15px 10px 45px;">
                  <?php foreach ($task->child_tasks as $subtask): ?>
                    <div class="subtask-item" style="background: var(--card-bg); border-left: 3px solid #2196F3; padding: 10px; margin-bottom: 8px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; <?= $subtask->completed ? 'opacity: 0.6;' : '' ?>">
                      <div style="flex: 1; display: flex; align-items: flex-start; gap: 10px;">
                        <?= $this->Form->create(null, ['url' => ['action' => 'toggleComplete', $subtask->id]]) ?>
                        <input type="checkbox" <?= $subtask->completed ? 'checked' : '' ?> onchange="this.form.submit()" style="width: 18px; height: 18px; cursor: pointer; margin-top: 3px;">
                        <?= $this->Form->end() ?>
                        
                        <div id="display-container-<?= $subtask->id ?>" style="flex: 1;">
                          <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                            <span style="font-size: 15px; <?= $subtask->completed ? 'text-decoration: line-through;' : '' ?>"><?= h($subtask->title) ?></span>
                            <?php
                              $priorityColors = [
                                'high' => ['bg' => '#ffebee', 'text' => '#c62828', 'label' => '高'],
                                'medium' => ['bg' => '#fff3e0', 'text' => '#ef6c00', 'label' => '中'],
                                'low' => ['bg' => '#e8f5e9', 'text' => '#2e7d32', 'label' => '低']
                              ];
                              $priority = $subtask->priority ?? 'medium';
                              $color = $priorityColors[$priority];
                            ?>
                            <span style="background: <?= $color['bg'] ?>; color: <?= $color['text'] ?>; padding: 1px 6px; border-radius: 10px; font-size: 10px; font-weight: bold;"><?= $color['label'] ?></span>
                          </div>
                          <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;"><?= nl2br(h($subtask->description)) ?></div>
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

                        <div id="edit-form-<?= $subtask->id ?>" style="display: none; flex: 1; background: var(--card-bg); padding: 10px; border: 1px solid #2196F3; border-radius: 4px;">
                          <?= $this->Form->create(null, ['url' => ['action' => 'edit', $subtask->id]]) ?>
                          <?= $this->Form->control('title', ['label' => 'サブタスク名', 'value' => $subtask->title, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                          <?= $this->Form->control('description', ['label' => '概要', 'type' => 'textarea', 'rows' => 2, 'value' => $subtask->description, 'style' => 'width: 100%; margin-bottom: 8px;']) ?>
                          <?= $this->Form->control('priority', [
                            'label' => '優先度',
                            'type' => 'select',
                            'options' => ['high' => '高', 'medium' => '中', 'low' => '低'],
                            'value' => $subtask->priority ?? 'medium',
                            'style' => 'width: 100%; margin-bottom: 8px;'
                          ]) ?>
                          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                            <?= $this->Form->control('start_date', ['label' => '開始日', 'type' => 'date', 'value' => $subtask->start_date ? $subtask->start_date->format('Y-m-d') : '', 'style' => 'width: 100%;']) ?>
                            <?= $this->Form->control('end_date', ['label' => '終了日（期限）', 'type' => 'date', 'value' => $subtask->end_date ? $subtask->end_date->format('Y-m-d') : '', 'style' => 'width: 100%;']) ?>
                          </div>
                          <div style="display: flex; gap: 5px;">
                            <?= $this->Form->button('保存', ['style' => 'background: #4CAF50; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;']) ?>
                            <button type="button" onclick="toggleEdit(<?= $subtask->id ?>, false)" style="background: #999; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">戻る</button>
                          </div>
                          <?= $this->Form->end() ?>
                        </div>

                        <button id="edit-btn-<?= $subtask->id ?>" onclick="toggleEdit(<?= $subtask->id ?>, true)" style="background: none; border: none; color: #2196F3; cursor: pointer; font-size: 11px;">[編集]</button>
                      </div>
                      <div style="display: flex; align-items: center;">
                        <?= $this->Form->postLink('削除', ['action' => 'delete', $subtask->id], ['confirm' => '削除しますか？', 'style' => 'background: #f44336; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; height: 32px; white-space: nowrap;']) ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center; color: var(--text-muted); padding: 40px 0;">タスクがありません。</p>
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
          <button onclick="changeMonth(-1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; height: 40px; white-space: nowrap;">前月</button>
          <h3 id="calendar-title" style="margin: 0;"></h3>
          <button onclick="changeMonth(1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; height: 40px; white-space: nowrap;">翌月</button>
        </div>
      </div>
      <div id="calendar-container"></div>
    </div>

    <!-- 週表示ガントチャート -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px;">週表示（ガントチャート）</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
          <button onclick="changeWeek(-1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; height: 40px; white-space: nowrap;">前週</button>
          <button onclick="goToToday()" style="background: #f5f5f5; color: #333; border: 1px solid #ddd; padding: 8px 16px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; height: 40px; white-space: nowrap;">今日</button>
          <button onclick="changeWeek(1)" style="background: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; height: 40px; white-space: nowrap;">翌週</button>
          <span id="week-title" style="margin-left: 15px; font-size: 14px; color: #666;"></span>
        </div>
      </div>
      <div id="gantt-container" style="overflow-x: auto;"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  // --- 1. グローバル変数の定義 ---
  let barChartInstance = null;
  let doughnutChartInstance = null;
  let currentDate = new Date();
  let currentWeekStart = new Date();
  // 初期状態で今週の日曜日に設定
  currentWeekStart.setDate(currentDate.getDate() - currentDate.getDay());

  // PHPデータの埋め込み
  const statistics = <?= json_encode($statistics) ?>;
  const calendarTasks = <?= json_encode($calendarTasks) ?>;
  const allTasksForGantt = <?= json_encode($ganttTasks) ?>;

  // --- 2. ヘルパー関数 ---

  // ダークモードに応じた色を取得
  function getThemeColors() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    return {
      isDark: isDark,
      text: isDark ? '#d4d4d4' : '#333',
      grid: isDark ? '#3e3e42' : '#dddddd',
      cardBg: isDark ? '#2d2d2d' : '#ffffff'
    };
  }

  // --- 3. グラフ描画セクション ---

  function renderCharts() {
    const theme = getThemeColors();
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barData = statistics.barChart;

    // 最大値からステップサイズを計算
    const maxValue = Math.max(barData.recentAssigned, barData.today, barData.nextWeek, barData.later);
    const stepSize = maxValue <= 10 ? 2 : maxValue <= 20 ? 4 : maxValue <= 50 ? 10 : 20;

    if (barChartInstance) barChartInstance.destroy();
    barChartInstance = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['最近の割り当て', '今日の作業', '来週の作業', 'あとにする'],
        datasets: [{
          label: 'タスク数',
          data: [barData.recentAssigned, barData.today, barData.nextWeek, barData.later],
          backgroundColor: [
            'rgba(102, 126, 234, 0.8)', 'rgba(245, 87, 108, 0.8)',
            'rgba(79, 172, 254, 0.8)', 'rgba(250, 112, 154, 0.8)'
          ],
          borderColor: [
            'rgb(102, 126, 234)', 'rgb(245, 87, 108)',
            'rgb(79, 172, 254)', 'rgb(250, 112, 154)'
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
            grid: {
              color: theme.grid
            },
            ticks: {
              color: theme.text,
              stepSize: stepSize
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: theme.text
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

    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    if (doughnutChartInstance) doughnutChartInstance.destroy();
    doughnutChartInstance = new Chart(doughnutCtx, {
      type: 'doughnut',
      data: {
        labels: ['完了', '未完了'],
        datasets: [{
          data: [statistics.nextMonthCompleted, statistics.nextMonthIncomplete],
          backgroundColor: ['rgba(76, 175, 80, 0.8)', 'rgba(33, 150, 243, 0.8)'],
          borderColor: ['rgb(76, 175, 80)', 'rgb(33, 150, 243)'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: theme.text
            }
          },
          tooltip: {
            callbacks: {
              label: (context) => context.label + ': ' + context.parsed + '件'
            }
          }
        },
        cutout: '60%'
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function(chart) {
          const {
            width,
            height,
            ctx
          } = chart;
          ctx.restore();
          const fontSize = (height / 200).toFixed(2);
          ctx.font = 'bold ' + fontSize + 'em sans-serif';
          ctx.textBaseline = 'middle';
          const text = statistics.nextMonthTotal + '件';
          ctx.fillStyle = theme.text;
          const textX = Math.round((width - ctx.measureText(text).width) / 2);
          ctx.fillText(text, textX, height / 2);
          ctx.save();
        }
      }]
    });
  }

  // --- 4. カレンダー描画セクション ---

  function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    document.getElementById('calendar-title').textContent = `${year}年${month + 1}月`;

    const theme = getThemeColors();
    const isDark = theme.isDark;

    const cellBg = isDark ? '#2d2d2d' : 'white';
    const headerBg = isDark ? '#252526' : '#f5f5f5';
    const textColor = theme.text;
    const borderColor = theme.grid;
    const taskBg = isDark ? '#4a7c9e' : '#bbdefb';
    const completedTaskBg = isDark ? '#5a5a5a' : '#e0e0e0';
    const todayBorder = isDark ? '#007acc' : '#2196F3';

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let html = `<div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: ${borderColor};">`;
    ['日', '月', '火', '水', '木', '金', '土'].forEach(day => {
      html += `<div style="background: ${headerBg}; padding: 10px; text-align: center; font-weight: bold; color: ${textColor};">${day}</div>`;
    });

    for (let i = 0; i < firstDay; i++) {
      html += `<div style="background: ${cellBg}; min-height: 120px; padding: 8px;"></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const tasksForDay = calendarTasks[dateStr] || [];
      const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();

      html += `<div style="background: ${cellBg}; min-height: 120px; padding: 8px; ${isToday ? `border: 2px solid ${todayBorder};` : ''}">`;
      html += `<div style="font-weight: bold; margin-bottom: 5px; color: ${isToday ? todayBorder : textColor}; font-size: 14px;">${day}</div>`;

      tasksForDay.forEach(task => {
        // 優先度による色設定
        const priorityColors = {
          high: { bg: isDark ? '#8b0000' : '#ffcdd2', border: '#c62828' },
          medium: { bg: isDark ? '#e65100' : '#ffe0b2', border: '#ef6c00' },
          low: { bg: isDark ? '#2e7d32' : '#c8e6c9', border: '#388e3c' }
        };
        const priority = task.priority || 'medium';
        const priorityColor = priorityColors[priority];
        
        const bgColor = task.completed ? completedTaskBg : priorityColor.bg;
        const borderLeft = task.completed ? completedTaskBg : priorityColor.border;
        const textDeco = task.completed ? 'line-through' : 'none';
        const taskTextColor = isDark ? '#e8e8e8' : '#1a1a1a';
        html += `<div style="background: ${bgColor}; border-left: 3px solid ${borderLeft}; padding: 4px 6px; margin-bottom: 3px; border-radius: 3px; font-size: 11px; text-decoration: ${textDeco}; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: ${taskTextColor}; font-weight: 500;" title="${task.title}">${task.title}</div>`;
      });
      html += '</div>';
    }
    html += '</div>';
    document.getElementById('calendar-container').innerHTML = html;
  }

  // --- 5. ガントチャート描画セクション ---

  function renderGanttChart() {
    const weekStart = new Date(currentWeekStart);
    weekStart.setHours(0, 0, 0, 0);
    const weekDays = [];
    for (let i = 0; i < 7; i++) {
      const day = new Date(weekStart);
      day.setDate(weekStart.getDate() + i);
      weekDays.push(day);
    }

    const weekEnd = new Date(weekDays[6]);
    document.getElementById('week-title').textContent = `${weekStart.getFullYear()}年${weekStart.getMonth() + 1}月${weekStart.getDate()}日 - ${weekEnd.getMonth() + 1}月${weekEnd.getDate()}日`;

    const theme = getThemeColors();
    const isDark = theme.isDark;
    const headerBg = isDark ? '#252526' : '#f5f5f5';
    const textColor = theme.text;
    const borderColor = theme.grid;
    const rowBg = isDark ? '#2d2d2d' : 'white';
    const todayBg = isDark ? '#1a3a4a' : '#e3f2fd';
    const todayTextColor = isDark ? '#4fc3f7' : '#2196F3';

    let html = '<div style="min-width: 900px;">';
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

    allTasksForGantt.forEach(task => {
      if (!task.start_date || !task.end_date) return;
      const tStart = new Date(task.start_date);
      const tEnd = new Date(task.end_date);
      tStart.setHours(0, 0, 0, 0);
      tEnd.setHours(0, 0, 0, 0);
      const wEnd = new Date(weekDays[6]);
      wEnd.setHours(23, 59, 59, 999);

      if (tEnd >= weekStart && tStart <= wEnd) {
        html += `<div style="display: grid; grid-template-columns: 200px repeat(7, 1fr); border-bottom: 1px solid ${borderColor}; min-height: 70px; position: relative; background: ${rowBg};">`;
        const compStyle = task.completed ? 'text-decoration: line-through; opacity: 0.6;' : '';
        const indent = task.type === 'child' ? 'padding-left: 28px;' : '';

        html += `<div style="padding: 12px; ${indent} border-right: 1px solid ${borderColor}; ${compStyle} display: flex; flex-direction: column; justify-content: center; color: ${textColor};">
                <div style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">${task.title}</div>
                <div style="font-size: 11px; color: ${isDark ? '#999' : '#666'};">${task.description || ''}</div>
                <div style="font-size: 10px; color: ${isDark ? '#888' : '#999'}; margin-top: 2px;">
                    ${tStart.toLocaleDateString('ja-JP', {month:'short', day:'numeric'})} - ${tEnd.toLocaleDateString('ja-JP', {month:'short', day:'numeric'})}
                </div>
            </div>`;

        html += '<div style="grid-column: 2 / 9; position: relative; display: grid; grid-template-columns: repeat(7, 1fr);">';
        weekDays.forEach((day) => {
          const isToday = day.toDateString() === new Date().toDateString();
          html += `<div style="border-right: 1px solid ${borderColor}; ${isToday ? `background: ${isDark ? '#263238' : '#f0f8ff'};` : ''}"></div>`;
        });

        // バー計算ロジック
        let barStartCol = 0;
        let barSpan = 0;
        weekDays.forEach((day, index) => {
          const dStart = new Date(day);
          dStart.setHours(0, 0, 0, 0);
          if (dStart.toDateString() === tStart.toDateString() || (tStart < weekStart && index === 0)) barStartCol = index;
          if (dStart >= tStart && dStart <= tEnd) barSpan++;
        });
        if (tStart < weekStart) barStartCol = 0;
        if (tEnd > wEnd) barSpan = 7 - barStartCol;

        // 優先度による色設定
        const priorityColors = {
          high: { parent: '#e53935', child: '#ef5350' },
          medium: { parent: '#fb8c00', child: '#ffa726' },
          low: { parent: '#43a047', child: '#66bb6a' }
        };
        const priority = task.priority || 'medium';
        const colorSet = priorityColors[priority];
        
        let barColor = task.completed ? '#9e9e9e' : (task.type === 'child' ? colorSet.child : colorSet.parent);
        const bRadiusL = tStart >= weekStart ? '16px' : '0';
        const bRadiusR = tEnd <= wEnd ? '16px' : '0';

        html += `<div style="position: absolute; top: 50%; transform: translateY(-50%); left: ${(barStartCol/7)*100}%; width: ${(barSpan/7)*100}%; padding: 0 4px;">
                <div style="background: ${barColor}; height: 36px; border-radius: ${bRadiusL} ${bRadiusR} ${bRadiusR} ${bRadiusL}; display: flex; align-items: center; padding: 0 12px; color: white; font-size: 12px; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    ${tStart >= weekStart ? task.title : ''}
                    ${tEnd <= wEnd ? '<span style="margin-left: auto;">●</span>' : ''}
                </div>
            </div></div></div>`;
      }
    });
    html += '</div>';
    document.getElementById('gantt-container').innerHTML = html;
  }

  // ★メインフォームの開閉制御
  function toggleMainForm() {
    const form = document.getElementById('main-task-form');
    const btn = document.getElementById('main-form-toggle-btn');
    const icon = document.getElementById('main-form-icon');

    if (form.style.display === 'none') {
      form.style.display = 'block';
      btn.style.background = '#999';
      icon.innerText = '×';
    } else {
      form.style.display = 'none';
      btn.style.background = '#4CAF50';
      icon.innerText = '+';
    }
  }

  // ★サブタスクフォームの開閉制御（新規追加）
  function toggleSubtaskForm(taskId) {
    const form = document.getElementById('subtask-form-' + taskId);
    const area = document.getElementById('expanded-area-' + taskId);
    const arrow = document.getElementById('arrow-' + taskId);
    
    // エリア自体が閉じている場合は開く
    if (area.style.display === 'none') {
      area.style.display = 'block';
      arrow.style.transform = 'rotate(90deg)';
    }
    
    // フォームの表示切り替え
    if (form.style.display === 'none') {
      form.style.display = 'block';
    } else {
      form.style.display = 'none';
    }
  }

  // ★子タスクエリアの展開制御（修正版）
  function toggleSubtasks(taskId) {
    const area = document.getElementById('expanded-area-' + taskId);
    const arrow = document.getElementById('arrow-' + taskId);
    const form = document.getElementById('subtask-form-' + taskId);

    if (area.style.display === 'none') {
      area.style.display = 'block';
      arrow.style.transform = 'rotate(90deg)';
    } else {
      area.style.display = 'none';
      arrow.style.transform = 'rotate(0deg)';
      // 閉じるときはフォームも閉じる
      if (form) {
        form.style.display = 'none';
      }
    }
  }

  // 編集用関数（修正版 - サブタスクのバグ修正）
  function toggleEdit(id, show) {
    const displayContainer = document.getElementById('display-container-' + id);
    const editForm = document.getElementById('edit-form-' + id);
    const editBtn = document.getElementById('edit-btn-' + id);
    
    if (show) {
      // 編集モードに切り替え
      if (displayContainer) displayContainer.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (editForm) editForm.style.display = 'block';
    } else {
      // 表示モードに戻す
      if (displayContainer) displayContainer.style.display = 'block';
      if (editBtn) editBtn.style.display = 'inline-block';
      if (editForm) editForm.style.display = 'none';
    }
  }

  // --- 6. タブ・テーマ・初期化 ---

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

    const target = document.getElementById(tabName + '-view');
    if (target) target.style.display = 'block';

    // クリックイベント経由の場合のボタン装飾
    if (event && event.target && event.target.classList.contains('tab-btn')) {
      event.target.classList.add('active');
      event.target.style.borderBottomColor = '#2196F3';
      event.target.style.fontWeight = 'bold';
      event.target.style.color = '#2196F3';
    }

    if (tabName === 'dashboard') {
      renderCharts();
    } else if (tabName === 'calendar') {
      renderCalendar();
      renderGanttChart();
    }
  }

  const toggleBtn = document.getElementById('theme-toggle');
  toggleBtn.addEventListener('click', () => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    if (isDark) {
      document.documentElement.removeAttribute('data-theme');
      toggleBtn.innerHTML = '<span>🌙</span> <span>ダークモード</span>';
      localStorage.setItem('theme', 'light');
    } else {
      document.documentElement.setAttribute('data-theme', 'dark');
      toggleBtn.innerHTML = '<span>☀️</span> <span>ライトモード</span>';
      localStorage.setItem('theme', 'dark');
    }

    // 現在の表示内容を更新
    renderCharts();
    if (document.getElementById('calendar-view').style.display !== 'none') {
      renderCalendar();
      renderGanttChart();
    }
  });

  // 初期化
  document.addEventListener('DOMContentLoaded', () => {
    // テーマ復元
    if (localStorage.getItem('theme') === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
      toggleBtn.innerHTML = '<span>☀️</span> <span>ライトモード</span>';
    }

    // URLパラメータによる初期タブ設定
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam && document.getElementById(tabParam + '-view')) {
      switchTab(tabParam);
      // URL経由の場合のボタンのアクティブ化
      document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.getAttribute('onclick').includes(`'${tabParam}'`)) {
          btn.classList.add('active');
          btn.style.borderBottomColor = '#2196F3';
          btn.style.color = '#2196F3';
          btn.style.fontWeight = 'bold';
        }
      });
    } else {
      renderCharts();
    }
  });

  function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
  }

  function changeWeek(dir) {
    currentWeekStart.setDate(currentWeekStart.getDate() + (dir * 7));
    renderGanttChart();
  }

  function goToToday() {
    const today = new Date();
    currentWeekStart = new Date(today);
    currentWeekStart.setDate(today.getDate() - today.getDay());
    renderGanttChart();
  }
</script>