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
              <h2 class="pageheader-title">Transaction Details</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/transactions" class="breadcrumb-link">Transaction Management</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link">Transaction Details</a></li>
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
            <h5 class="card-header">User Detail</h5>
              <!-- .card-body -->
              <div class="card-body text-center ">
                <!-- .user-avatar -->
                <?php $getUserDetails =User::find($getUserTransactionDetails->user_id); ?>
                <!-- <a href="/user/{{ ucfirst($getUserTransactionDetails->user_id) }}" class="user-avatar avatar-xl my-3 ">
                  <img src="{{ asset('storage/profile-photos/'.$getUserDetails->profile_photo_path) }}" alt="User Avatar " class="rounded-circle avatar-lg ">
                </a> -->
                <!-- /.user-avatar -->
                <h3 class="card-title mb-2 text-truncate ">
                  <a href="/user/{{ ucfirst($getUserTransactionDetails->user_id) }}">{{ $getUserDetails->name.' '. $getUserDetails->lastname  }}</a>
                </h3>
                <h6 class="card-subtitle text-muted mb-3 "> {{ $getUserDetails->email }}</h6>
                <p>
                    <i class="fa fa-mobile"> {{ $getUserDetails->contact }}
                    </i>
                  
                </p>

                
              </div>
             
              <!-- /.card-body -->
              
            </div>
             
          </div>

          <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12 col-12">
          <div class="card card-fluid mb-5 shadow-sm">
          <h5 class="card-header">Transaction Detail</h5>
              <!-- .card-body -->
              <div class="card-body">
              <p>  <b>Transaction Id :</b> {{ $getUserTransactionDetails->transaction_id }} </p>
              <p>  <b>Amount :</b> $ {!! number_format((float)($getUserTransactionDetails->amount), 2) !!} </p>
              <p>  <b>Transaction Type :</b> {{ $getUserTransactionDetails->t_type }} 
              @if ($getUserTransactionDetails->t_type === 'credit')
                            <span class="icon-shape icon-xs rounded-circle text-success ml-4 bg-success-light"><i class="fa fa-fw fa-arrow-up"></i></span>
                            @else
                            <span class="icon-shape icon-xs rounded-circle text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-down"></i></span>
                            @endif</p>
              <p>  <b>Source Type :</b> {{ $getUserTransactionDetails->source_id }} </p>
              <p>  <b>Receiver :</b> {{ $getUserTransactionDetails->receiver_id }} </p>
              <p>  <b>Comments :</b> {{ $getUserTransactionDetails->comments }} </p>
              <p>  <b>Transaction Time :</b> {{ $getUserTransactionDetails->created_at }} </p> 
              </div>
             
              <!-- /.card-body -->              
            </div>
        </div>
        
        
        
      </div>

</x-app-layout>
