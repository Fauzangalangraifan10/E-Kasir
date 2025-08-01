<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['store_name', 'logo', 'address', 'phone', 'tax', 'discount'];
}
