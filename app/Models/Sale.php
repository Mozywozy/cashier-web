<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total'];

    protected $casts = [
        'sale_date' => 'datetime', // Casts the sale_date attribute to a Carbon instance
    ];

    protected $dates = ['sale_date'];

    public $timestamps = true;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
