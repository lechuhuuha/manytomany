<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use App\Models\Cate_Product;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Image_products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\product\UpdateRequest;

class ProductController extends Controller
{

    private $product;
    private $image_products;
    public function __construct(Product $product, Image_products $image_products)
    {
        $this->product = $product;
        $this->image_products = $image_products;
    }

    public function index(Request $request)
    {
        // $searchData = $request->except('page');
        //  dd($_GET['branch']);
        $products = Product::where(function ($query) use ($request) {   // thực hiện tìm các products trong bẳng cate_products
            if ($request->category) {
                $cate = Category::where('slug', $request->category)->first();
                $items = $cate->cate_products; // lấy bảng con
                $pro_id = [];

                foreach ($items as $i) {
                    $pro_id[] = $i->product_id;
                }
                //  dd($query->from('products')->whereIn('id', $pro_id));
                return $query->from('products')->whereIn('id', $pro_id);
            }
        })
            ->where(function ($query) use ($request) { // nếu branch khác rỗng thì tìm
                if ($request->branch) {
                    $cate = Branch::where('slug', $request->branch)->first();
                    //  dd($query->from('products')->whereIn('id', $pro_id));
                    // dd();
                    return $query->from('products')->where('branch_id', $cate->id);
                }
                // return $request->branch ? $query->from('products')->where('branch_id', $request->branch) : '';
            })
            ->where(function ($query) use ($request) { // nếu keyword khác rỗng thì tìm
                return $request->keyword ? $query->from('products')->where('name', 'like', "%$request->keyword%") : '';
            })
            ->orderBy('updated_at', 'DESC')->paginate(10);



        $data["searchKeyword"] = $request->searchKeyword;
        $data["category_id"] = $request->category_id;
        $data["branch_id"] = $request->branch_id;
        $branch = DB::table('branches')->get();
        $categories = DB::table('categories')->get();
        $data["categories"] = $categories;
        $data["branches"] = $branch;
        // dd($products[0]->price);
        return view('admin.pages.products.index', compact('products'), $data);
    }


    public function create()
    {
        $categories = DB::table('categories')->get();
        $branches = DB::table('branches')->get();

        return view('admin.pages.products.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:products|max:500',
            'slug' => 'required|unique:products',
            'image' => 'required|mimes:jpg,bmp,png|max:2048',
            'image2' => 'max:10048',
            'branch_id' => 'required',
            'cate_id' => 'required',
            'price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'competitive_price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'desc' => 'required',
        ]);

        $request->discount = ($request->discount != null) ? $request->discount : 0;
        // dd($request->discount);
        try {
            DB::beginTransaction();
            $pathAvatar = $request->file('image')->store('public/images/products');
            $pathAvatar = str_replace("public/", "", $pathAvatar);
            $product = $this->product->create([
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'slug' => $request->slug,
                'short_desc' => $request->short_desc,
                'image' => $pathAvatar,
                'desc'  => $request->desc,
                'price' => $request->price,
                'competitive_price' => $request->competitive_price,
                'discount'  => $request->discount,

            ]);

            $product->categories()->attach($request->cate_id);
            foreach ($request->file('image2') as $image2) {
                $image2 = $image2->store('public/images/products');
                $image_product = str_replace("public/", "", $image2);
                Image_products::create([
                    'product_id' => $product->id,
                    'image' => $image_product,
                ]);
            }
            DB::commit();
            //$request->session()->flash('message', 'Thêm sản phẩm thành công');
            return redirect('/admin/products')->with('message', 'Thêm sản phẩm thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('message :', $exception->getMessage() . '--line :' . $exception->getLine());
        }
        return redirect()->back()->with('message', 'Thêm sản phẩm thất bại !');
    }

    public function show(Product $product)
    {
        //
    }


    public function edit($id, Request $request)
    {
        $model = Product::find($id);


        //   dd($model->image_products);
        if ($model) {
            $product_ids = $model->product_cate_pros;
            $model->load('image_products');
            $categories = DB::table('categories')->get();
            $branches = DB::table('branches')->get();
            return view('admin.pages.products.edit', compact('model', 'categories', 'branches', 'product_ids'));
        } else {
            return redirect('/admin/products');
        }
    }


    public function update(Request $request, $id)
    {
        $model = Product::find($id);
       //  dd($request->file('image'));
        if ($model) {
            $model->load('image_products');
            $request->validate([
                'name' => 'required|max:500',
                'slug' => 'required',
                'image' => 'mimetypes:image/jpeg,image/png|max:2048',
                'image2' => 'max:10048',
                'branch_id' => 'required',
                'cate_id' => 'required',
                'price' => 'required|regex:/^\d*(\.\d{2})?$/',
                'competitive_price' => 'required|regex:/^\d*(\.\d{2})?$/',
                'short_desc' => 'required',
            ]);
            if ($request->name !== $model->name) {
                $request->validate([
                    'name' => 'unique:products',
                ]);
            }
            $request->discount = ($request->discount != null) ? $request->discount : 0;
            //  dd($model->image);
            try {
                DB::beginTransaction();

               
                if ($request->file('image') != null) {
                    if (file_exists('storage/' . $request->image)) {
                        unlink('storage/' . $request->image);
                    }
                    $pathAvatar = $request->file('image')->store('public/images/products');
                    $pathAvatar = str_replace("public/", "", $pathAvatar);
                } else {
                    $pathAvatar = $model->image;
                }


                $product = $model->update([
                    'name' => $request->name,
                    'branch_id' => $request->branch_id,
                    'slug' => $request->slug,
                    'short_desc' => $request->short_desc,
                    'image' => $pathAvatar,
                    'desc'  => $request->desc,
                    'price' => $request->price,
                    'competitive_price' => $request->competitive_price,
                    'discount'  => $request->discount,

                ]);

                $model->categories()->sync($request->cate_id);
                if ($request->file('image2') != null) {
                    foreach ($model->image_products as $image) {
                        if (file_exists('storage/' . $image->image)) {
                            unlink('storage/' . $image->image);
                        }
                        $model->image_products()->delete();
                    }
                    foreach ($request->file('image2') as $image2) {
                        $image2 = $image2->store('public/images/products');
                        $image_product = str_replace("public/", "", $image2);
                        // dd($image_product);
                        $model->image_products()->create([
                            'product_id' => $id,
                            'image' => $image_product,
                        ]);
                    }
                }

                DB::commit();
                //   //$request->session()->flash('message', 'Thêm sản phẩm thành công');
                return redirect('/admin/products')->with('message', 'Sửa sản phẩm thành công');
            } catch (Exception $exception) {
                DB::rollBack();
                Log::error('message :', $exception->getMessage() . '--line :' . $exception->getLine());
            }
            return redirect()->back()->with('message', 'Sửa sản phẩm thất bại !');
        }
    }


    public function destroy($id, Request $request)
    {
        $model = Product::find($id);
        $model->load('image_products');
        foreach ($model->image_products as $image) {

            if (file_exists('storage/' . $image->image)) {
                unlink('storage/' . $image->image);
            }
        }

        if ($model) {
            if (file_exists('storage/' . $model->image)) {
                unlink('storage/' . $model->image);
            }
            $model->delete();
            // $request->session()->flash('message', 'Xóa sản phẩm thành công');
            return redirect('/admin/products')->with('message', 'Xóa sản phẩm thành công');
        } else {
            return redirect('/admin/products');
        }
    }
}
