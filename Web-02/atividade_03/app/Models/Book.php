<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use Hasfactory;

    protected $fillade = ['title','author-id','category_id','publisher_id','publisher_year'];

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public function category(){
        return $this->beloongsTo(Category::class);
    }

    public function publisher (){
        return $this->belongsTo(Publisher::class);

    }



}
