<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
     protected $id_user=['id'];
     protected $table = 'user';
     public $timestamps = false;
}