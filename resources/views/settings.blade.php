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
              <h2 class="pageheader-title">Site Settings</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link">Site Settings</a></li>
                  </ol>
                </nav>
              </div>
            </div>
        </div>
    </div>
    @include('common.notify')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">

                <form class="form-horizontal" action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label" style="padding: 9px 6px;">Site Name</label>
                        &emsp;
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="name" required id="name" placeholder="Site Name" value="{{ Setting::get('site_name')}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        &nbsp;
                        <label for="site_logo" class="col-xs-2 col-form-label">Site  Logo</label>
                        <div class="col-xs-10">
                            @if(\Setting::get('site_logo')!='')
                            <img style="height: 83px; margin-bottom: 13px;padding: 0px 60px;" src="{{ img(Setting::get('site_logo')) }}">
                            @endif
                            <input type="file" accept="image/*" name="site_logo"  class="dropify form-control-file" id="logo" aria-describedby="fileHelp" style="padding: 1px 52px!important">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">Copyright content</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="copyright_content" required id="copyright_content" placeholder="Copyright content" value="{{ Setting::get('copyright_content', '2021')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="withdraw_fees" class="col-xs-12 col-form-label">Withdraw Fees (%)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="number" name="withdraw_fees" required id="copyright_content" placeholder="Withdraw Fees (%)" value="{{ Setting::get('withdraw_fees')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="bank_fee" class="col-xs-12 col-form-label">Deposit Fees for Bank (%)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="bank_fee" required id="bank_fee" placeholder="Desposit Fees (%)" value="{{ Setting::get('bank_fee')  }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="card_fee" class="col-xs-12 col-form-label">Deposit Fees for Card (%)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="card_fee" required id="card_fee" placeholder="Desposit Fees (%)" value="{{ Setting::get('card_fee')  }}">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">&nbsp;Referral Bonus</label>

                        <div class="col-xs-10" style="padding: 0px 27px">
                            <input class="form-control" type="text" name="referral_bonus" required id="referral_bonus" placeholder="Referral bonus" value="{{ Setting::get('referral_bonus', '20')  }}">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="submit" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{url('/dashboard')}}" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
