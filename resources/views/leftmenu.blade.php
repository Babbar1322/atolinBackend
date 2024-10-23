 <!-- ============================================================== -->
    <!-- left sidebar -->
    <!-- ============================================================== -->
    @php
        function set_active($route)
        {
            return request()->route()->named($route) ? 'active' : '';
        }
    @endphp
    <div class="nav-left-sidebar sidebar-dark">
      <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
          <a class="d-xl-none d-lg-none text-white" href="#">Dashboard</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav flex-column">
              <li class="nav-divider">
                Menu
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('dashboard')}}" href="/dashboard"><i class="fas fa-chart-pie"></i>Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('usermanagement')}}" href="/usermanagement"><i class="fas fa-user"></i>User Management</a>
              </li>
             <li class="nav-item">
                <a class="nav-link {{set_active('transactions')}}" href="/transactions"><i class="fas fa-exchange-alt"></i>Transactions</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="{{ route('document.index') }}"><i class="fas fa-file-export"></i>KYC Documents</a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link {{set_active('withdrawrequests')}}" href="{{ route('withdrawrequests') }}"><i class="fas fa-donate"></i>Pending Withdraw Requests</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('confirmedwithdrawrequests')}}" href="{{ route('confirmedwithdrawrequests') }}"><i class="fas fa-donate"></i>Confirmed Withdraw Requests</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('sitesettings')}}" href="/sitesettings"><i class="fas fa-user-cog"></i>Site Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('tokensettings')}}" href="/tokensettings"><i class="fas fa-coins"></i>Token Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('gas-fee-history')}}" href="/gas-fee-history"><i class="fas fa-coins"></i>Gas Fee History</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('token-swap-history')}}" href="/token-swap-history"><i class="fas fa-exchange-alt"></i>Token Swap History</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{set_active('token-swap-fee-history')}}" href="/token-swap-fee-history"><i class="fas fa-retweet"></i>Token Swap Fees History</a>
              </li>

              <li class="nav-item">
                <a class="nav-link {{set_active('banner.index')}}" href="/banner"><i class="fa fa-audio-description"></i>Banner Images</a>
              </li>

            </ul>
          </div>
        </nav>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
