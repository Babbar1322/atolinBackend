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
              <h2 class="pageheader-title">User Kyc Details</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link"></a>User Kyc Details</li>
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
                        <th>Document name</th>
                        <th>Document Image</th>
                        <th>User preview</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($getKycdetails as $Index => $Document)
                        @if(!empty($Document->url))
                            <tr>
                                <td>{{ $Index + 1 }}</td>
                                <td><b>{{$Document->document->name}}</b></td>
                                <td><img  src="{{ asset('storage/') }}" class="avatar-xs rounded-circle mr-3 userlistimg"></td>
                                <td><img  src="{{ asset('storage/') }}" class="avatar-xs rounded-circle mr-3 userlistimg"></td>
                                <td><b>@if($Document->status == "DISAPPROVED")
                                        REJECTED
                                    @else
                                    {{ $Document->status}}
                                    @endif
                                </b>
                                </td>
                                <td>
                                    <div class="col-xs-6">
                                    <form action="{{ route('userdocument.approve')}}" method="POST">
                                        {{ csrf_field() }}
                                        
                                        <input type="hidden" name="status" value="APPROVED">
                                        <input type="hidden" name="user_id" value="{{$Document->user_id}}">
                                        <input type="hidden" name="doc_id" value="{{$Document->document_id}}">
                                        <button class="btn btn-block btn-success" type="submit">Approve</button>
                                    </form>
                                    </div>
                                    <br>

                                    <div class="col-xs-6">
                                        <form action="{{ route('userdocument.reject')}}" method="POST">
                                        {{ csrf_field() }}
                                        
                                        <input type="hidden" name="status" value="DISAPPROVED">
                                        <input type="hidden" name="user_id" value="{{$Document->user->id}}">
                                        <input type="hidden" name="doc_id" value="{{$Document->document_id}}">
                                        <button class="btn btn-block btn-danger" type="submit">Reject</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Document name</th>
                        <th>Document Image</th>
                        <th>User preview</th>
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
