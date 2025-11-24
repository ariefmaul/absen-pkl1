<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="bg-green-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <h1 class="text-2xl font-serif font-bold tracking-wide">
        RPL SMK Negeri 2 Tasikmalaya
      </h1>
      <nav class="hidden md:flex space-x-6 text-sm">
        <a href="#" class="hover:underline">Beranda</a>
        <a href="#" class="hover:underline">Profil</a>
        <a href="#" class="hover:underline">Kontak</a>
      </nav>
    </div>
  </header>

  <!-- JUDUL -->
  <main class="flex flex-col items-center flex-grow p-10">
    <!-- Tambahan logo + judul -->
    <div class="flex items-center space-x-4 mb-6">
      <img src="https://img.icons8.com/color/96/000000/university.png" alt="Logo Universitas" class="w-14 h-14"/>
      <h2 class="text-3xl font-serif text-green-600 tracking-wide">
        Portal Aplikasi RPL
      </h2>
    </div>
    <!-- KETERANGAN -->
    <p class="text-gray-600 text-center max-w-2xl mb-12 leading-relaxed">
      Selamat datang di portal aplikasi digital SMK Negeri 2 Tasikmalaya.  
    </p>
    <div class="w-16 h-1 bg-green-600 rounded mb-12"></div>

    <!-- GRID MENU -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
      
      <!-- e-Arsip -->
      <a href="http://192.168.2.4:90" class="w-36 h-36 bg-gray-50 shadow-md rounded-2xl flex flex-col items-center justify-center hover:shadow-xl hover:scale-105 transition transform">
        <img src="https://img.icons8.com/ios-filled/80/4caf50/opened-folder.png" alt="e-Arsip" class="w-12 h-12 mb-3"/>
        <span class="text-base text-gray-700 font-medium">Absen PKL</span>
      </a>

      <!-- Absensi Digital -->
      <a href="http://192.168.2.4:100 " class="w-36 h-36 bg-gray-50 shadow-md rounded-2xl flex flex-col items-center justify-center hover:shadow-xl hover:scale-105 transition transform">
        <img src="https://img.icons8.com/ios-filled/80/2196f3/attendance-mark.png" alt="Absensi" class="w-12 h-12 mb-3"/>
        <span class="text-base text-gray-700 font-medium">Smartsen</span>
      </a>

      <!-- Laporan Keuangan -->
      <!-- <a href="https://earsip.mayasaribakti.ac.id/Laporan_keuangan" class="w-36 h-36 bg-gray-50 shadow-md rounded-2xl flex flex-col items-center justify-center hover:shadow-xl hover:scale-105 transition transform">
        <img src="https://img.icons8.com/ios-filled/80/f44336/commercial.png" alt="Keuangan" class="w-12 h-12 mb-3"/>
        <span class="text-base text-gray-700 font-medium">Laporan Keuangan</span>
      </a> -->

      <!-- Transkrip/SIAKAD -->
      <!-- <a href="https://earsip.mayasaribakti.ac.is/siakad" class="w-36 h-36 bg-gray-50 shadow-md rounded-2xl flex flex-col items-center justify-center hover:shadow-xl hover:scale-105 transition transform">
        <img src="https://img.icons8.com/ios-filled/80/9c27b0/report-card.png" alt="SIAKAD" class="w-12 h-12 mb-3"/>
        <span class="text-base text-gray-700 font-medium">SIAKAD</span>
      </a> -->

      <!-- Perpustakaan Digital -->
      <!-- <a href="https://earsip.mayasaribakti.ac.id/perpus" class="w-36 h-36 bg-gray-50 shadow-md rounded-2xl flex flex-col items-center justify-center hover:shadow-xl hover:scale-105 transition transform">
        <img src="https://img.icons8.com/ios-filled/80/ff9800/books.png" alt="Perpus" class="w-12 h-12 mb-3"/>
        <span class="text-base text-gray-700 font-medium">Perpustakaan</span>
      </a> -->

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-50 border-t text-center py-4 text-sm text-gray-500">
    &copy; 2025 Universitas Mayasari Bakti. All rights reserved.
  </footer>

</body>
</html>
