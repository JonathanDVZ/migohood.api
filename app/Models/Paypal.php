<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
     protected $cod_service=['id_paypal'];
     protected $table = 'paypal';
     public $timestamps = false;
}