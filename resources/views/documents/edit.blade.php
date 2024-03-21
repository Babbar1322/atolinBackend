<?php use \App\Http\Controllers\DashboardController; 
use App\Models\User;
?>

<style>
    .form-control{
        width: 148%!important;
    }
    .form-group.row {
    padding: 45px 383px!important;
    }
    .form-control-file, .form-control-range{
    padding: 5px!important;
    }
    </style>
<x-app-layout>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding: 26px">
          <div class="page-header">
            @include('common.notify')
            <h2 class="pageheader-title">KYC Documents</h2>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/document" class="breadcrumb-link">Documents</a></li>
                  <li class="breadcrumb-item"><a href="/document" class="breadcrumb-link"></a>Edit Document</li>

                </ol>
              </nav>
            </div>
            
          </div>
        </div>
      </div>
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <form class="form-horizontal" action="{{route('document.update', $document->id ) }}" method="POST" enctype="multipart/form-data" role="form">
					{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">&emsp;Document Name</label>
                        &emsp;
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ $document->name }}" name="name" required id="name" placeholder="Document Name">
                        </div>
                    </div>
    
					<div class="form-group row">
                        &emsp;
                        <label for="order" class="col-xs-12 col-form-label">Document Type</label>
                        &emsp;&nbsp;&emsp;
                        <div class="col-xs-10">
                            <select id="country" class="form-control" name="type" required>
								@if($document->type == "Mandatory")
								<option value="Mandatory" >MANDATORY</option>
                                <option value="Optional"> OPTIONAL</option>
								@else
								<option value="Optional" >OPTIONAL</option>
                                <option value="Mandatory" >MANDATORY</option>
								@endif
                                
                            </select>
                        </div>
                    </div>
    
                    <div class="form-group row">
                        
                        <label for="image" class="col-xs-12 col-form-label">&emsp;Document Image </label>
                        &emsp;
                        <div class="col-xs-10">
    
                            <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
                        </div>
                    </div>
    
                    <div class="form-group row">
                        &emsp;
                        <label for="order" class="col-xs-12 col-form-label">Status</label>
                        &emsp;&nbsp;&emsp;&nbsp;&emsp;&nbsp;&emsp;&nbsp;&emsp;&nbsp;
                        <div class="col-xs-10">
                            <select id="country" class="form-control" name="status" required>
								@if($document->status == 0)
								<option value="0" >ENABLE</option>
                                <option value="1"> DISABLE</option>
								@else
								<option value="1" >DISABLE</option>
                                <option value="0" >ENABLE</option>
								@endif
                                
                            </select>
                        </div>
                    </div>
    
                    <div class="form-group row">
                        &emsp;
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <a href="{{url('/document')}}" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
