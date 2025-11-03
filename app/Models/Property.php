<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Property extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'address_line1',
        'address_line2',
        'address_line3',
        'city',
        'state_province',
        'country_code',
        'country',
        'postal',
        'phone',
        'fax',
        'region',
        'logo'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
//     public function getAccounts()
//     {
//         return $this->hasMany(Account::class);
//     }

//     public function getUsers()
//     {
//         return $this->hasMany(User::class);
//     }

//     public function getBookings()
//     {
//         return $this->hasMany(Booking::class);
//     }

//     public function getContacts()
//     {
//         return $this->hasManyThrough(Contact::class, Account::class);
//     }

//     // New relationship for multi-property access
//     public function users()
// {
//     return $this->belongsToMany(User::class, 'user_property_access')
//         ->withTimestamps();

}
