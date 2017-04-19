<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     protected $id=['id'];
     protected $table = 'comment';
     public $timestamps = false;
}
