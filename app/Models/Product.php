<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // protected $table = 'products';
    protected $with = ['categories'];
    protected $fillable = [
        'name',
        'price',
        'competitive_price',
        'desc',
        'image',
        'short_desc',
        'slug',
        'branch_id',
        'discount'
    ];
    //public $timestamps = false;
    // public function product_cate_pros()
    // {
    //     return $this->hasMany(Cate_Product::class, 'product_id');
    // }
    public function image_products()
    {
        return $this->hasMany(Image_products::class, 'product_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_products', 'product_id', 'cate_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Chỉnh sửa dữ liệu đầu ra khi đối tượng gọi thuộc tính. Trường hợp muốn lấy dữ liệu gốc thì dùng hàm getRawOriginal('attributeName')
    // Accessor get{AttributeName}Attribute
    public function getPriceAttribute()
    {
        $price = number_format($this->attributes['price'], 0, '', ',') . ' VNĐ';
        return $price;
    }
    public function getCompetitivePriceAttribute()
    {
        $competitive_price = number_format($this->attributes['competitive_price'], 0, '', ',') . ' VNĐ';
        return $competitive_price;
    }
    public function getDiscountAttribute()
    {
        $discount = "- " . $this->attributes['discount'] . ' %';
        return $discount;
    }
}
