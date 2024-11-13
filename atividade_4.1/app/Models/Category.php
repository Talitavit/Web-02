<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    use Hasfactory;

    protected $fillable = ['name'];

    public function books (){
        return $this->hasMany(Book::class);
    }
    


}