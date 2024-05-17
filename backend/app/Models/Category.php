<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * This class represents a category model in the application. It defines the structure and relationships
 * for managing categories in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Define a one-to-many relationship with the EquipmentGroup model.
     *
     * This method defines a one-to-many relationship between the current model and the EquipmentGroup model.
     * It specifies that an instance of the current model can have multiple EquipmentGroup instances associated with it.
     * The relationship is established based on the foreign key 'category_id' in the EquipmentGroup model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(EquipmentGroup::class);
    }
}
