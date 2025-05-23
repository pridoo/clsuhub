@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<!-- Form Section -->
<div class="left-section">
  <div class="glass-container">
    <h1>Register</h1>

    {{-- Validation Errors --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="signup-form" method="POST" action="{{ route('register') }}">
      @csrf

      <!-- Username -->
      <div class="form-group mb-3">
        <label for="name">name</label>
        <input
          type="text"
          class="form-control"
          id="name"
          name="name"
          placeholder="Enter name"
          required
          value="{{ old('name') }}"
        >
        <div class="invalid-feedback" id="name-feedback"></div>
      </div>

      <!-- Email -->
      <div class="form-group mb-3">
        <label for="email">Email</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          placeholder="Enter email"
          required
          value="{{ old('email') }}"
        >
        <div class="invalid-feedback" id="email-feedback"></div>
      </div>

      <!-- Password -->
      <div class="form-group mb-3">
        <label for="password">Password</label>
        <div class="password-container">
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Enter password"
            required
          >
          <i class="fa fa-eye-slash eye-icon" id="togglePassword"></i>
        </div>
        <div class="invalid-feedback" id="password-feedback"></div>
      </div>

      <!-- Confirm Password -->
      <div class="form-group mb-4">
        <label for="password_confirmation">Confirm Password</label>
        <div class="password-container">
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            name="password_confirmation"
            placeholder="Confirm password"
            required
          >
          <i class="fa fa-eye-slash eye-icon" id="toggleConfirmPassword"></i>
        </div>
        <div class="invalid-feedback" id="password-confirmation-feedback"></div>
      </div>

      <button
        type="submit"
        class="btn btn-danger"
        id="signup-button"
        disabled
      >Register</button>
    </form>

    <p class="mt-3">
      Already have an account?
      <a href="{{ route('login') }}" class="login-link">Login</a>
    </p>
    <a href="#" class="forgot-password-link" target="_blank">Forgot your password?</a>
  </div>
</div>

<!-- Logo and Text -->
<div class="right-section">
  <div class="logo-container">
    <img src="{{ asset('css/uploads/logo1.png') }}" alt="Logo 1" class="logo" />
  </div>
  <h2>Welcome to ConnectEd</h2>
  <p>Join our community by registering for an account.</p>
</div>

<script>
  // Toggle password visibility
  document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this;

    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  });

  document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const icon = this;

    const isPassword = confirmPasswordInput.type === 'password';
    confirmPasswordInput.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  });

  // Enable Register button only when form is valid
  const form = document.getElementById('signup-form');
  const button = document.getElementById('signup-button');

  function checkFormValidity() {
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const password = form.password.value;
    const passwordConfirmation = form.password_confirmation.value;
    const passwordFeedback = document.getElementById('password-confirmation-feedback');

    // Show password mismatch message
    if (password !== passwordConfirmation) {
      passwordFeedback.textContent = 'Passwords do not match';
    } else {
      passwordFeedback.textContent = '';
    }

    // Basic validation: all fields filled + passwords match
    const isValid = 
      name !== '' &&
      email !== '' &&
      password !== '' &&
      password === passwordConfirmation;

    button.disabled = !isValid;  // Enable button if valid, else disable
  }

  // Listen for input changes on the entire form
  form.addEventListener('input', checkFormValidity);
</script>

@endsection
