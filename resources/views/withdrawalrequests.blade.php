<?php use App\Http\Controllers\DashboardController;
use App\Models\User;
?>

<x-app-layout>
    @php
        $isConfirmed = request()->route()->named('confirmedwithdrawrequests');
    @endphp

    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    @include('common.notify')
                    <h2 class="pageheader-title">{{$isConfirmed ? "Confirmed":"Pending"}} Withdrawal Requests</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('document.index') }}" class="breadcrumb-link"></a>Withdrawal Requests</li>
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
                                        <th>{{$isConfirmed ? "Remarks" : "Action"}}</th>
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
                                                    <!-- Approve Button triggers modal -->
                                                    <button class="btn btn-success approveBtn" data-withdraw="{{ $withdrawrequests->id }}">APPROVE</button>
                                                    <br>
                                                    <!-- Reject Button triggers modal -->
                                                    <button class="btn btn-danger rejectBtn" data-withdraw="{{ $withdrawrequests->id }}"
                                                        style="width:100px">REJECT</button>
                                                @else
                                                    <p>{{$withdrawrequests->remarks ?? "No Remarks"}}</p>
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
                                        <th>{{$isConfirmed ? "Remarks" : "Action"}}</th>
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

    <!-- Modal for Remarks -->
    <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Enter Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="remarksForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="withdrawalRequestId" name="id">
                        <input type="hidden" id="actionType" name="action_type">
                        <div class="form-group">
                            <label for="remarks" class="col-form-label">Remarks:</label>
                            <textarea class="form-control" id="remarks" name="remarks"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ensure jQuery is loaded before Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <script type="text/javascript" defer>
        // $(window).load(function() {
            function openRemarksModal(action, requestId) {
                $('#actionType').val(action); // Set action type (approve/reject)
                $('#withdrawalRequestId').val(requestId); // Set request ID
                $('#remarksModal').modal('show'); // Show the modal
            }
            $(".approveBtn").click(function() {
                openRemarksModal('approve', $(this).data('withdraw'));
            });
            $(".rejectBtn").click(function() {
                openRemarksModal('reject', $(this).data('withdraw'));
            });

            // Submit form dynamically based on action type (approve/reject)
            $('#remarksForm').on('submit', function(e) {
                e.preventDefault();
                const actionType = $('#actionType').val();
                const formAction = actionType === 'approve' ? "{{ route('withdrawapproval') }}"
                                                            : "{{ route('withdrawreject') }}";
                $(this).attr('action', formAction); // Set form action dynamically
                this.submit(); // Submit the form
            });
        // })
    </script>

</x-app-layout>
