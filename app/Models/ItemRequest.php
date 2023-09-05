<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'items_request';

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'id_item');
    }

    public function Trx()
    {
        return $this->hasOne(Transaction::class, 'id_item', 'id_item');
    }

    public function ItemAssign()
    {
        return $this->hasOne(ItemAssign::class, 'id_item', 'id_item');
    }
}
