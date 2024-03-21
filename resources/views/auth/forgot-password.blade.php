

<x-guest-layout>
<!-- ============================================================== -->
  <!-- forgot password  -->
  <!-- ============================================================== -->
  <div class="min-vh-100 d-flex align-items-center">
    <div class="splash-container">
      <div class="card shadow-sm">
        <div class="card-header text-center"><a href="">G-Pay</a><span
            class="splash-description">Please enter your user information.</span></div>
            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <div class="card-body">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <p>Don't worry, we'll send you an email to reset your password.</p>
            <div class="form-group mb-2">
              <input class="form-control" id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Your Email"
                autocomplete="off">
            </div>
            <button type="submit" class="btn btn-block btn-primary btn-xl">
            Reset Password
</button>
          </form>
        </div>
      <!--  <div class="card-footer text-center">
          <span>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></span>
        </div> -->
      </div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- end forgot password  -->
  <!-- ============================================================== -->
  </x-guest-layout>