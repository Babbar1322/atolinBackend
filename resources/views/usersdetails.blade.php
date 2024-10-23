<?php use \App\Http\Controllers\DashboardController;
use App\Models\User;
?>

<x-app-layout>

<div class="container-fluid  dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
              <h2 class="pageheader-title">User Details</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/usermanagement" class="breadcrumb-link">User Management</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link">User Details</a></li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row ">
          <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 ">
            <div class="card card-fluid mb-5 shadow-sm">
              <!-- .card-body -->
              <div class="card-body text-center ">
                <!-- .user-avatar -->
                {{-- <a href="" class="user-avatar avatar-xl my-3 ">
                  <img src="{{ asset('storage/profile-photos/'.$getUserDetails->profile_photo_path) }}" alt="User Avatar " class="rounded-circle avatar-lg ">
                </a> --}}
                <!-- /.user-avatar -->
                <h3 class="card-title mb-2 text-truncate ">
                  <a href="">{{ $getUserDetails->name.' '. $getUserDetails->lastname  }}</a>
                </h3>
                <h6 class="card-subtitle text-muted mb-3 "> {{ $getUserDetails->email }}</h6>
                <p>
                  <i class="fa fa-mobile"> {{ $getUserDetails->contact }}
                    </i>

                </p>


              </div>

              <!-- /.card-body -->
              <!-- .card-footer -->
              <footer class="card-footer p-0 d-flex ">
                <!-- .card-footer-item -->
                <div class="card-footer-item text-left card-footer-item-bordered ">
                  <!-- .metric -->

              <p>  <b>Country :</b> {{ $getcountry}} </p>
              <p>  <b>Balance :</b> ${{ $balance }} </p>
                  <!-- /.metric -->
                </div>
                <!-- .card-footer-item -->
                <!-- /.card-footer-item -->

                <!-- .card-footer-item -->

              </footer>
              <!-- /.card-footer -->

            </div>

          </div>

          <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12 col-12">
          <div class="card shadow-sm h-100">
                  <h5 class="card-header">Recent Transactions</h5>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered first">
                        <thead class="bg-light">
                          <tr class="border-0">
                            <th class="border-0">#</th>
                            <th class="border-0">Transaction Id</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Transaction Type</th>
                            <th class="border-0">Transaction Time</th>
                            <th class="border-0">Details</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($getuserLatestTransactions as $key => $getLatestTransaction)
                          <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $getLatestTransaction->transaction_id }}</td>
                            <td>$ {!! number_format((float)($getLatestTransaction->amount), 2) !!}</td>
                            <td>{{ ucfirst($getLatestTransaction->t_type) }}
                            @if ($getLatestTransaction->t_type === 'credit')
                            <span class="icon-shape icon-xs rounded-circle text-success ml-4 bg-success-light"><i class="fa fa-fw fa-arrow-up"></i></span>
                            @else
                            <span class="icon-shape icon-xs rounded-circle text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-down"></i></span>
                            @endif
                            </td>
                            <td>{{ $getLatestTransaction->created_at }}</td>
                            <td><a href="/transaction/{{ ucfirst($getLatestTransaction->id) }}" class="btn btn-outline-success ">View</a></td>
                          </tr>
                          @endforeach

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>




          </div>

         </div>
        </div>



      </div>

</x-app-layout>
