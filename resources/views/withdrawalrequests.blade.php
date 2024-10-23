<?php use App\Http\Controllers\DashboardController;
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
                    @include('common.notify')
                    <h2 class="pageheader-title">{{request()->route()->named('confirmedwithdrawrequests') ? "Confirmed":"Pending"}} Withdrawal Requests</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('document.index') }}"
                                        class="breadcrumb-link"></a>Withdrawal Requests </li>
                            </ol>
                        </nav>
                        <!-- <a href="{{ route('document.create') }}" class="btn btn-primary" style=" float:right">Add Document</a></td> -->
                        <!-- <br> -->
                    </div>

                </div>
            </div>
        </div>


        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <br>
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
                                        <th>Requested User Name</th>
                                        <th>Account Holder Name</th>
                                        <th>Bank Name</th>
                                        <th>Account Number</th>
                                        <th>ABA Routing Number</th>
                                        <th>Phone Number</th>
                                        <th>Amount</th>
                                        <th>Admin Fees</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allwithdrawalrequests as $index => $withdrawrequests)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $withdrawrequests->user->name ?? 'No Name' }}</td>

                                            <td>{{ $withdrawrequests->account_holder_name }}</td>
                                            <td>{{ $withdrawrequests->bank_name }}</td>
                                            <td>{{ $withdrawrequests->account_number }}</td>
                                            <td>{{ $withdrawrequests->ifsc }}</td>
                                            <td>{{ $withdrawrequests->phone_no }}</td>
                                            <td>{{ $withdrawrequests->amount }}</td>
                                            <td>{{ $withdrawrequests->admin_fees }}</td>
                                            <td>{{ $withdrawrequests->status }}</td>
                                            <td>
                                                @if ($withdrawrequests->status == 'PENDING')
                                                    <form
                                                        action="{{ route('withdrawapproval', $withdrawrequests->id) }}"
                                                        enctype="multipart/form-data" role="form">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" id="id" name="id"
                                                            value="{{ $withdrawrequests->id }}">
                                                        <button class="btn btn-success"
                                                            onclick="return confirm('Are you sure?')">APPROVE</button>
                                                    </form>
                                                    <br>
                                                    <form action="{{ route('withdrawreject', $withdrawrequests->id) }}"
                                                        enctype="multipart/form-data" role="form">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" id="id" name="id"
                                                            value="{{ $withdrawrequests->id }}">
                                                        <button class="btn btn-danger"
                                                            onclick="return confirm('Are you sure?')"
                                                            style="width:100px">REJECT</button>
                                                    </form>
                                                @else
                                                    <p>NO ACTIONS REQUIRED</p>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Requested User Name</th>
                                        <th>Account Holder Name</th>
                                        <th>Bank Name</th>
                                        <th>Account Number</th>
                                        <th>ABA Routing Number</th>
                                        <th>Phone Number</th>
                                        <th>Amount</th>
                                        <th>Admin Fees</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
