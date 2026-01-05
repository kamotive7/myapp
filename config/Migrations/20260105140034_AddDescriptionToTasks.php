<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddDescriptionToTasks extends BaseMigration
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
        $table = $this->table('tasks');

        //descriptionカラム（テキスト型）を追加
        //nullを許可（null => true）し、作成日時(created)カラムの前に配置するように指定
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true,
        ]);
        
        $table->update();
    }
}
