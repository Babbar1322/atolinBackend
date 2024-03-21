

<!-- login page  -->
  <!-- ============================================================== -->
  <x-guest-layout>
  <div class="min-vh-100 d-flex align-items-center">
    <div class="splash-container">
      <div class="card shadow-sm">
        <div class="card-header text-center">
          <img src="{{ img(Setting::get('site_logo')) }}"> <span
            class="splash-description">Please enter your admin credentials.</span></div>
            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <div class="card-body">
@include('common.notify')
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-2">
              <input class="form-control" id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email" autocomplete="off">
            </div>
            <div class="form-group mb-2">
              <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password">
            </div>
            <div class="form-group">
              <label class="custom-control custom-checkbox">
                <input class="custom-control-input" id="remember_me" name="remember" type="checkbox"><span class="custom-control-label">Remember
                  Me</span>
              </label>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block" >Sign in</button>
            
          </form>
        </div>
        <div class="card-footer bg-white p-0  ">
         <!-- <div class="card-footer-item card-footer-item-bordered border-right d-inline-block  ">
            <a href="{{ route('register') }}" class="footer-link">Create An Account</a></div> -->
          <div class="card-footer-item card-footer-item-bordered d-inline-block ">
            <a href="{{ route('password.request') }}" class="footer-link">Forgot Password</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</x-guest-layout>
  <!-- ============================================================== -->
  <!-- end login page  -->
