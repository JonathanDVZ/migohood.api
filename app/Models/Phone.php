<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
     protected $cod_service=['id'];
     protected $table = 'phone_number';
     public $timestamps = false;
}