<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'id_item');
    }
}
