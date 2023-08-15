@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            @if (count($errors) > 0)
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
            <h5 class="m-b-10 text-center">All Categories</h5>
            <p class="text-muted m-b-10 text-center">Defines Product Categorie</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Categories</a>
               </li>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
              <div class="card-header">
        <i class="fa fa-table"></i> Categories List
        <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Category</a>
      </div>
         </div>
      </div>
      <!-- Page-header end -->
      <div class="card">
         <div class="card-header">
            <!--  <h5>Hover table</h5>
               <span>use class <code>table-hover</code> inside table element</span> -->
            <div class="card-header-right">
               <ul class="list-unstyled card-option">
                  <li><i class="fa fa-chevron-left"></i></li>
                  <li><i class="fa fa-window-maximize full-card"></i></li>
                  <li><i class="fa fa-minus minimize-card"></i></li>
                  <li><i class="fa fa-refresh reload-card"></i></li>
                  <li><i class="fa fa-times close-card"></i></li>
               </ul>
            </div>
         </div>
         <div class="card-block table-border-style">
            <div class="table-responsive">
               <table class="table table-hover table-datatable">
                  <thead>
                     <tr class="text-center">
                        <th>ID</th>
                        <th>Name</th>
                        <th>T.Products linked</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($categories as $category)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        @if($category->user_id == Auth::id())
                        <td>{{ $category->product->pluck('id')->count() }}</td>
                        @else
                        <td>--</td>
                        @endif
                        <td>
                        @if($category->user_id == Auth::id())
                           <a href="{{ route('update.category' , $category->id) }}" class="btn btn-sm btn-primary edit-category" data-toggle="modal" data-target="#category-popup">
                              <i class="fa fa-edit"></i>
                           Edit Category
                           </a>
                           @if ($category->product->pluck('id')->count() == 0)
                           <a href="{{ route('delete.category' , $category->id) }}" class="btn btn-sm btn-danger delete-btn waves-effect" data-type="inverse" data-animation-in="animated rotateInDownRight" data-animation-out="animated rotateOutUpRight">
                              <i class="fa fa-trash"></i>
                           Delete Category
                           </a>
                           @else
                           <a class="btn btn-sm btn-danger delete-btn waves-effect" data-type="inverse" data-animation-in="animated rotateInDownRight" style="color: white;" data-animation-out="animated rotateOutUpRight" disabled="">
                              <i class="fa fa-warning"></i>
                           Can't Delete Product Linked!
                           </a>
                           @endif
                        
                        @else
                        --
                        @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Hover table card end -->
      <div id="styleSelector">
      </div>
   </div>
</div>

<div class="modal fade" id="category-popup" tabindex="-1" role="dialog" aria-labelledby="category-popup" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Category</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" class="category-form" onsubmit='disableButton()'>
          {{ csrf_field() }}
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control old-category" required="">
          </div>
          <div class="form-group modal-footer">
            <button class="btn btn-primary btn-block call-processing preloader3" id="button">Add</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">
  $('.edit-category').click(function(event){
    event.preventDefault();
    var cTR = $(this).closest('tr');
    var c_name = cTR.find('td').eq(1).text();
    $('.old-category').val(c_name)
    $('.category-form').attr('action' , $(this).attr('href'));
    //$('#category-popup .modal-title').html('Edit <b>' + c_name + '</b>');
    $('#category-popup .modal-footer button').text('Update Category');
  });
  $(document).ready(function(){
  $(document).on('click', '.add-category', function() {
   console.log(1);
    event.preventDefault();
    $('.old-category').val('');
    $('.category-form').attr('action' , '{{ route("save.category") }}');
    $('#category-popup .modal-title').html('Add Category');
    $('#category-popup .modal-footer button').text('Add Category');
  });
  });
</script>
@endpush