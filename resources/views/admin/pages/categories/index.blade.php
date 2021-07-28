@extends('admin.layout')
@section('title', 'Danh mục sản phẩm')
@section('content')
<div class="card">
    <div class="d-flex justify-content-start">
        <div class="card-header">
            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#categoryModal">Thêm mới</a>
        </div>
        <div class="card-header">
            <form action="" name="fillter_cate" method="GET">
                <select class="form-control target" name="status">
                    @foreach(config('categories.cate.status') as $key =>$value)
                    <option value="{{$key}}" onchange="status($key)"{{$status==$key? 'selected':""}}>{{$value}}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="page" value="1">
            </form>
        </div>
   
    </div>
    
    <div class="card-body">
        <table class="table table-scriped" id="categoryTable">
            <thead>
                <th>ID</th>
                <th>Loại danh mục</th>
                <th>Tên danh mục</th>
                <th>SLug</th>
             <th>Số lượng SP</th>
                <th>Hành động</th>
            </thead>
            <tbody>
                @foreach($models as $c)
                <tr id="cid{{$c->id}}">
                    <td>{{$c->id}}</td>
                   <td>{{ config("categories.cate.status.$c->status") }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->slug }}</td>
                    <td>{{ $c->cate_products()->count() }}</td>
                    <td>
                    <a href="javascript:void(0)" onclick="editCategory({{$c->id}})" class="btn btn-warning">Sửa</a>
                        <a href="javascript:void(0)" onclick="deleteCategory({{$c->id}})" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@if(count($models))
{{$models->links()}}
@endif
<!-- Modal -->
@include('admin.pages.categories.create')
@include('admin.pages.categories.edit')
@endsection
@section('javascript')
<script src="{{asset('asset_be/dist/js/pages/slug.js')}}"></script>
<script>


$( ".target" ).change(function(e) {
    e.preventDefault();
    $("form[name='fillter_cate']").trigger("submit");
});
$(document).ready(function() {
        $(".page-link").on("click", function(e) {
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

                $("form[name='fillter_cate']").trigger("submit");
            });
    });
// create category
$('.modal-footer button').on('click', function() {
    $('#cate_alert').css('display', 'none');
    $('#slug_alert').css('display', 'none');

    $('#cate_alert2').css('display', 'none');
    $('#slug_alert2').css('display', 'none');
});
$('#categoryForm').on('submit', function(e) {
    e.preventDefault();
    let cate_name = $('#cate_name').val();
    let slug = $('#slug').val();
    let status = $('#status:checked').val();
    let _token = $('input[name=_token]').val();
    $.ajax({
        url: "{{ route('admin.categories.store') }}",
        type: "POST",
        data: {
            name: cate_name,
            slug: slug,
            status: status,
            _token: _token,
        },
        success: function(response) {
            let id = response.id;
            console.log(response);
            let edit_html ='<a href="javascript:void(0)" onclick="editCategory('+id+')" class="btn btn-warning">Sửa</a>';
                
            let delete_html ='<a href="javascript:void(0)" onclick="deleteCategory('+id+')" class="btn btn-danger">Xóa</a>'
                var status1= (response.status==1)?"Điện tử":"Điện gia dụng";
            $('#categoryTable tbody')
                .prepend("<tr id="+"cid"+id+"><td>" + response.id + "</td><td>" + status1 + "</td><td>" + response.name + "</td><td>" + response.slug + "</td><td>0</td><td>" +
                    edit_html + delete_html + "</td></tr>");
            $('#categoryForm')[0].reset();
            $('#categoryModal').modal('hide');

            swal("Great Job", "Thêm danh mục thành công", "success", {
                button: "OK",
            })
        },
        error: function(data) {
            console.log(data);
            var errors = data.responseJSON;
            if (errors.errors.name) {
                $('#cate_alert').css('display', 'block');
                $('#cate_alert').text(errors.errors.name[0]);
            } else {
                $('#cate_alert').css('display', 'none');
            }
            if (errors.errors.slug) {
                $('#slug_alert').css('display', 'block');
                $('#slug_alert').text(errors.errors.slug[0]);
            } else {
                $('#slug_alert').css('display', 'none');
            }

        }
    });

})

// edit category
function editCategory(id) {
    $.get("/admin/categories/edit/" + id, function(data) {
  
      if(data.status==1){
        $(".status0").attr('checked', false);
      }
      else{
        $(".status1").attr('checked', false);
      }
       
        $('#id').val(data.id);
        $('#cate_name2').val(data.name);
        $('#slug2').val(data.slug);
        $(".status"+data.status+"").attr('checked', true);
        $('#categoryEditModal').modal('toggle');
    })
}
$('#categoryEditModal').on('submit', function(e) {
    e.preventDefault();
    let id = $('#id').val();
    let name = $('#cate_name2').val();
    let slug = $('#slug2').val();
    let status = $('input[checked=checked]').val();
    let _token = $('input[name=_token]').val();

    $.ajax({
        url: "{{ route('admin.categories.update') }}",
        type: "POST",
        data: {
            id: id,
            name: name,
            slug: slug,
            status: status,
            _token: _token,
        },
        success: function(response) {
            var status1= (response.status==1)?"Điện tử":"Điện gia dụng";
            $('#cid' + response.id + ' td:nth-child(2)').text(status1);
            $('#cid' + response.id + ' td:nth-child(3)').text(response.name);
            $('#cid' + response.id + ' td:nth-child(4)').text(response.slug);
            $('#categoryEditModal').modal('toggle');
            $('#categoryEditForm')[0].reset();

            swal("Great Job", "Cập nhật danh mục thành công", "success", {
                button: "OK",
            })
        },
        error: function(data) {
            var errors = data.responseJSON;
            if (errors.errors.name) {
                $('#cate_alert2').css('display', 'block');
                $('#cate_alert2').text(errors.errors.name[0]);
            } else {
                $('#cate_alert2').css('display', 'none');
            }
            if (errors.errors.slug) {
                $('#slug_alert2').css('display', 'block');
                $('#slug_alert2').text(errors.errors.slug[0]);
            } else {
                $('#slug_alert2').css('display', 'none');
            }

        }
    });
})

// delete category
function deleteCategory(id) {
    swal({
            title: "Bạn có chắc xóa không?",
            text: "Khi xóa, bạn sẽ không phục hổi lại được!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.get("/admin/categories/delete/" + id, function(data) {
                    console.log(data);
                    $('#cid' + data.id).css('display', 'none');
                    swal("Great Job", "Xóa danh mục thành công", "success", {
                        button: "OK",
                    })
                });
                swal("Bạn đã xóa danh mục thành công!", {
                    icon: "success",
                });
            } else {
                swal("Bạn đã hủy xóa danh mục!");
            }
        });
}

$(document).ready(function() {
    $('input[type=radio][name="status"]').change(function() {
        // alert($(this).val()); // or, use `this.value`
        var key=$(this).val();
      $(".status0").attr('checked', false);
      $(".status1").attr('checked', false);
      $(".status"+key+"").attr('checked', true);
    });
});
</script>
@endsection