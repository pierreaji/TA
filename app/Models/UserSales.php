<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSales extends Model
{
    use HasFactory;
    protected $table = 'users_sales_document';

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
