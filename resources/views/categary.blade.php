@extends('layout.master')

@section('contant')


    <!-- End Navbar -->
    <div class="container-fluid py-4">

        <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0" style="display: flex; align-items: center; justify-content: space-between;">
                <h6>Product Category</h6>
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add</button> --}}
                 </div>

            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center  mb-0" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SR No.</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                      <th class="text-uppercase text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>

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
            <label for="recipient-name" class="col-form-label">Category Name</label>
            <input type="text" name="category" required class="form-control" id="recipient-name">
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
              <label for="recipient-name" class="col-form-label">Category Name</label>
              <input type="text" name="category" id="category" required class="form-control" id="recipient-name">
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
                    url: "{{route('user.category-save')}}",
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

  // dom: 'lBfrtip',
  // buttons: ['copy', 'excel', 'pdf', 'print'],
  processing: true,
  serverSide: true,
  searching:true,
 ajax : "{{route('user.CategoryList')}}",
 columns: [
      {
          data: 'id',
      },
      {
          data: 'name',
      },
      {
          data : 'action',
          orderable: false,
      }
 ],

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
                      url: "{{route('user.category-delete')}}",
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
  url : "{{route('user.category-delete')}}",
  type : "GET",
  data : {'edit' : id},
  success : function(response){
    // console.log(response);

    if(response.status == "success"){
      $('#id').val(response.data.id);
      $('#category').val(response.data.category);

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
                  url: "{{route('user.category-edit')}}",
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
