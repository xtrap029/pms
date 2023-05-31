@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="employeeNo" class="col-md-4 col-form-label text-md-end">Employee Number</label>

                            <div class="col-md-6">
                                <input id="employeeNo" type="text" class="form-control @error('employee_no') is-invalid @enderror" name="employee_no" value="{{ old('employee_no') }}" required>

                                @error('employee_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">First Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control formAutoFill" id="formFname" placeholder="No Employee Found" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Last Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control formAutoFill" id="formLname" placeholder="No Employee Found" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Position</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control formAutoFill" id="formPos" placeholder="No Employee Found" disabled>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="formSubmit" class="btn btn-primary" disabled>
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('internal-scripts')
<script>
    $(document).ready( function () {
        async function logJSONData() {
            $('#formSubmit').prop('disabled', true);

            if ($('#employeeNo').val() !== '') {
                const response = await fetch("/api/employee-number/"+$('#employeeNo').val());
                const jsonData = await response.json();

                if (jsonData.data.length === 0) {
                    $('.formAutoFill').val('');
                } else {
                    const data = jsonData.data;
                    
                    $('#formFname').val(data[0].first_name);
                    $('#formLname').val(data[0].last_name);
                    $('#formPos').val(data[0].user_position.name);

                    $('#formSubmit').prop('disabled', false);
                }
            } else {
                $('.formAutoFill').val('');
            }
        }

        function delay(fn, ms) {
            let timer = 500;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(fn.bind(this, ...args), ms || 500);
            }
        }

        $('#employeeNo').keyup(delay(logJSONData));
        if ($('#employeeNo').val() !== '') {
            logJSONData();
        }
    } );
</script>
@endsection