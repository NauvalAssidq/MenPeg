<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pegawai - Sistem Manajemen Kepegawaian</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <?php $this->load->view('partials/sidebar'); ?>
  <div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
      <?php $this->load->view('partials/header'); ?>
    </div>
    <main class="pt-24 p-6 flex-1 overflow-y-auto">
      <div class="bg-white border border-gray-200 rounded-lg">
        <!-- Header Section -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-lg font-medium text-gray-800">Detail Pegawai</h1>
            <p class="text-sm text-gray-500 mt-1">ID: <?= $employee['id'] ?></p>
          </div>
          <a href="<?= base_url('employee') ?>" class="flex items-center px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Daftar
          </a>
        </div>

        <!-- ID Card Content -->
        <div class="p-6">
          <!-- Profile Section -->
          <div class="flex flex-col md:flex-row items-start gap-6 pb-6 border-b border-gray-200">
            <div class="w-24 h-24 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50">
              <img src="<?= base_url('uploads/profiles/' . $employee['profile_image']) ?>" class="w-full h-full object-cover" alt="Profile">
            </div>
            <div class="flex-1 space-y-3">
              <div class="flex items-center gap-3">
                <h2 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($employee['full_name']) ?></h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($employee['status'] == 'active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                  <i data-lucide="circle" class="w-3 h-3 mr-1"></i>
                  <?= ucfirst($employee['status']) ?>
                </span>
              </div>
              <div class="flex items-center gap-2 text-gray-600">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
                <p class="font-medium"><?= htmlspecialchars($employee['position']) ?></p>
              </div>
            </div>
          </div>

          <!-- Two Column Layout -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
            <!-- Employee Information -->
            <div class="space-y-6">
              <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide mb-4">Informasi Pribadi</h3>
                <div class="space-y-4">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-sm text-gray-500 mb-1">Departemen</p>
                      <p class="text-gray-800"><?= htmlspecialchars($employee['department']) ?></p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 mb-1">Bergabung</p>
                      <p class="text-gray-800"><?= date('d M Y', strtotime($employee['hire_date'])) ?></p>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-sm text-gray-500 mb-1">Gaji Pokok</p>
                      <p class="text-gray-800 font-medium">Rp<?= number_format($employee['salary'], 0, ',', '.') ?></p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 mb-1">Lokasi Kerja</p>
                      <p class="text-gray-800">Kantor Pusat</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 mb-1">Alamat</p>
                      <p class="text-gray-800"><?= htmlspecialchars($employee['address']) ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Account Information -->
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-800 uppercase tracking-wide mb-4">
                <i data-lucide="shield" class="w-5 h-5"></i>
                Informasi Akun
              </h3>
              <div class="space-y-4">
                <div>
                  <p class="text-sm text-gray-500 mb-1">Username</p>
                  <p class="text-gray-800 font-mono"><?= htmlspecialchars($employee['username']) ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 mb-1">Email</p>
                  <p class="text-gray-800"><?= htmlspecialchars($employee['email']) ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 mb-1">Role</p>
                  <p class="text-gray-800 capitalize"><?= htmlspecialchars($employee['role']) ?></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="pt-6 border-t border-gray-200 mt-6 flex flex-col sm:flex-row justify-end gap-3">
            <a href="<?= base_url('employee/edit/' . $employee['id']) ?>" class="flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors">
              <i data-lucide="edit-2" class="w-5 h-5 mr-2"></i>
              Edit Data
            </a>
            <a href="<?= base_url('employee') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-center">
              Tutup Detail
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>
  <?php $this->load->view('partials/footer'); ?>
  <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
  <script>
    lucide.createIcons();
  </script>
</body>
</html>