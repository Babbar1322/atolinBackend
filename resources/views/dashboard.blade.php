<?php use \App\Http\Controllers\DashboardController;
use App\Models\User;
?>

<x-app-layout>
   <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome />
            </div>
        </div>
    </div> -->

    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
          <!-- ============================================================== -->
          <!-- pageheader  -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="page-header">
                <h2 class="pageheader-title">{{Setting::get('site_name')}} Dashboard</h2>

                <div class="page-breadcrumb">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>

                    </ol>
                  </nav>
                </div>
              </div>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- end pageheader  -->
          <!-- ============================================================== -->
          <div class="ecommerce-widget">
            <div class="row">
              <!-- ============================================================== -->
              <!-- sales  -->
              <!-- ============================================================== -->
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-4">
                <div class="card border-top-primary shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="text-muted mb-4">Today Transactions</h5>
                    <div class="d-flex justify-content-between">
                      <div class="metric-value">
                        <h1 class="font-weight-bold">{{ DashboardController::getTodaytracsactioncount() }}</h1>
                      </div>
                  <div class="float-right icon-shape icon-lg rounded-circle  bg-info-light mt-1">
                    <i class="fas fa-exchange-alt fa-fw fa-sm text-info font-24"></i>
                  </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ============================================================== -->
              <!-- end sales  -->
              <!-- ============================================================== -->
              <!-- ============================================================== -->
              <!-- new customer  -->
              <!-- ============================================================== -->
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-4">
                <div class="card border-top-primary shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="text-muted mb-4">New Customer</h5>
                    <div class="d-flex justify-content-between">
                      <div class="metric-value">
                        <h1 class="font-weight-bold">{{ DashboardController::getTodayCustomercount() }}</h1>
                      </div>
                      <div class="float-right icon-shape icon-lg rounded-circle  bg-primary-light mt-1">
                    <i class="fa fa-user fa-fw fa-sm text-primary font-24"></i>
                  </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ============================================================== -->
              <!-- end new customer  -->
              <!-- ============================================================== -->
              <!-- ============================================================== -->
              <!-- visitor  -->
              <!-- ============================================================== -->
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-4">
                <div class="card border-top-primary shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="text-muted mb-4">Today Credit Amount</h5>
                    <div class="d-flex justify-content-between">
                      <div class="metric-value">
                        <h1 class="font-weight-bold"> $ {!! number_format((float)(DashboardController::getTodayCreditAmount()), 2) !!}</h1>
                      </div>
                      <div class="float-right icon-shape icon-lg rounded-circle  bg-success-light mt-1">
                    <i class="fa fa-money-bill-alt fa-fw fa-sm text-success font-24"></i>
                  </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ============================================================== -->
              <!-- end visitor  -->
              <!-- ============================================================== -->
              <!-- ============================================================== -->
              <!-- total orders  -->
              <!-- ============================================================== -->
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-4">
                <div class="card border-top-primary shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="text-muted mb-4">Today Debit Amount</h5>
                    <div class="d-flex justify-content-between">
                      <div class="metric-value">
                        <h1 class="font-weight-bold">$ {!! number_format((float)(DashboardController::getTodayDebitAmount()), 2) !!}</h1>
                      </div>
                      <div class="float-right icon-shape icon-lg rounded-circle  bg-secondary-light mt-1">
                    <i class="far fa-money-bill-alt fa-fw fa-sm text-secondary font-24"></i>
                  </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ============================================================== -->
              <!-- end total orders  -->
              <!-- ============================================================== -->
            </div>

            <div class="row">
            <!-- ============================================================== -->
            <!-- four widgets   -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- total views   -->
            <!-- ============================================================== -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Transactions</h5>
                    <h2 class="mb-0"> {{ DashboardController::getTotalTracsactioncount() }}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-info-light mt-1">
                    <i class="fas fa-exchange-alt fa-fw fa-sm text-info font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- ============================================================== -->
            <!-- end total views   -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- total followers   -->
            <!-- ============================================================== -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card  mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Customers</h5>
                    <h2 class="mb-0"> {{ DashboardController::getTotalCustomercount() }}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-primary-light mt-1">
                    <i class="fa fa-user fa-fw fa-sm text-primary font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- ============================================================== -->
            <!-- end total followers   -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- partnerships   -->
            <!-- ============================================================== -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Credit Amount</h5>
                    <h2 class="mb-0">$ {!! number_format((float)(DashboardController::getTotalCreditAmount()), 2) !!}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-success-light mt-1">
                    <i class="fa fa-money-bill-alt fa-fw fa-sm text-success font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- ============================================================== -->
            <!-- end partnerships   -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- total earned   -->
            <!-- ============================================================== -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Debit Amount</h5>
                    <h2 class="mb-0">$ {!! number_format((float)(DashboardController::getTotalDebitAmount()), 2) !!}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-secondary-light mt-1">
                    <i class="far fa-money-bill-alt fa-fw fa-sm text-secondary font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Balance in Wallet</h5>
                    <h2 class="mb-0">$ {!! number_format((float)(DashboardController::getTotalWallet()), 2) !!}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-info-light mt-1">
                    <i class="fal fa-money-bill-wave fa-fw fa-sm text-info font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Swap Fee Collected</h5>
                    <h2 class="mb-0">$ {!! number_format((float)(DashboardController::getTotalSwapFee()), 2) !!}</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-info-light mt-1">
                    <i class="fas fa-exchange-alt fa-fw fa-sm text-info font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card mb-5 shadow-sm">
                <div class="card-body">
                  <div class="d-inline-block">
                    <h5 class="text-muted mb-3">Total Gas Fee Sent</h5>
                    <h2 class="mb-0">{!!number_format((float)(DashboardController::getTotalGasFee()), 7)!!} BNB</h2>
                  </div>
                  <div class="float-right icon-shape icon-xl rounded-circle  bg-secondary-light mt-1">
                    <i class="fas fa-coins fa-fw fa-sm text-secondary font-24"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- ============================================================== -->
            <!-- end total earned   -->
            <!-- ============================================================== -->
          </div>

            <div class="row">

              <!-- ============================================================== -->
              <!-- recent orders  -->
              <!-- ============================================================== -->
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                <div class="card shadow-sm h-100">
                  <h5 class="card-header">Recent Transactions</h5>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-light">
                          <tr class="border-0">
                            <th class="border-0">#</th>
                            <th class="border-0">Transaction Id</th>
                            <th class="border-0">User</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Transaction Type</th>
                            <th class="border-0">Transaction Time</th>
                            <th class="border-0">Details</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($getLatestTransactions as $key => $getLatestTransaction)
                          <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $getLatestTransaction->transaction_id }}</td>
                            <td>@php $user = User::find($getLatestTransaction->user_id, ['email']) @endphp {{ $user->email }}</td>
                            <td>$ {!! number_format((float)($getLatestTransaction->amount), 2) !!}</td>
                            <td>{{ ucfirst($getLatestTransaction->t_type) }}
                            @if ($getLatestTransaction->t_type === 'debit')
                            <span class="icon-shape icon-xs rounded-circle text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-up"></i></span>
                            @else
                            <span class="icon-shape icon-xs rounded-circle text-success ml-4 bg-success-light"><i class="fa fa-fw fa-arrow-down"></i></span>
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
              <!-- ============================================================== -->
              <!-- end recent orders  -->
              <!-- ============================================================== -->

            </div>





          </div>
        </div>
      </div>

</x-app-layout>
