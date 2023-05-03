<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'slug',
        'image',
        'name',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_descrip',
        'selling_price',
        'original_price',
        'qty',
        'brand',
        'featured',
        'popular',
        'status',
        
    ];
      //Relation call...........
    protected $with = ['category'];
    public function category(){
        //$this->belongsTo(Model to fetch data from::class, 'foreign_key_id', 'primary_key_id');......format.....
        return $this->belongsTo(Category::class,'category_id', 'id' );
    }
}
