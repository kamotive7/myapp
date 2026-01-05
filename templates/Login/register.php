<?php $this->assign('title', 'アカウント作成'); ?>

<style>
    .password-wrapper {
        position: relative;
        display: block;
        width: 100%;
    }

    .password-wrapper input[type="password"],
    .password-wrapper input[type="text"] {
        width: 100% !important;
        padding-right: 45px !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        cursor: pointer;
        font-size: 20px;
        padding: 5px;
        line-height: 1;
        z-index: 10;
    }

    .input.password {
        margin-bottom: 1rem;
    }
</style>

<h1>アカウント作成</h1>

<?= $this->Form->create(null, ['url' => ['controller' => 'Login', 'action' => 'create']]) ?>
<?= $this->Form->control('username', ['label' => 'ユーザー名', 'required' => true]) ?>
<?= $this->Form->control('name', ['label' => '名前', 'required' => true]) ?>

<div class="input password">
    <label for="password">パスワード</label>
    <div class="password-wrapper">
        <input type="password" name="password" id="password" required>
        <button type="button" onclick="togglePassword('password')" class="password-toggle">
            👁️
        </button>
    </div>
</div>

<div class="input password">
    <label for="password-confirm">パスワード（確認）</label>
    <div class="password-wrapper">
        <input type="password" name="password_confirm" id="password-confirm" required>
        <button type="button" onclick="togglePassword('password-confirm')" class="password-toggle">
            👁️
        </button>
    </div>
</div>
<?= $this->Form->button('登録') ?>
<?= $this->Form->end() ?>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>

<p>
    <?= $this->Html->link('ログイン画面に戻る', ['action' => 'index']) ?>
</p>