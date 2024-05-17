<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Site
 *
 * This class represents a site model in the application. It defines the structure and relationships
 * for managing sites in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class Site extends Model
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
}
