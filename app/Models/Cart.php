<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = ['user_id','product_id','product_qty','product_name'];


    //Relation call...........
    protected $with = ['product'];

    public function product(){
        //$this->belongsTo(Model to fetch data from::class, 'foreign_key_id', 'primary_key_id');......format.....
       return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
