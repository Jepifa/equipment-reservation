<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * This class represents a user model in the application. It defines the structure and relationships
 * for managing users in the database.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'validated',
        'color'
    ];

    /**
     * Define a one-to-many relationship with the Manip model.
     *
     * This method defines a one-to-many relationship between the current model and the Manip model.
     * It specifies that an instance of the current model can have multiple Manip instances associated with it.
     * The relationship is established based on the foreign key 'user_id' in the Manip model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function manips()
    {
        return $this->hasMany(Manip::class);
    }

    /**
     * Define a one-to-many relationship with the Preference model.
     *
     * This method defines a one-to-many relationship between the current model and the Preference model.
     * It specifies that an instance of the current model can have multiple Preference instances associated with it.
     * The relationship is established based on the foreign key 'user_id' in the Preference model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
