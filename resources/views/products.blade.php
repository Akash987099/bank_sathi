@extends('layout.master')

@section('contant')


    <!-- End Navbar -->
    <div class="container-fluid py-4">

        <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0" style="display: flex; align-items: center; justify-content: space-between;">
                <h6>Products</h6>
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add</button> --}}
                 </div>

            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <div class="row">
                    <div class="col-3">
                        <select name="category" id="" class="form-control category">
                            <option value="">Select Category</option>
                            @foreach ($category as $key => $val)
                                <option value="{{$val->id}}">{{$val->category}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-header pb-0" style="display: flex; align-items: center; justify-content: space-between;">

                     </div>
                <table class="table align-items-center  mb-0" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SR No.</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Logo</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product</th>

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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="addform">
            @csrf

            <div class="mb-3">
                <label for="recipient-name" class="col-form-label">Category</label>
                <select name="category" id="" class="form-control" required>
                    <option value="">Category</option>
                    @foreach ($category as $key => $val)
                        <option value="{{$val->id}}">{{$val->category}}</option>
                    @endforeach
                </select>
              </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Product Name</label>
            <input type="text" name="product" required class="form-control" id="recipient-name">
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Payout</label>
            <input type="text" name="payout" required class="form-control" id="recipient-name" required>
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">ETB</label>
            <input type="text" name="etb"  class="form-control" id="recipient-name">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Send</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Update Form</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" id="updateform">
              @csrf
              <input type="hidden" id="id" name="id">

              <div class="mb-3">
                <label for="recipient-name" class="col-form-label">Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Category</option>
                    @foreach ($category as $key => $val)
                        <option value="{{$val->id}}">{{$val->category}}</option>
                    @endforeach
                </select>
              </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Product Name</label>
            <input type="text" name="product" required class="form-control" id="product">
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Payout</label>
            <input type="text" name="payout" required class="form-control" id="payout" required>
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">ETB</label>
            <input type="text" name="etb"  class="form-control" id="etb">
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Send</button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  <input type="hidden" id="categoryID" value="{{$id}}">

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
                    url: "{{route('user.product-save')}}",
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
    url: "{{ route('user.ProductList') }}",
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
          data: 'logo',
      },
      {
          data: 'name',
      }
 ],

});

$('.category').on('change' , function(){
    table.ajax.reload();
});

$('body').on('click', '.delete', function (e) {

e.preventDefault();

var id = $(this).attr('data-id');


 Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'Cancel',
              customClass: {
      popup: 'small-swal-popup',
      htmlContainer: 'custom-text-color',
      title: 'custom-title-color'
  }
}).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url: "{{route('user.product-delete')}}",
                      type: "GET",
                      data: {'delete': id},
                      success: function(response) {
                          console.log(response);
                          if(response.status == "success"){
                              Swal.fire({
                                  title: 'Success!',
                                  text: response.message,
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

$('body').on('click' , '.edit', function(){

var id = $(this).attr('data-id');

$.ajax({
  url : "{{route('user.product-delete')}}",
  type : "GET",
  data : {'edit' : id},
  success : function(response){
    // console.log(response);

    if(response.status == "success"){
      $('#id').val(response.data.id);
      $('#category').val(response.data.category_id);
      $('#product').val(response.data.product);
      $('#payout').val(response.data.payout);
      $('#etb').val(response.data.etb);

      $('#exampleModal1').modal('show');
      $("#exampleModal").modal('hide');

    } else {

      Swal.fire({
                              title: 'Error!',
                              text: response.message,
                              icon: 'error',
                              confirmButtonText: 'OK',
                              customClass: {
      popup: 'small-swal-popup',
      htmlContainer: 'custom-text-error-color',
      title: 'custom-title-error-color'
  }
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  window.location.reload();
                              }
                          });

    }

  }
});

});

$('#updateform').on('submit' , function(e){

// alert('hello');

e.preventDefault();

var formData = $(this).serialize();

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
                  url: "{{route('user.product-edit')}}",
                  type: "POST",
                  data: formData,
                  success: function(response) {
                      console.log(response);
                      if(response.status == "success"){
                          Swal.fire({
                              title: 'Success!',
                              text: response.message,
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
                              $('#' + field + '_error').text(message).addClass('text-danger');
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

});

</script>

@endsection
