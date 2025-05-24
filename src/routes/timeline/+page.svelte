<script>
  let items = Array.from({ length: 50 }, (_, i) => `Konten ${i + 1}`);
  import animate from "../+layout.svelte";
  import isBouncing from "../+layout.svelte"

  // ini buat navigasi
  import { goto } from '$app/navigation';
</script>

<main class="flex flex-col bg-light md:flex-row h-screen overflow-y-auto">
  <!-- SIDEBAR -->
  <div class="w-full md:w-2/7 backdrop-blur-md bg-gradient-to-br from-brown1 to-brown2 text-white p-5 flex flex-col items-center flex-shrink-0 md:sticky md:top-0">
    <div
    class="w-full h-full flex flex-col rounded-4xl items-center max-w-md relative pt-10 p-6 transition-transform duration-200 ease-in transform-gpu"
    class:scale-100={animate}
    class:scale-90={!animate}
    class:animate-bounce-once={isBouncing}
  >
    <h2 class="text-3xl uppercase font-bold mb-6 text-light text-center">Akun Kamu</h2>
    <div class="w-[150px] h-[200px] bg-light rounded-full mb-6"></div>

    <h3 class="text-xl font-medium text-light">Username:</h3>
    <div class="h-[2px] w-1/2 bg-light my-1"></div>
    <p class="text-lg font-regular text-light mb-3">Mas_Yaaz22</p>

    <h3 class="text-xl font-medium text-light">Email:</h3>
    <div class="h-[3px] w-1/2 bg-light my-1"></div>
    <p class="text-md font-regular text-light mb-3">Diyaz.hal22@gmail.com</p>

    <h3 class="text-xl font-medium text-light">Address:</h3>
    <div class="h-[2px] w-1/2 bg-light my-1"></div>
    <p class="text-md font-regular text-light text-center">
      Jl. kemlaten IV/20, Kel. Kebraon, Kec. Karang Pilang, Surabaya, Jawa Timur 60222
    </p>

    <button 
    on:click={() => goto('/')}
    class="w-30 h-10 bg-green1 mt-10 rounded-3xl hover:cursor-pointer 
    uppercase font-bold hover:bg-light hover:text-green1 hover:scale-105 
    transition-transform duration-300 ">Log Out</button>
  </div>
    </div>

  <!-- KONTEN -->
  <div class="w-full md:w-3/4 p-6">
    <h1 class="text-3xl font-bold text-green1 uppercase mb-4">Konten Utama</h1>
    <div class="space-y-4">
      {#each items as item}
        <div class="bg-gray-100 p-4 rounded shadow">{item}</div>
      {/each}
    </div>
  </div>
</main>
