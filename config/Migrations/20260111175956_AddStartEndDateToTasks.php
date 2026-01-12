<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddStartEndDateToTasks extends BaseMigration
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
        $table->renameColumn('due_date', 'end_date');
        $table->addColumn('start_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'due_date'
        ]);
        $table->update();
    }
}
