<h1>Login</h1>

<?= $this->Form->create(null, ['url' => ['controller' => 'Login', 'action' => 'auth']]) ?>
    <?=  $this->Form->control('username', ['label' => 'ユーザー名']) ?>
    <?=  $this->Form->control('password', ['type' => 'password', 'label' => 'パスワード']) ?>
    <?=  $this->Form->button('ログイン') ?>
<?= $this->Form->end() ?>