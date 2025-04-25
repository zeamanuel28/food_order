<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'contact_number',
        'description',
        'location',
        'restaurant_admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'restaurant_admin_id');
    }
}
