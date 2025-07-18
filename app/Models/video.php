<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Word;
class video extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'video',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }


public function word()
{
    return $this->belongsToMany(Word::class);
}

}
