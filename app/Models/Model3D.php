<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model3D extends Model
{
    use HasFactory;
    protected $table = 'models';
    protected $fillable = ['name' , 'path'];

    

    public function word(){
     return $this->belongsTo(Word::class);
    }
}
