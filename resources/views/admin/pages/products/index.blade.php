@extends('admin.layout')
@section('title', "Danh sách sản phẩm")
@section('content')
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Thêm mới</a>
        <div style="display:flex; justify-content:space-around">
            <form name="search_product" method="get" action="{{ htmlspecialchars($_SERVER["REQUEST_URI"]) }}">
                <div style="margin: 15px 0; display:flex">
                    <div>
                        <strong>Thương hiệu</strong>
                        <select style="width: 200px;" class="form-control" name="branch" id="branch">
                            <option value="">Tất cả</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->slug }}" {{isset($_GET['branch']) && $_GET['branch']==$branch->slug? "selected":""}}> {{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mr-3 ml-3">
                        <strong>Danh mục</strong>
                        <select style="width: 200px;" class="form-control" name="category" id="category">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                          
                            <option value="{{ $category->slug }}"{{isset($_GET['category']) && $_GET['category']==$category->slug? "selected":""}}> {{ $category->name }} </option>
                          
                        
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 80%; margin-top:23px" class="ml-3">
                        <input value="{{isset($_GET['keyword'])?$_GET['keyword']:""}}" placeholder="type here" class="form-control"
                            type="text" name="keyword">

                    </div>
                    <input type="hidden" name="page" value="1">
                    <div class="mr-3 ml-3" style="margin-top: 22px">
                        <button class="btn btn-success">Lọc</button>
                    </div>
                </div>
            </form>
        </div>


    </div>
    <div class="card-body">
        <table class="table table-scriped" id="categoryTable">
            <thead>
                <th>ID</th>
                <th>Tên sản phẩm</th>              
                <th>Thương hiệu</th>
                <th>Hình ảnh</th>
                <th>Giá gốc</th>
                <th>Giá cạnh tranh</th>
                <th>Giảm giá (%)</th>
                <th>Hành động</th>
            </thead>
            <tbody>
                @foreach($products as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->name }}</td>
                    <td>{{$m->branch->name}}</td>
                    <td>
                        <img style="width: 50px; height: 50px" src="{{ asset("storage/$m->image") }}"
                            alt="">
                    </td>
                    <td>{{ $m->price }}</td>
                    <td>{{ $m->competitive_price }}</td>
                    <td>{{ $m->discount }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', ['id'=>$m->id])}}" class="btn btn-warning">Sửa</a>
                     
                           <b  onclick="deleteProduct({{$m->id}})" class="btn btn-danger">Xóa</b>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(count($products))
    {{$products->links()}}
    @endif
</div>
@endsection
@section('javascript')
@if(Session::has('message'))
<script>
swal({
    title: "Good job!",
    text: "{{ Session::get('message') }}!",
    icon: "success",
    button: "OK!",
});
// delete Product




</script>

@endif
<script>
    function deleteProduct(id) {
    swal({
            title: "Bạn có chắc xóa không?",
            text: "Khi xóa, bạn sẽ không phục hổi lại được!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.get("/admin/products/delete/" + id, function(data) {
                    console.log(data);
                    $('#cid' + data.id).css('display', 'none');
                    swal("Great Job", "Xóa danh mục thành công", "success", {
                        button: "OK",
                    })
                   
                });
                 location.reload();
               
            } else {
                swal("Bạn đã hủy xóa danh mục!");
            }
        });
}
$(document).ready(function () {

$("a.page-link").on("click", function (e) {
    e.preventDefault();

    var rel = $(this).attr("rel");

    if (rel == "next") {
        var page = $("body").find(".page-item.active > .page-link").eq(0).text();
        console.log(" : " + page);
        page = parseInt(page);
        page += 1;
    } else if(rel == "prev") {
        var page = $("body").find(".page-item.active > .page-link").eq(0).text();
        console.log(page);
        page = parseInt(page);
        page -= 1;
    } else {
        var page = $(this).text();
    }

    console.log(page);

    page = parseInt(page);

    $("input[name='page']").val(page);

    $("form[name='search_product']").trigger("submit");
});
});


</script>

@endsection