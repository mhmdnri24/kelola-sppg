<form method="POST" action="/login">
    @csrf

    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
    @error('email') <span>{{ $message }}</span> @enderror

    <input type="password" name="password" placeholder="Password" required>

    <label>
        <input type="checkbox" name="remember"> Remember Me
    </label>

    <button type="submit">Login</button>
</form>