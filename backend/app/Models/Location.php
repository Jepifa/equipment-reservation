<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 *
 * This class represents a location model in the application. It defines the structure and relationships
 * for managing locations in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'site_id',
    ];

    /**
     * Define a belongs-to relationship with the Site model.
     *
     * This method defines a belongs-to relationship between the current model and the Site model.
     * It specifies that an instance of the current model belongs to a single Site instance.
     * The relationship is established based on the foreign key 'site_id' in the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
