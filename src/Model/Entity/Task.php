<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Cake\I18n\FrozenDate|null $start_date
 * @property \Cake\I18n\FrozenDate|null $end_date
 * @property \Cake\I18n\Date|null $due_date
 * @property bool $completed
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Task extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'title' => true,
        'description' => true,
        'priority' => true,
        'sort_order' => true,
        'is_recurring' => true,       
        'recurring_type' => true, 
        'recurring_interval' => true, 
        'start_date' => true,
        'end_date' => true,
        'due_date' => true,
        'completed' => true,
        'parent_id' => true,
        'created' => true,
        'modified' => true,
    ];
}
