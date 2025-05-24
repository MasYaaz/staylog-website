<script>
  // Buat Import fontawesome
  import { FontAwesomeIcon } from "@fortawesome/svelte-fontawesome";
  import {
    faCircleUser,
    faAddressCard,
  } from "@fortawesome/free-solid-svg-icons";

  // Buat import inputfield (tempat input) pakai component
  import InputField from "../components/inputField.svelte";

  import { onMount } from "svelte";

  // ini buat navigasi
  import { goto } from '$app/navigation';

  // ini macam tipe data buat input inputfield
  let isLogin = true;
  let Username = "";
  let email = "";
  let password = "";
  let confirmPassword = "";

  // ini tipe data buat animasi
  let animate = false;
  let isBouncing = false;

  // Fungsi submit data
  function handleSubmit() {
    if (isLogin) {
      console.log("Login:", email, password);
    } else {
      console.log("Register:",Username, email, password, confirmPassword);
    }
  }

  // Fungsi buat gonta ganti form (antara login sama register)
  function toggleForm() {
    isLogin = !isLogin;
  }

  // Aktifkan animasi
  onMount(() => {
    // Trigger scale-rotate animation
    setTimeout(() => {
      animate = true;

      // Setelah transisi selesai, baru trigger bounce
      setTimeout(() => {
        triggerBounce();
      }, 100); // durasi transisi transform (harus sama)
    }, 10);
  });

  // fungsi aktifin animasi bounce
    function triggerBounce() {
    isBouncing = true;
    setTimeout(() => {
      isBouncing = false;
    }, 2000); // durasi bounce (harus sama dengan animasi CSS)
  }

</script>

<main class="min-h-screen flex items-center justify-center bg-light p-4">
  <div
    class="w-full max-w-md aspect-[3/4] relative [perspective:1000px] transition-transform duration-200 ease-in transform-gpu"
    class:scale-100={animate}
    class:scale-90={!animate}
    class:animate-bounce-once={isBouncing}
  >
    <!-- Flipper -->
    <div
      class="absolute inset-0 transition-transform duration-700 [transform-style:preserve-3d] {isLogin
        ? ''
        : 'rotate-y-180'}"
    >
      <!-- LOGIN SIDE -->
      <div
        class="absolute inset-0 backface-hidden rounded-4xl backdrop-blur-md bg-gradient-to-br from-brown1 to-brown2 shadow-brown1 shadow-xl/30 text-light p-6 flex flex-col justify-around"
      >
        <div
          class="flex flex-col items-center mt-10 justify-center gap-x-2 mb-4"
        >
          <FontAwesomeIcon
            icon={faCircleUser}
            class="fa-4x w-auto text-light hover:text-green1 hover:scale-105 transition-transform duration-300 "
          />
          <h2 class="text-4xl font-bold uppercase mt-3 hover:cursor-default">
            Login
          </h2>
          <h4 class="text-lg font-light uppercase hover:cursor-default">
            Your Self
          </h4>
        </div>
        <form
          on:submit|preventDefault={handleSubmit}
          class="space-y-4 flex flex-col items-center"
        >
          <InputField 
            type="email" 
            bind:value={email} 
            placeholder="Email" 
          />

          <InputField
            type="password"
            bind:value={password}
            placeholder="Password"
          />

          <button
            on:click={() => goto('/timeline')}
            type="submit"
            class="w-1/3 font-bold uppercase bg-green2 text-light py-2 rounded-full hover:scale-105 transition-transform hover:text-green1 hover:bg-light hover:cursor-pointer"
          >
            Login
          </button>
        </form>
        <p class="text-center text-sm mt-4 hover:cursor-default">
          Belum punya akun?
          <a href="#Register" on:click={toggleForm} class="underline hover:font-medium"
            >Daftar di sini</a
          >
        </p>
      </div>

      <!-- REGISTER SIDE -->
      <div
        class="absolute inset-0 backface-hidden rotate-y-180 rounded-4xl backdrop-blur-md bg-gradient-to-br from-brown1 to-brown2 shadow-brown1 shadow-xl/30 text-light p-6 flex flex-col justify-around"
      >
        <div
          class="flex flex-col items-center mt-10 justify-center gap-x-2 mb-4"
        >
          <FontAwesomeIcon
            icon={faAddressCard}
            class="fa-4x text-light hover:text-green1 hover:scale-105 transition-transform duration-300"
          />
          <h2 class="text-4xl font-bold uppercase mt-5 hover:cursor-default">
            Register
          </h2>
          <h4 class="text-lg font-light uppercase hover:cursor-default">
            Your Self
          </h4>
        </div>
        <form
          on:submit|preventDefault={handleSubmit}
          class="space-y-4 flex flex-col items-center"
        >
          <InputField
            type="text"
            bind:value={Username}
            placeholder="Username"
          />
          
          <InputField
            type="email" 
            bind:value={email} 
            placeholder="Email" 
          />

          <InputField
            type="password"
            bind:value={password}
            placeholder="Password"
          />

          <InputField
            type="password"
            bind:value={confirmPassword}
            placeholder="Confirm Password"
          />

          <button
            type="submit"
            class="w-1/3 font-bold uppercase bg-green2 text-light py-2 rounded-full hover:scale-105 transition-transform hover:text-green1 hover:bg-light hover:cursor-pointer"
          >
            Register
          </button>
        </form>
        <p class="text-center text-sm mt-4 hover:cursor-default">
          Sudah punya akun?
          <a href="#login" on:click={toggleForm} class="underline hover:font-medium"
            >Masuk di sini</a
          >
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
</style>