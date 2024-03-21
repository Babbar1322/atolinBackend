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
                    <h2 class="pageheader-title">User Management</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="/usermanagement" class="breadcrumb-link">User
                                        Management</a></li>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Register Date</th>
                                        <th>More Details</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getUsers as $key => $getUser)
                                        <tr>
                                            <td>{{ $key + 1 }} </td>
                                            <td> <b>{{ $getUser->name . ' ' . $getUser->lastname }}</b></td>
                                            <td>{{ $getUser->email }}</td>
                                            <td>{{ $getUser->contact }}</td>
                                            <td>{{ $getUser->created_at->format('d-M-Y h:i:s') }}</td>
                                            <td>
                                                <a href="/user/{{ ucfirst($getUser->id) }}"
                                                    class="btn btn-info">View</a>
                                                <a href="{{ route('sendBalance', $getUser->id) }}"
                                                    class="btn btn-primary">Send Balance</a>
                                                <form action="{{ route('delete-user', ['id' => $getUser->id]) }}"
                                                    method="post" onsubmit="return confirm('Do you really want to delete this user?\n{{$getUser->name . ' ' . $getUser->lastname}}');">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete User</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Register Date</th>
                                        <th>More Details</th>
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
