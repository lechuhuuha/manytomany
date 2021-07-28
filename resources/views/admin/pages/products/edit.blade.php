@extends('admin.layout')
@section('title', 'Sửa sản phẩm')
@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Quay lại</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update',['id'=>$model->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="desc">Mô tả</label>
                <textarea id="summernote" name="desc" style="display: none;">{{ $model->desc }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input onchange="ChangeToSlug('name','slug')" value="{{ $model->name }}" type="text" id="name" name="name" class="form-control" require>
                    </div>
                    @error('name')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input value="{{ $model->slug }}" type="text" id="slug" name="slug" class="form-control" require>
                    </div>
                    @error('slug')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="branch_id">Thương hiệu</label> <br>
                        <select class="form-control" name="branch_id" id="">
                            @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{$model->branch_id == $b->id?"selected":""}}> {{ $b->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    @error('branch_id')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="cate_id">Danh mục</label> <br>
                        <select class="js-example-basic-multiple form-control" value="{{ old('cate_id[]') }}" name="cate_id[]" multiple="multiple">
                            @foreach($categories as $c)
                            <option value="{{$c->id}}" {{ $product_ids->contains('cate_id', $c->id) ? 'selected' : ''}}>{{$c->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('cate_id')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="image">Hình ảnh đại diện sản phẩm</label>
                        <input onchange="previewFile(this)" id="product_image" type="file" id="image" name="image" class="form-control"
                            require>
                           <div class="d-flex justify-content-start">
                            <div class="mt-3" style="width: 130px; border: 1px solid gray; background-color: pink !important;text-align: center;">
                                <img style="max-width: 125px"src="{{ asset("storage/$model->image") }}" alt="">
                            </div>
                            <div  class="mt-3" style="font-size: 100px">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="mt-3" style="width: 130px; height: 200px; border: 1px solid gray ; background-color: pink !important;text-align: center;">
                                <img style="max-width: 125px" id="previewimg" alt="">
                            </div>
                           </div>
                       
                    </div>
                    @error('image')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
           

                   



                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="short_desc">Miêu tả ngắn</label>
                        <textarea id="summernote2" class="form-control" name="short_desc"  cols="30"
                            rows="4">{{ $model->short_desc }}</textarea>
                    </div>
                    @error('short_desc')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="price">Giá</label>
                        <input value="{{ $model->price }}" type="number" id="price" name="price" class="form-control"
                            require>
                    </div>
                    @error('price')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="competitive_price">Giá cạnh tranh</label>
                        <input value="{{ $model->competitive_price }}" type="number" id="competitive_price"
                            name="competitive_price" class="form-control" require>
                    </div>
                    @error('competitive_price')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="discount">Khuyến mại (%)</label>
                        <input value="{{ $model->discount }}" type="number" id="discount" name="discount"class="form-control" require>
                            
                    </div>
                    @error('discount')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="image">Hình ảnh phụ</label>
                        <input id="product_image" type="file" id="image2" name="image2[]" class="form-control"  multiple="multiple" require>
                        <div class="d-flex justify-content-between">
                           @foreach($model->image_products as $image_products)
                            <div class="mt-3" style="width: 130px; height: 200px; border: 1px solid gray ; background-color: pink !important;text-align: center;">
                                <img style="max-width: 125px"src="{{ asset("storage/$image_products->image") }}" alt="">
                            </div>
                            @endforeach
                           </div>
                    </div>
                    @error('image2')
                    <div class="alert alert-danger mt-3" role="alert">
                        {{$message}}
                    </div>
                    @enderror
            
                  
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-success">Thêm mới</button>
            </div>
        </form>
    </div>
</div>


@endsection
@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('asset_be/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('asset_be/dist/js/pages/slug.js')}}"></script>
<script>
function previewFile(input) {
    var file = $("#product_image").get(0).files[0];
    console.log(file);
    if (file) {
        var reader = new FileReader();
        reader.onload = function() {
            $('#previewimg').attr('src', reader.result);
        }
        reader.readAsDataURL(file);
    }
}
$(function() {
    // Summernote
    $('#summernote').summernote()
    $('#summernote2').summernote()
    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
    });
})

$(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    $(function() {
        bsCustomFileInput.init();
    });

  
</script>
@endsection