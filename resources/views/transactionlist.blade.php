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
              <h2 class="pageheader-title">User Transactions</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/transactions" class="breadcrumb-link">User Transactions</a></li>
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
                        <th>S.No</th>
                        <th>Transaction Id</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Transaction Type</th>
                        <th>Transaction Time</th>
                        <th>Details</th>

                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($getAllTransactions as $key => $getAllTransaction)
                      <tr>
                        <td>{{ ++$key  }}</td>
                        <td>{{ ucfirst($getAllTransaction->transaction_id) ?? "-" }} </td>
                        <td>@php $user = User::find($getAllTransaction->user_id, ['email']) @endphp
                        {{ $user->email ?? "" }}</td>
                        <td>$ {!! number_format((float)($getAllTransaction->amount), 2) !!}</td>
                        <td>{{ ucfirst($getAllTransaction->t_type) }}
                            @if ($getAllTransaction->t_type === 'debit')
                            <span class="icon-shape icon-xs rounded-circle text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-up"></i></span>
                            @else
                            <span class="icon-shape icon-xs rounded-circle text-success ml-4 bg-success-light"><i class="fa fa-fw fa-arrow-down"></i></span>
                            @endif</td>
                        <td>{{ $getAllTransaction->created_at }}</td>
                        <td>
                          <a href="/transaction/{{ ucfirst($getAllTransaction->id) }}" class="btn btn-primary">View</a></td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                      <th>S.No</th>
                      <th>Transaction Id</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Transaction Type</th>
                        <th>Transaction Time</th>
                        <th>Details</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- end basic table  -->
          <!-- ============================================================== -->
        </div>




      </div>

</x-app-layout>
