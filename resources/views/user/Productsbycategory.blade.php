@extends('layout.master')

@section('contant')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- End Navbar -->
    <div class="container-fluid py-4">

      <div class="row">

        @foreach ($records as $item => $category)
        <div class="col-xl-3 col-sm-6 mb-4 p-2">
          <a href="{{ route('user.productbyId', ['id' => $category['product_id']]) }}">
            <div class="card h-100">
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ $category['title'] }}</p>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                        @if (!empty($category['logo']))
                          <img src="{{ $category['logo'] }}" class="text-lg opacity-10" height="50" width="50" alt="">
                        @else
                          <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        @endif
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
        @endforeach


      </div>

      @endsection
