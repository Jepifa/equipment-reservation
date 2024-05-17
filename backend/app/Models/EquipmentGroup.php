<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EquipmentGroup
 *
 * This class represents an equipment group model in the application. It defines the structure and relationships
 * for managing equipment groups in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
    ];
    
    /**
     * Define a belongs-to relationship with the Category model.
     *
     * This method defines a belongs-to relationship between the current model and the Category model.
     * It specifies that an instance of the current model belongs to a single Category instance.
     * The relationship is established based on the foreign key 'category_id' in the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define a one-to-many relationship with the Equipment model.
     *
     * This method defines a one-to-many relationship between the current model and the Equipment model.
     * It specifies that an instance of the current model can have multiple Equipment instances associated with it.
     * The relationship is established based on the foreign key 'equipment_group_id' in the Equipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
