<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pegawai - Sistem Manajemen Kepegawaian</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <?php $this->load->view('partials/sidebar'); ?>
  <div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
      <?php $this->load->view('partials/header', ['notifications' => $notifications ?? [], 'notif_count' => $notif_count ?? 0]); ?>
    </div>
    <main class="pt-24 p-6 flex-1 overflow-y-auto">
      <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-lg font-medium text-gray-800">Daftar Pegawai</h1>
            <p class="text-sm text-gray-500 mt-1">Total <?= count($employees) ?> pegawai terdaftar</p>
          </div>
          <a href="<?= base_url('employee/create'); ?>" 
             class="flex items-center px-3 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors text-sm">
            <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
            Tambah Pegawai
          </a>
        </div>
        <div class="p-6">
          <?php if ($this->session->flashdata('success')): ?>
            <div class="mb-4 px-4 py-2 border-l-4 border-green-400 bg-green-50 text-sm text-green-700">
              <?= $this->session->flashdata('success'); ?>
            </div>
          <?php endif; ?>
          <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table id="employeesTable" class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr class="text-left text-gray-700 border-b border-gray-200">
                  <th class="px-4 py-3 font-medium">No</th>
                  <th class="px-4 py-3 font-medium">Profil</th>
                  <th class="px-4 py-3 font-medium">Nama</th>
                  <th class="px-4 py-3 font-medium">Jabatan</th>
                  <th class="px-4 py-3 font-medium">Departemen</th>
                  <th class="px-4 py-3 font-medium">Gaji</th>
                  <th class="px-4 py-3 font-medium">Status</th>
                  <th class="px-4 py-3 font-medium text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php foreach ($employees as $index => $employee): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 text-gray-500"><?= $index + 1 ?></td>
                  <td class="px-4 py-3">
                    <div class="w-9 h-9 border border-gray-200 rounded-full overflow-hidden">
                      <img class="h-full w-full object-cover" src="<?= !empty($employee['profile_image']) ? base_url('uploads/profiles/' . $employee['profile_image']) : base_url('assets/img/default.png') ?>" alt="Profile">
                    </div>
                  </td>
                  <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($employee['full_name']) ?></td>
                  <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($employee['position']) ?></td>
                  <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($employee['department']) ?></td>
                  <td class="px-4 py-3 font-medium">Rp<?= number_format($employee['salary'], 0, ',', '.') ?></td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs <?= ($employee['status'] == 'active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                      <i data-lucide="circle" class="w-3 h-3 mr-1"></i>
                      <?= ucfirst($employee['status']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex justify-end space-x-2">
                      <a href="<?= base_url('employee/view/' . $employee['id']); ?>" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-md">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                      </a>
                      <a href="<?= base_url('employee/edit/' . $employee['id']); ?>" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-md">
                        <i data-lucide="edit" class="w-5 h-5"></i>
                      </a>
                      <a href="<?= base_url('employee/delete/' . $employee['id']); ?>" class="p-1.5 text-red-600 hover:bg-red-100 rounded-md" onclick="return confirm('Apakah Anda yakin?')">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
  <?php $this->load->view('partials/footer'); ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#employeesTable').DataTable({
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
          paginate: {
            previous: "‹",
            next: "›"
          }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        columnDefs: [{ orderable: false, targets: [1, 7] }],
        order: [[0, 'asc']]
      });
      lucide.createIcons();
    });
  </script>
</body>
</html>
