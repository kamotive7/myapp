<?php

declare(strict_types=1);

use Migrations\BaseMigration;

class AddRecurringToTasks extends BaseMigration
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
        $table->addColumn('is_recurring', 'boolean', [
            'default' => false,
            'null' => false,
            'after' => 'sort_order'
        ]);
        $table->addColumn('recurring_type', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'after' => 'is_recurring'
        ]);
        $table->addColumn('recurring_interval', 'integer', [
            'default' => 1,
            'limit' => 11,
            'null' => true,
            'after' => 'recurring_type'
        ]);
        $table->update();
    }
}
