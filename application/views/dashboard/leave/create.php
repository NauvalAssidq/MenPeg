<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajukan Cuti</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50">
  
<div class="flex">
  <?php $this->load->view('partials/sidebar'); ?>

  <div class="ml-64 flex-1 flex flex-col min-h-screen">
    <?php $this->load->view('partials/header'); ?>
    
    <main class="p-6 flex-1 space-y-6">
      <!-- Form Container -->
      <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">Ajukan Permintaan Cuti</h1>
        
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
          <div class="mb-4">
            <div class="<?= $this->session->flashdata('success') ? 'border-green-400 bg-green-50 text-green-700' : 'border-red-400 bg-red-50 text-red-700' ?> 
              px-4 py-2 border-l-4 rounded-md text-sm">
              <?= $this->session->flashdata('success') ?: $this->session->flashdata('error') ?>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- Leave Request Form -->
        <?php echo form_open('leave/store'); ?>
          <div class="mb-4">
            <label for="leave_type" class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
            <select name="leave_type" id="leave_type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
              <option value="sick">Sakit</option>
              <option value="vacation">Cuti Tahunan</option>
              <option value="personal">Cuti Pribadi</option>
              <option value="other">Lainnya</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
          </div>
          
          <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
          </div>
          
          <div class="mb-4">
            <label for="reason" class="block text-sm font-medium text-gray-700">Alasan (Opsional)</label>
            <textarea name="reason" id="reason" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Masukkan alasan pengajuan cuti..."></textarea>
          </div>
          
          <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
              Kirim Permintaan
            </button>
          </div>
        <?php echo form_close(); ?>
      </div>
    </main>
  </div>
</div>

<?php $this->load->view('partials/footer'); ?>
</body>
</html>
