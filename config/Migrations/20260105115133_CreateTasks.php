<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateTasks extends BaseMigration
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
        $table->addColumn('title', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
         $table->addColumn('due_date', 'date', [
            'null' => true,
        ]);
         $table->addColumn('completed', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
         $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
         $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
