@extends('admin.layout')
@section('title')Danh sách permission
@endsection
@section('content')

<div class="col-md-12">
<a href="{{route('admin.permission.create')}}" class="btn btn-secondary"><i class="fas fa-plus-circle"></i></a> <br> <br>
</div>
<div class="col-md-12">

    @foreach($permissions_parent as $parent)
    <div class="col-md-3" style="float:left;">
        <!-- USERS LIST -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">{{$parent->name}}</h3>

                <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0"style="height: 290px;">
              
                <textarea name="" class="col-md-12"  rows="2"disabled >{{$parent->desc}}</textarea>
                <ul class="users-list clearfix">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hành động</th>
                                <th>Key</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parent->permissionsChilden as $permissionsChildenItem)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$permissionsChildenItem->name}}</td>
                                <td><span class="badge badge-warning">{{$permissionsChildenItem->key_code}}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </ul>
                <!-- /.users-list -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="{{route('admin.permission.edit',['id'=>$parent->id])}}" class="btn btn-success"><i class="fas fa-edit"></i></a>
                <a href="javascript:void(0)" onclick="deleteCategory({{$parent->id}})" class="btn btn-danger" ><i class="fas fa-trash-alt"></i></a>

            </div>
            <!-- /.card-footer -->
        </div>
        <!--/.card -->
    </div>
    @endforeach
</div>

@endsection
@section('javascript')
@if(Session::has('status'))
<script>
swal({
  title: "Good job!",
  text: "{{ Session::get('status') }}!",
  icon: "success",
  button: "OK!",
});
</script>
@endif
<script>
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
                $.get("/admin/permission/destroy/" + id, function(data) {
                    
                    $('#cid' + data.id).css('display', 'none');
                    swal("Great Job", "Xóa permission thành công", "success", {
                        button: "OK",
                    })
                });
                location.reload();
            } else {
                swal("Bạn đã hủy xóa permission!");
            }
        });
}
</script>
@endsection