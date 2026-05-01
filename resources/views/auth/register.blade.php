<form method="POST" action="/register">
    @csrf

    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required>
    @error('name') <span>{{ $message }}</span> @enderror

    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
    @error('email') <span>{{ $message }}</span> @enderror

    <input type="password" name="password" placeholder="Password" required>
    @error('password') <span>{{ $message }}</span> @enderror

    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit">Register</button>
</form>