<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Sistem Manajemen Kepegawaian</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <?php $this->load->view('partials/sidebar'); ?>
  <div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
      <?php $this->load->view('partials/header', ['notifications' => $notifications ?? [], 'notif_count' => $notif_count ?? 0]); ?>
    </div>
    <main class="pt-24 p-6 flex-1 overflow-y-auto space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Pegawai</p>
              <p class="text-2xl font-semibold text-gray-900"><?= $total_employees ?></p>
            </div>
            <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
          </div>
          <div class="mt-3 border-t border-gray-100 pt-3">
            <span class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded">+2.5% dari bulan lalu</span>
          </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Pegawai Aktif</p>
              <p class="text-2xl font-semibold text-gray-900"><?= $active_employees ?></p>
            </div>
            <i data-lucide="user-check" class="w-8 h-8 text-green-600"></i>
          </div>
          <div class="mt-3 border-t border-gray-100 pt-3">
            <span class="text-sm text-green-600 bg-green-50 px-2 py-1 rounded">+5 dari minggu lalu</span>
          </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Departemen</p>
              <p class="text-2xl font-semibold text-gray-900">-</p>
            </div>
            <i data-lucide="briefcase" class="w-8 h-8 text-purple-600"></i>
          </div>
          <div class="mt-3 border-t border-gray-100 pt-3">
            <span class="text-sm text-purple-600 bg-purple-50 px-2 py-1 rounded">Update terakhir: 2h lalu</span>
          </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 mb-1">Pengajuan Cuti</p>
              <p class="text-2xl font-semibold text-gray-900"><?= $leave_requests ?></p>
            </div>
            <i data-lucide="clock" class="w-8 h-8 text-grey-600"></i>
          </div>
          <div class="mt-3 border-t border-gray-100 pt-3">
            <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">1 pengajuan disetujui </span>
          </div>
        </div>
      </div>
      <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-md font-medium text-gray-800">Presensi Cepat</h2>
            <p class="text-sm text-gray-500 mt-1">Status presensi hari ini</p>
          </div>
          <div class="flex gap-4 items-center">
            <?php if (empty($today_record['clock_in_time'])): ?>
              <form action="<?= base_url('attendance/checkin') ?>" method="post" class="flex items-center">
                <button type="submit" class="flex items-center px-4 py-2 border border-green-600 text-green-600 rounded-md hover:bg-green-50 text-sm transition-colors">
                  <i data-lucide="zap" class="w-5 h-5 mr-2"></i>Check In
                </button>
              </form>
            <?php elseif (empty($today_record['clock_out_time'])): ?>
              <form action="<?= base_url('attendance/checkout') ?>" method="post" class="flex items-center">
                <button type="submit" class="flex items-center px-4 py-2 border border-red-600 text-red-600 rounded-md hover:bg-red-50 text-sm transition-colors">
                  <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>Check Out
                </button>
              </form>
            <?php else: ?>
              <div class="flex items-center px-4 py-2 border border-gray-300 text-gray-600 rounded-md bg-gray-50 text-sm">
                <i data-lucide="check" class="w-5 h-5 mr-2 text-gray-500"></i>Presensi hari ini selesai
              </div>
            <?php endif; ?>
            <div class="text-sm text-gray-500">
              <?= !empty($today_record['clock_in_time']) ? 'Check In: '.date('H:i', strtotime($today_record['clock_in_time'])) : '' ?>
              <?= !empty($today_record['clock_out_time']) ? ' | Check Out: '.date('H:i', strtotime($today_record['clock_out_time'])) : '' ?>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-800">Aktivitas Terkini</h2>
          <p class="text-sm text-gray-500 mt-1">10 aktivitas terakhir</p>
        </div>
        <div class="p-6">
          <table id="activitiesTable" class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
              <tr class="border-b border-gray-200">
                <th class="px-4 py-3 font-medium text-left">Waktu</th>
                <th class="px-4 py-3 font-medium text-left">Aktivitas</th>
                <th class="px-4 py-3 font-medium text-left">Pengguna</th>
                <th class="px-4 py-3 font-medium text-left">Detail</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr>
                <td class="px-4 py-3">10:30 AM</td>
                <td class="px-4 py-3">Pegawai baru ditambahkan</td>
                <td class="px-4 py-3">John Doe</td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-xs">
                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>Detail
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
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
      $('#activitiesTable').DataTable({
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
          paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        order: [[0, 'desc']]
      });
      lucide.createIcons();
    });
  </script>
</body>
</html>
