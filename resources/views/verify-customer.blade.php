@extends('layout.master')

@section('contant')


    <!-- End Navbar -->
    <div class="container-fluid py-4">

        <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0" style="display: flex; align-items: center; justify-content: space-between;">
                <h6>Company</h6>
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add</button> --}}
                 </div>

            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">

                <div class="card-header pb-0" style="display: flex; align-items: center; justify-content: space-between;">

                     </div>
                <table class="table align-items-center  mb-0" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SR No.</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mobile No.</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Creadit Score</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>

                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

<script>

$(document).ready(function(){

    var table = $('#datatable').DataTable({

  processing: true,
  serverSide: true,
  searching:true,
 ajax: {
    url: "{{ route('user.verify-customer-list') }}",
    type: "GET",
    data: function (d) {
      d.category = $('.category').val();
      d.cateoryID = $('#categoryID').val();
    }
  },
 columns: [
      {
          data: 'id',
      },
      { data: 'mobile'},
      { data: 'cutomber_id'},
      { data: 'credit_score'},
      { data: 'action'},
 ],

});

});

</script>

@endsection
