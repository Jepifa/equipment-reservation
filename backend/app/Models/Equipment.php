<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Equipment
 *
 * This class represents an equipment model in the application. It defines the structure and relationships
 * for managing equipment in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class Equipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'operational',
        'equipment_group_id',
    ];

    /**
     * Define a belongs-to relationship with the EquipmentGroup model.
     *
     * This method defines a belongs-to relationship between the current model and the EquipmentGroup model.
     * It specifies that an instance of the current model belongs to a single EquipmentGroup instance.
     * The relationship is established based on the foreign key 'equipment_group_id' in the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipmentGroup()
    {
        return $this->belongsTo(EquipmentGroup::class);
    }
}
