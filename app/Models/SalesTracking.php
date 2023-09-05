<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTracking extends Model
{
    use HasFactory;
    protected $table = 'sales_tracking';

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
