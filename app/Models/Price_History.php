<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Price_History extends Model
{
     protected $cod_service=['starDate'];
     protected $table = 'price_history';
     public $timestamps = false;
}