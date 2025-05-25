<script>
  import { FontAwesomeIcon } from '@fortawesome/svelte-fontawesome';
  import { faXmark } from '@fortawesome/free-solid-svg-icons';

  export let type = 'text';            // Jenis input (text, password, dll)
  export let placeholder = '';         // Placeholder input
  export let value = '';               // Bind value input
  export let name = '';                // Nama input (optional)
  export let error = '';               // Pesan error untuk input ini
  export let clearError = () => {};    // Fungsi untuk clear error dari parent
  export let validate = () => {};      // Fungsi validasi (dipanggil saat blur)

  let isPasswordVisible = false;       // State toggle visibility password
  let isFocused = false;                // State fokus input

  // Menentukan apakah error popup harus disembunyikan
  // Popup error hanya muncul saat input tidak fokus dan kosong
  $: hidePopup = isFocused || (value && value.trim().length > 0);

  // Jika tipe password dan password terlihat, input type jadi 'text'
  $: inputType = type === 'password' && isPasswordVisible ? 'text' : type;

  // Clear isi input dan panggil clearError untuk reset error
  function clearInput() {
    value = '';
    clearError();
  }

  // Saat input fokus, reset error
  function handleFocus() {
    isFocused = true;
    clearError();
  }

  // Saat blur, set fokus false dan panggil validasi onBlur
  function handleBlur() {
    isFocused = false;
    validate();
  }
</script>

<div class="relative w-full focus-within:scale-105 transition-transform duration-500">
  <!-- Input utama -->
  <input
    class="w-full px-3 py-2 rounded-2xl bg-light text-brown1 border border-gray-300 focus:outline-none"
    type={inputType}
    {placeholder}
    bind:value
    {name}
    on:focus={handleFocus}
    on:blur={handleBlur}
    on:input={clearError}
  />

  <!-- Popup error kecil di pojok kanan atas -->
  {#if error && !hidePopup}
    <div
      class="absolute -top-0 right-0 translate-y-[-100%] text-[10px] px-2 py-1 rounded-md bg-red-500 text-white shadow-sm animate-fadeIn"
      role="alert"
      aria-live="assertive"
    >
      {error}
    </div>
  {/if}

  <!-- Tombol Clear (X) muncul saat ada value -->
  {#if value}
    <button
      type="button"
      class="absolute top-1/2 -translate-y-1/2 text-brown1 hover:text-red-500 hover:scale-120 hover:cursor-pointer"
      class:right-9={type === 'password'}
      class:right-3={type !== 'password'}
      on:click={clearInput}
      aria-label="Clear input"
    >
      <FontAwesomeIcon icon={faXmark} />
    </button>
  {/if}

  <!-- Tombol toggle visibility password -->
  {#if type === 'password'}
    <button
      type="button"
      class="absolute right-3 top-1/2 -translate-y-1/2 text-brown1 hover:cursor-pointer hover:scale-105 hover:text-green1"
      on:click={() => (isPasswordVisible = !isPasswordVisible)}
      aria-label="Toggle password visibility"
      tabindex="-1"
    >
      {#if isPasswordVisible}
        <!-- Icon mata terbuka -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
      {:else}
        <!-- Icon mata tertutup -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-6-10-6a16.892 16.892 0 013.04-3.58m3.128-2.047A6.989 6.989 0 0112 5c5.523 0 10 6 10 6a16.888 16.888 0 01-1.637 2.223M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
        </svg>
      {/if}
    </button>
  {/if}
</div>

<style>
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-4px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
  }
</style>
