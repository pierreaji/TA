<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemIn extends Model
{
    use HasFactory;
    protected $table = 'items_in';

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'id_item');
    }
}
