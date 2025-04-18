<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Pegawai - Sistem Manajemen Kepegawaian</title>
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
      <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-lg font-medium text-gray-800">Tambah Pegawai Baru</h1>
          <p class="text-sm text-gray-500 mt-1">Isi formulir berikut untuk menambahkan pegawai baru</p>
        </div>
        <a href="<?= base_url('employee') ?>" 
           class="flex items-center px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-sm">
          <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
          Kembali ke Daftar
        </a>
      </div>
      <form method="POST" action="<?= base_url('employee/create') ?>" enctype="multipart/form-data" class="p-6 space-y-6">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
              <input type="text" name="full_name" value="<?= set_value('full_name') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('full_name', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
              <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="male" <?= set_select('gender', 'male') ?>>Laki-laki</option>
                <option value="female" <?= set_select('gender', 'female') ?>>Perempuan</option>
              </select>
              <?= form_error('gender', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
              <input type="date" name="dob" value="<?= set_value('dob') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('dob', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
              <input type="tel" name="phone" value="<?= set_value('phone') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('phone', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>
          </div>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
              <input type="text" name="position" value="<?= set_value('position') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('position', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
              <input type="text" name="department" value="<?= set_value('department') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('department', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Gaji</label>
              <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                <input type="number" name="salary" value="<?= set_value('salary') ?>" 
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              </div>
              <?= form_error('salary', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Kerja</label>
              <input type="date" name="hire_date" value="<?= set_value('hire_date') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('hire_date', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
          <textarea name="address" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500"><?= set_value('address') ?></textarea>
          <?= form_error('address', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
        </div>
        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide mb-6">Informasi Akun</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
              <input type="text" name="username" value="<?= set_value('username') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('username', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input type="email" name="email" value="<?= set_value('email') ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('email', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <input type="password" name="password" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <?= form_error('password', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
              <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Pilih Role</option>
                <option value="admin" <?= set_select('role', 'admin') ?>>HR Admin</option>
                <option value="employee" <?= set_select('role', 'employee') ?>>Pegawai</option>
                <option value="manager" <?= set_select('role', 'manager') ?>>Manajer</option>
              </select>
              <?= form_error('role', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
              <div class="relative border border-gray-300 rounded-md px-3 py-2 hover:border-blue-500">
                <input type="file" name="profile_image"
                      class="w-full opacity-0 absolute inset-0 cursor-pointer"
                      accept="image/jpeg, image/png"
                      onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Unggah foto profil'">
                <div class="flex items-center text-gray-500">
                  <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                  <span id="file-name" class="truncate">Unggah foto profil</span>
                </div>
              </div>
              <small class="text-gray-500 mt-1 block">Format: JPEG/PNG (Maks. 2MB)</small>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row justify-end gap-3">
          <button type="submit" 
                  class="flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors">
            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
            Simpan Data
          </button>
          <a href="<?= base_url('employee') ?>" 
             class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-center">
            Batal
          </a>
        </div>
      </form>
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