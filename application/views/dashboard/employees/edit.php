<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Pegawai - Sistem Manajemen Kepegawaian</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
          <h1 class="text-lg font-medium text-gray-800">Edit Data Pegawai</h1>
          <p class="text-sm text-gray-500 mt-1">ID Pegawai: <?= $employee['id'] ?></p>
        </div>
        <a href="<?= base_url('employee') ?>" 
           class="flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm">
          <span class="material-icons text-base mr-2">arrow_back</span>
          Kembali
        </a>
      </div>

      <!-- Form Content -->
      <form action="<?= base_url('employee/edit/' . $employee['id']); ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

        <?php if ($this->session->flashdata('error')): ?>
          <div class="px-4 py-2 border-l-4 border-red-400 bg-red-50 text-sm text-red-700">
            <?= $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
              <input type="text" name="full_name" value="<?= htmlspecialchars($employee['full_name']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              <?= form_error('full_name', '<small class="text-red-500 block mt-1 text-sm">', '</small>') ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
              <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="male" <?= ($employee['gender'] == 'male') ? 'selected' : '' ?>>Laki-laki</option>
                <option value="female" <?= ($employee['gender'] == 'female') ? 'selected' : '' ?>>Perempuan</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
              <input type="date" name="dob" value="<?= htmlspecialchars($employee['dob']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
              <input type="tel" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
              <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
              <input type="text" name="department" value="<?= htmlspecialchars($employee['department']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gaji</label>
              <input type="number" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="active" <?= ($employee['status'] == 'active') ? 'selected' : '' ?>>Aktif</option>
                <option value="inactive" <?= ($employee['status'] == 'inactive') ? 'selected' : '' ?>>Non-Aktif</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Address Section -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
          <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($employee['address']) ?></textarea>
        </div>

        <!-- Account Information -->
        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-md font-medium text-gray-800 mb-4">Informasi Akun</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
              <input type="text" name="username" value="<?= htmlspecialchars($employee['username']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
              <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="admin" <?= ($employee['role'] == 'admin') ? 'selected' : '' ?>>HR Admin</option>
                <option value="manager" <?= ($employee['role'] == 'manager') ? 'selected' : '' ?>>Manajer</option>
                <option value="employee" <?= ($employee['role'] == 'employee') ? 'selected' : '' ?>>Pegawai</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
              <input type="file" name="profile_image" 
                     class="w-full px-3 py-2 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0 file:text-sm file:font-medium
                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                     accept="image/jpeg, image/png">
              <small class="text-gray-500 text-sm mt-1 block">Biarkan kosong jika tidak ingin mengubah</small>
              <?php if (!empty($employee['profile_image'])): ?>
                <div class="mt-2 flex items-center space-x-2">
                  <img src="<?= base_url('uploads/profiles/' . $employee['profile_image']) ?>" 
                       alt="Foto Profil" 
                       class="h-12 w-12 rounded-full border-2 border-white shadow-sm">
                  <span class="text-sm text-gray-500">Foto saat ini</span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Form Footer -->
        <div class="border-t border-gray-200 pt-6 flex justify-end gap-3">
          <button type="submit" 
                  class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <span class="material-icons mr-2 text-base">save</span>
            Simpan Perubahan
          </button>
          <a href="<?= base_url('employee') ?>" 
             class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
            Batal
          </a>
        </div>
      </form>
    </div>
  </main>
</div>

<?php $this->load->view('partials/footer'); ?>
</body>
</html>