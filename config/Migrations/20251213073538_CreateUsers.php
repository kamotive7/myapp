<?php

declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUsers extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        //usersテーブル作成
        $table = $this->table('users');
        
        $table
            // ユーザー名（ログインID）
            ->addColumn('username', 'string', [
                'limit' => 50,
                'null' => false,
            ])

            // パスワード（ハッシュ保存）
            ->addColumn('password', 'string', [
                'limit' => 255,
                'null' => false,
            ])

            // 表示名
            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false,
            ])

            // 作成日時
            ->addColumn('created', 'datetime', [
                'null' => false,
            ])

            // 更新日時
            ->addColumn('modified', 'datetime', [
                'null' => false,
            ])

            // username はユニーク
            ->addIndex(['username'], [
                'unique' => true,
                'name' => 'idx_users_username_unique',
            ])
            ->create();
    }
}
