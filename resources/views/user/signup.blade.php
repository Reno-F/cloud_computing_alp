<x-template>

    <div class="d-flex justify-content-center">
        <div class="card" style="width:500px">
            <div class="card-header">
                Sign up
            </div>
            <div class="card-body">
                <form method="post" class="was-validated">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="text-danger">{{ $errors->first('name') }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="text-danger">{{ $errors->first('email') }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" required>
                        @error('password')
                        <div class="text-danger">{{ $errors->first('password') }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            Sign up
                        </button>
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-link w-100">
                        Log in
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-template>
