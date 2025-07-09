<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\video;

class Word extends Model
{
    use HasFactory;
    protected $table = 'words';

   public function video()
{
    return $this->belongsToMany(Video::class);
}

public function model(){
    return $this->hasOne(Model3D::class);
}
}
