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
              <h2 class="pageheader-title">Banners</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('banner.index') }}" class="breadcrumb-link"></a>Banners </li>
                  </ol>
                </nav>
                <a href="{{ route('banner.create') }}" class="btn btn-primary" style=" float:right">Add Banners</a></td>
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
                        <th>Banner image</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $index => $banner)
                        <tr>
                            <td>{{$index + 1}}</td>
                            
                            <td><img style="height: 83px; margin-bottom: 13px;padding: 0px 60px;" src="{{img($banner->image)}}"></td>
                            <td>
                              {{ $banner->status}}
                            </td>
                            <td>
                                <form action="{{ route('banner.destroy', $banner->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('banner.edit', $banner->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach          
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Banner image</th>
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
