<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $fillable = [
    	"marca", "modelo", "ano", "preco"
    ];
}
