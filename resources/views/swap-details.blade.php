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
              <h2 class="pageheader-title">Swap Details</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/token-swap-history" class="breadcrumb-link">Swap History</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link">Swap Details</a></li>
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
                <h3 class="card-title mb-2 text-truncate ">
                  <a href="/user/{{ ucfirst($swap->user->id ?? 0) }}">{{ $swap->user->name ?? "Deleted".' '. ($swap->user->lastname ?? "User")  }}</a>
                </h3>
                <h6 class="card-subtitle text-muted mb-3 "> {{ $swap->user->email ?? "-" }}</h6>
                <p>
                    <i class="fa fa-mobile"> {{ $swap->user->contact ?? "-" }}</i>
                </p>
                <p>
                    <i class="fas fa-wallet"> {{ $swap->from === "ATOLIN" ? $swap->cryptoTransaction->to ?? "-" : $swap->cryptoTransaction->from ?? "-" }}</i>
                </p>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12 col-12">
          <div class="card card-fluid mb-5 shadow-sm">
          <h5 class="card-header">Swap Detail</h5>
          @php
              $fromAtolin = $swap->from === 'ATOLIN';
          @endphp
              <!-- .card-body -->
              <div class="card-body">
                <p><b>Swapped From:</b> {{$swap->from}}</p>
                <p><b>Swapped To:</b> {{$swap->to}}({{$swap->token_symbol}})</p>
                <p><b>From Amount:</b> @if($fromAtolin) {{$swap->atolin_amount}} $ @else {{$swap->token_amount}} {{$swap->token_symbol}} @endif</p>
                <p><b>To Amount:</b> {{$swap->amountAfterFee()}} @if($fromAtolin) {{$swap->token_symbol}} @else $ @endif</p>
                <p><b>Swap Fee:</b> {{$swap->fee_amount}}@if($fromAtolin) {{$swap->token_symbol}} @else $ @endif</p>
                <p><b>@if($fromAtolin) Sent To:</b> {{$swap->cryptoTransaction->to ?? ""}} @else Received From:</b> {{$swap->cryptoTransaction->from}} @endif </p>
              </div>

              <!-- /.card-body -->
            </div>
        </div>



      </div>

</x-app-layout>
