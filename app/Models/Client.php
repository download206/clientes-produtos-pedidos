<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients'; 

    use HasFactory;
    
    protected $fillable = [
        'nome', 'telefone', 'email',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

   
}
