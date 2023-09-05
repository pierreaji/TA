<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function Category()
    {
        return $this->hasOne(Category::class, 'id', 'id_category');
    }

    public function Distributor()
    {
        return $this->hasOne(Distributor::class, 'id', 'id_distributor');
    }

    public function ItemAssign()
    {
        return $this->hasMany(ItemAssign::class, 'id_item', 'id');
    }

    public function ItemRequest()
    {
        return $this->hasMany(ItemRequest::class, 'id_item', 'id');
    }

    public function In()
    {
        return $this->hasMany(ItemIn::class, 'id_item', 'id');
    }

    public function Transactions()
    {
        return $this->hasMany(Transaction::class, 'id_item', 'id');
    }
}
