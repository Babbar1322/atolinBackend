<?php
use \App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Encryption\Encryption;
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
              <h2 class="pageheader-title">Token Settings</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard" class="breadcrumb-link">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="" class="breadcrumb-link">Token Settings</a></li>
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

                <form class="form-horizontal" action="{{ route('tokensettings.store') }}" method="POST" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="token_address" class="col-xs-12 col-form-label" style="padding: 9px 6px;">Token Address</label>
                        &emsp;
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="token_address" required id="token_address" placeholder="0x......" value="{{ Setting::get('token_address') }}">
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        &nbsp;
                        <label for="token_network" class="col-xs-2 col-form-label">Token Network</label>
                        <div class="col-xs-10 pl-4">
                            <select class="custom-select" name="token_network">
                                <option value="" disabled>Select one</option>
                                <option value="live_net" {{ Setting::get('token_network') === 'live_net' ? "selected" : '' }}>Live Net</option>
                                <option value="test_net" {{ Setting::get('token_network') === 'test_net' ? "selected" : '' }}>Test Net</option>
                              </select>
                            {{-- <input type="radio" name="token_network" id="token_network_live" value="Live Net">
                            <input type="radio" name="token_network" id="token_network_test" value="Test Net"> --}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="wallet_address" class="col-xs-12 col-form-label">Wallet Address (Wallet That Contain Tokens)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="wallet_address" required id="wallet_address" placeholder="0x......" value="{{ Encryption::decrypt(Setting::get('wallet_address'))  }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="wallet_private" class="col-xs-12 col-form-label">Wallet Private Key (Wallet That Contain Tokens)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="wallet_private" required id="wallet_private" placeholder="Wallet Private" value="{{ Encryption::decrypt(Setting::get('wallet_private'))  }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="token_price" class="col-xs-12 col-form-label">Token Price ($)</label>
                        <div class="col-xs-10" style="padding: 0px 10px">
                            <input class="form-control" type="text" name="token_price" required id="token_price" placeholder="Desposit Fees (%)" value="{{ Setting::get('token_price')  }}">
                        </div>
                    </div>

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
