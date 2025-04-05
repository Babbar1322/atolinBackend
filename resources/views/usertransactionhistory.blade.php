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
              <h2 class="pageheader-title">Transaction History</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link"></a>Transaction History</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
          <!-- ============================================================== -->
          <!-- basic table  -->
          <!-- ============================================================== -->
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card shadow-sm mb-5">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered first">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Transaction ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Transaction type</th>
                        <th>Made at</th>

                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($getusersLatestTransactions as $key => $getLatestTransaction)
                        <tr>
                          <td>{{ ++$key }}</td>
                          <td>{{ $getLatestTransaction->transaction_id }}</td>
                          <td>
                            @php $user = User::find($getLatestTransaction->user_id, ['name']) @endphp {{ $user->name ?? "Deleted" }}
                          </td>
                          <td>@php $user = User::find($getLatestTransaction->receiver_id, ['name']) @endphp {{ $user->name ?? "Deleted" }}</td>
                          <td>$ {!! number_format((float)($getLatestTransaction->amount), 2) !!}</td>
                          <td>{{ ucfirst($getLatestTransaction->t_type) }}
                          @if ($getLatestTransaction->t_type === 'debit')
                          <span class="icon-shape icon-xs rounded-circle text-success ml-4 bg-success-light"><i class="fa fa-fw fa-arrow-up"></i></span>
                          @else
                          <span class="icon-shape icon-xs rounded-circle text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-down"></i></span>
                          @endif
                          </td>
                          <td>{{ $getLatestTransaction->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Transaction ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Transaction type</th>
                        <th>Made at</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

</x-app-layout>
