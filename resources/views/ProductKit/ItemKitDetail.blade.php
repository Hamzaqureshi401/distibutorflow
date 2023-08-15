  <!-- Breadcrumbs-->
<div class="row">
  <div class="col-md-12 m-auto">
    <div class="card mb-3">
      <div class="card-header text-center">
        Kit Details
      </div>
      <div class="card-body">
        <div class="card-block table-border-style">
                     <div class="table-responsive">
                        <table class="table table-bordered table-datatable table-custom-th table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                
                                 <th>P Name</th>
                                 <th>Quantity</th>
                                 
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($ItemKitDetails as $ItemKit)
                              <tr>
                               <td>{{ $ItemKit->product->name }}</td>
                                <td>{{ $ItemKit->quantity }}</td>
                                
                                </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </div>
      </div>
    </div>
  </div>

      
 