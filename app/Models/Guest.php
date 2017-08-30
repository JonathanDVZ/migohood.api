<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
     protected $id=['id'];
     protected $table = 'guests';
     public $timestamps = false;
}