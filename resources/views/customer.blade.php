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


            <input class="form-control" type="hidden" name="customer_id" value="{{$cusID}}">

            <div class="card-body">
              <p class="text-uppercase text-sm">User Information</p>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">First Name</label>
                    <input class="form-control" type="text" name="first_name" value="{{$customer->first_name ?? ''}}" required>
                  </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Last Name</label>
                      <input class="form-control" type="text" name="last_name" value="{{$customer->last_name ?? ''}}" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Mobile</label>
                      <input class="form-control" type="text" name="mobile_no" value="{{$customer->mobile_no ?? ''}}" required>
                    </div>
                  </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Email address</label>
                    <input class="form-control" type="email" value="{{$customer->email ?? ''}}" name="email" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">DOB</label>
                    <input class="form-control" type="date" value="" required name="dob">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Company</label>
                    <select name="company" id="" class="form-control" required>
                      <option value="">Select Company</option>

                      @foreach ($company as $key => $val)

                      <option value="{{$val['id']}}">{{$val['company_name']}}</option>

                      @endforeach

                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Occupation</label>
                      <select name="occupation" id="" class="form-control" required>
                        <option value="">Select Occupation</option>
                        @foreach ($occuption as $item => $val)

                        <option value="{{$val['id']}}">{{$val['occu_title']}}</option>

                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Monthly Salary</label>
                      <input class="form-control" type="text" value="{{$customer->monthly_salary ?? ''}}" required name="monthly_salary">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">ITR Amount</label>
                      <input class="form-control" type="text" value="{{$customer->itr_amount ?? ''}}" required name="itr_amount">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Gender</label>
                      <select id="" name="gender" class="form-control" required>
                             <option value="">Select Category</option>
                             <option value="Male">Male</option>
                             <option value="Female">Female</option>
                             <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>

              </div>
              <hr class="horizontal dark">
              <p class="text-uppercase text-sm">Contact Information</p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Address</label>
                    <input class="form-control" type="text" name="Address" value="{{$customer->address ?? ''}}" required>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Postal code</label>
                    <input class="form-control" type="text" value="{{$customer->pincode ?? ''}} ignore this actully this is pincode ID" required name="pincode">
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">

              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Type</label>
                      <select name="category" id="" class="form-control" required>
                             <option value="">Select Category</option>
                             <option value="Individual">Individual</option>
                             <option value="Non-Individual">Non-Individual</option>
                      </select>
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
                    url: "{{route('user.customer-save')}}",
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
                                    window.location.href = '{{route('user.verify-customer')}}';
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
    let errMsg = 'There was a problem with your submission.';

    try {
        const res = JSON.parse(xhr.responseText);
        if (res.message) {
            errMsg = res.message;
        }
    } catch (e) {
        console.error('Failed to parse error response');
    }

    Swal.fire({
        title: 'Error!',
        text: errMsg,
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
