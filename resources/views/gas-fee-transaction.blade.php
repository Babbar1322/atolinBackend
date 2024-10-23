<x-app-layout>

    <div class="container-fluid  dashboard-content">
            <!-- ============================================================== -->
            <!-- pageheader -->
            <!-- ============================================================== -->
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Gas Fee Transactions</h2>
                  <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link"></a>Gas Fee Transactions</li>
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
                            <th>Amount</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $key => $item)
                            <tr>
                              <td>{{ ++$key }}</td>
                              <td>{{ $item->created_at->format('d M Y') }}</td>
                              <td>{{ $item->user->name }} {{$item->user->lastName}}<br>{{$item->user->email}}</td>
                              <td>{{ $item->amount }}</td>
                              <td><a href="/token-swap-details/{{ $item->fee_id }}" class="btn btn-primary">Swap Details</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Amount</th>
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
