<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $prop = [];
    public function index(Request $request)
    {
        
        Product::with('categories')->chunk(100,function ($products) {
                foreach ($products as $product) {
                    $cate = $product->categories()->get();
                    foreach ($cate as $cat) {
                        if ($cat->status == config('categories.cate.ts.ĐT')) {
                            if ($cat->pivot->product_id == $product->id) {
                                $this->prop[] .=  $product;
                            };
                        }
                    }
                }
            }
        );
        dump($this->prop);
        // foreach ($datas as $data) {
        //     $cate = $data->categories()->get();
        //     dump($data->name);
        //     foreach ($cate as $cat) {
        //         echo '<pre>';
        //         if ($cat->status == config('categories.cate.ts.ĐT')) {
        //             if ($cat->pivot->product_id == $data->id) {
        //                 echo $data;
        //             };
        //         }
        //         echo '</pre>';
        //     }
        // }
        return view('test.test');
    }
    public function index2(Request $request)
    {
        // get categories
        $categories = Category::orderBy('updated_at', 'asc')->orderBy('id', 'asc')->get();
        // get slider
        // $slider = Slider::orderBy('updated_at', 'desc')->get();

        // get producs Điện tử
        $products_DT = Product::where(function ($query) use ($request) {   // thực hiện tìm các products trong bẳng cate_products

            $cate = Category::where('status', config('categories.cate.ts.ĐT'))->get();
            $pro_id = [];
            $count = 0;
            $pro_id2 = [];
            foreach ($cate as $alphabet => $collection) {
                $pro_id[] = $collection->cate_products()->get();
            }
            foreach ($pro_id as $collection) {
                if (count($collection) != 0) {
                    for ($i = 0; $i < count($collection); $i++) {
                        $pro_id2[] = $collection[$i]->product_id;
                        // lấy id của product theo id của cate có status là 1
                    }
                }
            }
            return $query->from('products')->whereIn('id', $pro_id2);
        })->orderBy('updated_at', 'DESC')->paginate(8);


        return view('test.test2.home');
    }
}
