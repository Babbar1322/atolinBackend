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
              <h2 class="pageheader-title">Token Swap History</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link"></a>Token Swap History</li>
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
                        <th>#</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>User Email</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $key => $item)
                        <tr>
                          <td>{{ ++$key }}</td>
                          <td>{{ $item->created_at->format('d M Y') }}</td>
                          <td>{{ $item->user->name ?? "Deleted" }} {{$item->user->lastName ?? "User"}}</td>
                          <td>{{ $item->user->email ?? "-" }}</td>
                          <td>
                            @if ($item->from === 'ATOLIN')
                                Wallet <b>({{$item->atolin_amount}})</b>
                            @else
                                Token <b>({{$item->token_amount}})</b>
                            @endif
                          </td>
                          <td>
                            @if ($item->from === 'ATOLIN')
                                Token <b>({{$item->amountAfterFee()}})</b>
                            @else
                                Wallet <b>({{$item->amountAfterFee()}})</b>
                            @endif
                          </td>
                          <td><a href="/token-swap-details/{{ $item->id }}" class="btn btn-primary">Details</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>User Email</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Actions</th>
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
