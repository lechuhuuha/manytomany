@extends('admin.layout')
@section('title')
Thêm mới permission
@endsection
@section('content')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title d-flex "> <a href="{{route('admin.permission.index')}}"><i class="fas fa-undo-alt"></i></a></h3>
    </div>
    <form action="{{route('admin.permission.store')}}" method="post">
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="exampleInputEmail1">Tên module</label>
                <select class="custom-select" name="module_parent">
                          <option value="">Chọn module</option>
                          @foreach(config('permissions.permission_parent') as $module)
                         
                          @if($permissions_parent->contains('name',$module)!=true)
                          <option value="{{$module}}">{{$module}}</option>
                          @endif
                          @endforeach
                        </select>
                <code> {{ $errors->first('module_parent') }} </code>
            </div>
            
            <div class="form-group">
                <label for="exampleInputEmail1">Mô tả module</label>
            
                <textarea name="desc" class="form-control" id="exampleInputEmail1" cols="30" rows="10"  placeholder="Mô tả module"></textarea>
                <code> {{ $errors->first('desc') }} </code>
            </div>
         
        </div>

        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Giửi</button>
        </div>
    </form>
</div>
@endsection
@section('javascript')



@endsection