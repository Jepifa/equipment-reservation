<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Preference
 *
 * This class represents a preference model in the application. It defines the structure and relationships
 * for managing preferences in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class Preference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'manip_name',
        'user_id',
        'location_id',
    ];

    /**
     * Define a belongs-to relationship with the User model.
     *
     * This method defines a belongs-to relationship between the current model and the User model.
     * It specifies that an instance of the current model belongs to a single User instance.
     * The relationship is established based on the foreign key 'user_id' in the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a belongs-to relationship with the Location model.
     *
     * This method defines a belongs-to relationship between the current model and the Location model.
     * It specifies that an instance of the current model belongs to a single Location instance.
     * The relationship is established based on the foreign key 'location_id' in the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Define a many-to-many relationship with the User model representing the team members.
     *
     * This method defines a many-to-many relationship between the current model and the User model.
     * It specifies that an instance of the current model can belong to multiple User instances as team members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Define a many-to-many relationship with the Equipment model.
     *
     * This method defines a many-to-many relationship between the current model and the Equipment model.
     * It specifies that an instance of the current model can be associated with multiple Equipment instances.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function equipment()
    {
        return $this->belongsToMany(Equipment::class);
    }
}
