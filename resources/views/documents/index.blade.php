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
              @include('common.notify')
              <h2 class="pageheader-title">KYC Documents</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('document.index') }}" class="breadcrumb-link"></a>KYC </li>
                  </ol>
                </nav>
                <a href="{{ route('document.create') }}" class="btn btn-primary" style=" float:right">Add Document</a></td>
                <br>
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
                        <th>Document name</th>
                        <th>Document type</th>
                        <th>Preview</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $index => $document)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$document->name}}</td>
                            <td>{{$document->type}}</td>
                            <td>{{$document->image}}</td>
                            <td>
                            @if ($document->status == 0)
                            ENABLED
                            @else
                            DISABLED
                            @endif</td>
                            <td>
                                <form action="{{ route('document.destroy', $document->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('document.edit', $document->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach          
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Document name</th>
                        <th>Document type</th>
                        <th>Preview</th>
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
