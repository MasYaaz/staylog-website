<script>
  import { FontAwesomeIcon } from '@fortawesome/svelte-fontawesome';
  import { faCircleUser, faAddressCard } from '@fortawesome/free-solid-svg-icons';
  import InputField from '../components/inputField.svelte';
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';

  // Toggle antara form Login / Register
  let isLogin = true;

  // State form input, menggunakan object agar rapi
  let form = {
    username: '',
    email: '',
    password: '',
    confirmPassword: ''
  };

  // State error untuk tiap field (atau global di key 'server')
  let errors = {
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
    server: ''
  };

  // Loading state untuk tombol submit
  let isLoading = false;

  // Animasi kartu
  let animate = false;
  let isBouncing = false;

  // Reset form dan errors dengan mudah
  function resetForm() {
    form = { username: '', email: '', password: '', confirmPassword: '' };
    errors = { username: '', email: '', password: '', confirmPassword: '', server: '' };
  }

  // Toggle form dan reset data
  function toggleForm() {
    isLogin = !isLogin;
    resetForm();
  }

  // Animasi bounce sekali (dipanggil saat mount dan toggle form)
  onMount(() => {
    setTimeout(() => {
      animate = true;
      triggerBounce();
    }, 50);
  });

  function triggerBounce() {
    isBouncing = true;
    setTimeout(() => (isBouncing = false), 2000);
  }

  // Validasi form Login
  function validateLogin() {
    const err = {};
    if (!form.email) err.email = 'Email harus diisi.';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) err.email = 'Format email tidak valid.';
    if (!form.password) err.password = 'Password harus diisi.';
    return err;
  }

  // Validasi form Register
  function validateRegister() {
    const err = {};
    if (!form.username) err.username = 'Username harus diisi.';
    if (!form.email) err.email = 'Email harus diisi.';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) err.email = 'Format email tidak valid.';
    if (!form.password) err.password = 'Password harus diisi.';
    if (!form.confirmPassword) err.confirmPassword = 'Konfirmasi password harus diisi.';
    else if (form.password !== form.confirmPassword)
      err.confirmPassword = 'Konfirmasi password tidak cocok.';
    return err;
  }

  // Validasi individual untuk onBlur (untuk validasi saat blur input)
  // @ts-ignore
  function validateField(field) {
    let error = '';
    switch (field) {
      case 'username':
        if (!form.username) error = 'Username harus diisi.';
        break;
      case 'email':
        if (!form.email) error = 'Email harus diisi.';
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) error = 'Format email tidak valid.';
        break;
      case 'password':
        if (!form.password) error = 'Password harus diisi.';
        break;
      case 'confirmPassword':
        if (!form.confirmPassword) error = 'Konfirmasi password harus diisi.';
        else if (form.password !== form.confirmPassword)
          error = 'Konfirmasi password tidak cocok.';
        break;
    }
    errors = { ...errors, [field]: error };
  }

  // Submit form handler
  async function handleSubmit() {
    // Reset error global dan per field
    errors = { username: '', email: '', password: '', confirmPassword: '', server: '' };

    // Validasi lengkap sesuai form
    const validationErrors = isLogin ? validateLogin() : validateRegister();

    // Jika ada error, set ke errors dan batalkan submit
    if (Object.keys(validationErrors).length > 0) {
      errors = { ...errors, ...validationErrors };
      return;
    }

    isLoading = true;

    // Tentukan URL dan data request sesuai form
    const url = isLogin
      ? 'http://localhost/staylog-website/public/api/login.php'
      : 'http://localhost/staylog-website/public/api/register.php';

    const data = isLogin
      ? { email: form.email, password: form.password }
      : { username: form.username, email: form.email, password: form.password, confirmPassword: form.confirmPassword };

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      const result = await res.json();

      if (result.success) {
        // Simpan user dan redirect
        localStorage.setItem('user', JSON.stringify(result.user));
        goto('/timeline');
      } else {
        // Jika backend mengirim error per field
        if (result.errors) {
          errors = { ...errors, ...result.errors };
        } else {
          // Error global
          errors.server = result.message || 'Terjadi kesalahan server.';
        }
      }
    } catch (e) {
      errors.server = 'Gagal terhubung ke server.';
    } finally {
      isLoading = false;
    }
  }
</script>

<main class="min-h-screen flex items-center justify-center bg-light p-4">
  <div
    class="w-full max-w-md aspect-[3/4] relative [perspective:1000px] transition-transform duration-200 ease-in transform-gpu"
    class:scale-100={animate}
    class:scale-90={!animate}
    class:animate-bounce-once={isBouncing}
  >
    <div
      class="absolute inset-0 transition-transform duration-700 [transform-style:preserve-3d]"
      class:rotate-y-180={!isLogin}
    >
      <!-- LOGIN SIDE -->
      <div
        class="absolute inset-0 backface-hidden rounded-4xl bg-gradient-to-br from-brown1 to-brown2 shadow-brown1 shadow-xl/30 text-light p-6 flex flex-col justify-around"
      >
        <div class="flex flex-col items-center mt-10 gap-x-2 mb-4">
          <FontAwesomeIcon
            icon={faCircleUser}
            class="fa-4x text-light hover:text-green1 hover:scale-105 transition-transform duration-300"
          />
          <h2 class="text-4xl font-bold uppercase mt-3">Login</h2>
          <h4 class="text-lg font-light uppercase">Your Self</h4>
        </div>

        <form on:submit|preventDefault={handleSubmit} class="space-y-4 flex flex-col items-center w-full relative">
          <!-- Input Login -->
          <InputField
            type="email"
            bind:value={form.email}
            placeholder="Email"
            error={errors.email}
            clearError={() => (errors.email = '')}
            validate={() => validateField('email')}
          />

          <InputField
            type="password"
            bind:value={form.password}
            placeholder="Password"
            error={errors.password}
            clearError={() => (errors.password = '')}
            validate={() => validateField('password')}
          />

          <button
            type="submit"
            class="w-full sm:w-1/3 font-bold uppercase bg-green2 text-light py-2 rounded-full hover:scale-105 transition-transform hover:text-green1 hover:bg-light hover:cursor-pointer disabled:opacity-50"
            disabled={isLoading}
          >
            {#if isLoading}Loading...{:else}Login{/if}
          </button>

          {#if errors.server}
            <div
              class="absolute mt-2 bottom-[-3.5rem] text-center bg-red-500 text-white text-xs rounded-md px-3 py-2 shadow-lg z-10 animate-fadeIn"
              role="alert"
              aria-live="assertive"
            >
              {errors.server}
            </div>
          {/if}
        </form>

        <p class="text-center text-sm mt-4">
          Belum punya akun?
          <a href="#Register" on:click={toggleForm} class="underline hover:font-medium">Daftar di sini</a>
        </p>
      </div>

      <!-- REGISTER SIDE -->
      <div
        class="absolute inset-0 backface-hidden rotate-y-180 rounded-4xl bg-gradient-to-br from-brown1 to-brown2 shadow-brown1 shadow-xl/30 text-light p-6 flex flex-col justify-around"
      >
        <div class="flex flex-col items-center mt-10 gap-x-2 mb-4">
          <FontAwesomeIcon
            icon={faAddressCard}
            class="fa-4x text-light hover:text-green1 hover:scale-105 transition-transform duration-300"
          />
          <h2 class="text-4xl font-bold uppercase mt-5">Register</h2>
          <h4 class="text-lg font-light uppercase">Your Self</h4>
        </div>

        <form on:submit|preventDefault={handleSubmit} class="space-y-4 flex flex-col items-center w-full relative">
          <!-- Input Register -->
          <InputField
            type="text"
            bind:value={form.username}
            placeholder="Username"
            error={errors.username}
            clearError={() => (errors.username = '')}
            validate={() => validateField('username')}
          />
          <InputField
            type="email"
            bind:value={form.email}
            placeholder="Email"
            error={errors.email}
            clearError={() => (errors.email = '')}
            validate={() => validateField('email')}
          />
          <InputField
            type="password"
            bind:value={form.password}
            placeholder="Password"
            error={errors.password}
            clearError={() => (errors.password = '')}
            validate={() => validateField('password')}
          />
          <InputField
            type="password"
            bind:value={form.confirmPassword}
            placeholder="Confirm Password"
            error={errors.confirmPassword}
            clearError={() => (errors.confirmPassword = '')}
            validate={() => validateField('confirmPassword')}
          />

          <button
            type="submit"
            class="w-full sm:w-1/3 font-bold uppercase bg-green2 text-light py-2 rounded-full hover:scale-105 transition-transform hover:text-green1 hover:bg-light hover:cursor-pointer disabled:opacity-50"
            disabled={isLoading}
          >
            {#if isLoading}Loading...{:else}Register{/if}
          </button>

          {#if errors.server}
            <div
              class="absolute mt-2 bottom-[-3.5rem] text-center bg-red-500 text-white text-xs rounded-md px-3 py-2 shadow-lg z-10 animate-fadeIn"
              role="alert"
              aria-live="assertive"
            >
              {errors.server}
            </div>
          {/if}
        </form>

        <p class="text-center text-sm mt-4">
          Sudah punya akun?
          <a href="#Login" on:click={toggleForm} class="underline hover:font-medium">Login di sini</a>
        </p>
      </div>
    </div>
  </div>
</main>

<style>
  .backface-hidden {
    backface-visibility: hidden;
  }
  .rotate-y-180 {
    transform: rotateY(180deg);
  }
  .animate-bounce-once {
    animation: bounce 1.4s ease forwards;
  }
  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0);
    }
    40% {
      transform: translateY(-6%);
    }
    60% {
      transform: translateY(-3%);
    }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease forwards;
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
</style>
