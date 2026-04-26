<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Central Cyber Security Office</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/js/app.js')
</head>
@include('components.header')

<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

  <main class="flex-1 p-2 relative">

    <div class="mb-4 inline-block border-b-2 border-white pr-4 pb-1">
      <h2 class="text-white text-lg">
        Welcome, <span class="text-[#4DA8DA] cursor-pointer hover:underline">Anonimous</span>!
      </h2>
    </div>

    <div id="pwd-alert" class="absolute top-4 right-2 w-64 bg-[#212121] border border-gray-500 rounded-xl p-5 shadow-2xl z-20">
      <p class="text-[13px] text-gray-300 mb-6 leading-relaxed">
        You must change your current password for a new one
      </p>
      <div class="flex justify-end gap-2">
        <button onclick="dismissAlert()" class="px-4 py-1.5 rounded-md text-xs bg-[#3A3D42] text-gray-300 hover:bg-gray-600 transition">
          Later
        </button>
        <a href="/changepw" class="px-4 py-1.5 rounded-md text-xs bg-[#00A8E8] text-white hover:bg-blue-500 transition">
          Change Now
        </a>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-2 mt-2">

      <div class="col-span-12 lg:col-span-4">
        <p class="text-sm mb-2 ml-1">Feature A</p>
        <div class="border border-white rounded-lg h-56 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature A</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-8">
        <p class="text-sm mb-2 ml-1">Feature B</p>
        <div class="border border-white rounded-lg h-56 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature B</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-5">
        <p class="text-sm mb-2 ml-1">Feature C</p>
        <div class="border border-white rounded-lg h-48 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature C</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-7">
        <p class="text-sm mb-2 ml-1">Feature D</p>
        <div class="border border-white rounded-lg h-48 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature D</span>
        </div>
      </div>

      <div class="col-span-12">
        <p class="text-sm mb-2 ml-1">Data Log</p>
        <div class="border border-white rounded-lg h-60 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Data Log</span>
        </div>
      </div>

    </div>
  </main>
@include('components.footer')
  <script>
    function dismissAlert() {
      const alert = document.getElementById('pwd-alert');
      alert.style.display = 'none';
    }
  </script>

</body>
</html>