<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atolin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div class="container custom-container-lg my-3">
        @error('error')
            <div class="alert alert-danger dsfs">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
        {{-- @if(!$errors->isEmpty())
            @foreach ($errors->all() as $key => $err)
                <div class="alert alert-danger">
                    <strong>{{ $key }} {{ $err }}</strong>
                </div>
            @endforeach
        @endif --}}
        @if(session()->has('success'))
            <div class="alert alert-success 123">
                <strong>{{ session('success') }}</strong>
            </div>
        @endif
        <div class="card card-body mb-4 shadow">
            <div class="fs-3">Torus Technology - Passport Account</div>
            <hr class="my-1">
            <div class="fs-6">123 Easy Street</div>
            <div class="fs-6">Kingman, AZ 86401</div>
            <div class="fs-6">(314) 344-3344</div>
        </div>
        <form action="{{ url()->current() . '?' . http_build_query(request()->query()) }}" method="POST">
            @csrf
            <div class="card card-body shadow">
                <div class="mb-2 fs-3">Account Information</div>
                <div class="row align-items-center mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">Invoice ID <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('invoice_id') is-invalid @enderror" name="invoice_id" placeholder="Invoice ID" id="invoice_id" value="{{old('invoice_id', request()->get('InvoiceNumber'))}}" required>
                        @error('invoice_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">Payment Amount <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" placeholder="Payment Amount" id="amount" value="{{old('amount', request()->get('amt'))}}" required>
                        @error('amount')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="account-number" class="col-sm-4 col-form-label">Account Number <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" placeholder="Account Number" id="account-number" required value="{{ old('account_number') }}">
                        @error('account_number')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="holder-name" class="col-sm-4 col-form-label">Account Holder Name <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" name="account_holder_name" placeholder="Account Holder Name" id="holder-name" required value="{{ old('account_holder_name') }}">
                        @error('account_holder_name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="routing-number" class="col-sm-4 col-form-label">Routing Number <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('routing_number') is-invalid @enderror" name="routing_number" placeholder="Routing Number" id="routing-number" required value="{{ old('routing_number') }}">
                        @error('routing_number')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center">
                    <label for="account-type" class="col-sm-4 col-form-label">Account Type <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select type="text" class="form-select @error('account_type') is-invalid @enderror" name="account_type" placeholder="Account Type" id="account-type" required value="{{ old('account_type') }}">
                            <option value="">Select One</option>
                            <option value="Savings">Savings</option>
                            <option value="Checking">Checking</option>
                        </select>
                        @error('account_type')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="card card-body shadow mb-4">
                <div class="mb-2 fs-3">Billing Information</div>
                <div class="row align-items-center mb-3">
                    <label for="contact-name" class="col-sm-4 col-form-label">Contact Name <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" placeholder="Contact Name" id="contact-name" required value="{{ old('contact_name') }}">
                        @error('contact_name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="address" class="col-sm-4 col-form-label">Address <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" placeholder="Address" id="address" required value="{{ old('address') }}">
                        @error('address')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="address-2" class="col-sm-4 col-form-label">Address 2</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="address2" placeholder="Address 2" id="address-2" value="{{ old('address2') }}">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="city" class="col-sm-4 col-form-label">City <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" placeholder="City" id="city" required value="{{ old('city') }}">
                        @error('city')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="state" class="col-sm-4 col-form-label">State <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select type="text" class="form-select @error('state') is-invalid @enderror" name="state" placeholder="State" id="state" required>
                            <option value="">Select One</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DC">District of Columbia</option>
                            <option value="DE">Delaware</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                            <option value="AB">Alberta</option>
                            <option value="BC">British Colombia</option>
                            <option value="MB">Manitoba</option>
                            <option value="NB">New Brunswick</option>
                            <option value="NL">Newfoundland</option>
                            <option value="NS">Nova Scotia</option>
                            <option value="NT">NW Territories</option>
                            <option value="NU">Nunavut</option>
                            <option value="ON">Ontario</option>
                            <option value="PE">Prince Edward</option>
                            <option value="QC">Quebec</option>
                            <option value="SK">Saskatchewan</option>
                            <option value="YT">Yukon</option>
                        </select>
                        @error('state')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="zip" class="col-sm-4 col-form-label">Zip <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control @error('zip') is-invalid @enderror" name="zip" placeholder="Zip" id="zip" required value="{{ old('zip') }}">
                        @error('zip')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="mobile-number" class="col-sm-4 col-form-label">Mobile Number</label>
                    <div class="col-sm-8">
                        <input type="tel" class="form-control" name="phone" placeholder="Mobile Number" id="mobile-number" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="row align-items-center">
                    <label for="email-address" class="col-sm-4 col-form-label">Email Address</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" id="email-address" value="{{ old('email') }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-50">Submit Payment</button>
        </form>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            var amount = $('#amount');
            var valueAmt = amount.val();
            amount.val(valueAmt.replace(/[^0-9.]/gm, ''));
            // Attach keypress event to the input field
            amount.on('keypress', function(e) {
                if (isNaN(String.fromCharCode(e.which)) && e.which !== 8) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
