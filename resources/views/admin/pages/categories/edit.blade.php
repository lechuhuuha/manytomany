<div class="modal fade" id="categoryEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cập nhật danh mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="categoryEditForm">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="cate_name2">Tên danh mục</label>
                        <input type="text"  onchange="ChangeToSlug('cate_name2','slug2')" class="form-control" require id="cate_name2">
                        <div id="cate_alert2" style="display:none" class="alert alert-danger mt-3" role="alert">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slug2">Slug</label>
                        <input type="text" class="form-control" require id="slug2">
                        <div id="slug_alert2" style="display:none" class="alert alert-danger mt-3" role="alert">
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-start">
                        @foreach (config("categories.cate.status") as $key=>$value)
                        <div class="custom-control custom-checkbox mr-5 ">
                            <input class="form-check-input status{{$key}}" type="radio" id="status" onchange="checked({{$key}})" name="status" value="{{$key}}">
                            <label class="form-check-label">{{$value}}</label>
                        </div>        
                        @endforeach
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>