@extends('layout.master')

@section('contant')


    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Customer</p>
              </div>
            </div>

            <form id="addform" method="POST">
                @csrf

            <div class="card-body">
              <p class="text-uppercase text-sm">User Information</p>
              <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Mobile</label>
                      <input class="form-control" type="text" name="mobile_no" value="" required>
                    </div>
                  </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Pancard</label>
                    <input class="form-control" type="text" value="" name="pan" required>
                  </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Category</label>
                      <select name="category_id" id="" class="form-control" required>
                             <option value="">Select Category</option>

                             @foreach ($category as $key => $val)

                             <option value="{{$val['id']}}">{{$val['title']}}</option>

                             @endforeach

                      </select>
                    </div>
                  </div>

              </div>

            </div>

            <button type="submit" class="btn btn-primary p-2">Save</button>
            <br>

        </form>
          </div>
        </div>

      </div>

    </div>


<script>

$(document).ready(function(){

    $('#addform').on('submit', function(e) {
        e.preventDefault();

        // alert('hello');

        var formData = new FormData($(this)[0]);

        Swal.fire({
            title: 'Confirm Submission',
            text: 'Are you sure you want to submit the form?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel',
            customClass: {
    popup: 'small-swal-popup',
    htmlContainer: 'custom-text-color',
    title: 'custom-title-color'
}
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('user.checkcustomer')}}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if(response.status == "success"){
                            Swal.fire({
                                title: 'Success!',
                                text: 'Form submitted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
    popup: 'small-swal-popup',
    htmlContainer: 'custom-text-color',
    title: 'custom-title-color'
}
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        } else if(response.status == "error"){
                            $.each(response.message, function(field, message) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '-error').text(message).addClass('text-danger');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem with your submission.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
    popup: 'small-swal-popup',
    htmlContainer: 'custom-text-error-color',
    title: 'custom-title-error-color'
}
                        });
                    }
                });
            }
        });
    });

    var table = $('#datatable').DataTable({

  processing: true,
  serverSide: true,
  searching:true,
 ajax: {
    url: "{{ route('user.companylist') }}",
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
      {
          data: 'name',
      }
 ],

});

});

</script>

@endsection
