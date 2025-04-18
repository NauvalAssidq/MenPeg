<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi - Sistem Manajemen Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<?php $this->load->view('partials/sidebar'); ?>
<div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
        <?php $this->load->view('partials/header', ['notifications' => $notifications ?? [], 'notif_count' => $notif_count ?? 0]); ?>
    </div>

    <main class="pt-24 p-6 flex-1 overflow-y-auto space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Presensi Harian</h1>
                    <p class="text-sm text-gray-500 mt-1">Riwayat kehadiran Anda</p>
                </div>

                <?php
                $today = date('Y-m-d');
                $today_record = null;
                if (!empty($records)) {
                    foreach ($records as $r) {
                        if ($r['date'] === $today) {
                            $today_record = $r;
                            break;
                        }
                    }
                }
                ?>

                <div class="flex gap-2">
                    <?php if (!$today_record): ?>
                        <a href="<?= base_url('attendance/checkin') ?>" 
                           class="flex items-center px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            <span class="material-icons mr-2 text-base">login</span> Check In
                        </a>
                    <?php elseif (empty($today_record['clock_out_time'])): ?>
                        <a href="<?= base_url('attendance/checkout') ?>" 
                           class="flex items-center px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                            <span class="material-icons mr-2 text-base">logout</span> Check Out
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
            <div class="px-6 pt-4">
                <div class="<?= $this->session->flashdata('success') ? 'border-l-4 border-green-400 bg-green-50 text-green-700' : 'border-l-4 border-red-400 bg-red-50 text-red-700' ?> px-4 py-2 text-sm">
                    <?= $this->session->flashdata('success') ?: $this->session->flashdata('error') ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="personalAttendanceTable" class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">Tanggal</th>
                                <th class="p-3 font-medium">Masuk</th>
                                <th class="p-3 font-medium">Keluar</th>
                                <th class="p-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($records)): ?>
                                <?php foreach ($records as $row): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-3"><?= date('d M Y', strtotime($row['date'])) ?></td>
                                    <td class="p-3"><?= $row['clock_in_time'] ? date('H:i', strtotime($row['clock_in_time'])) : '-' ?></td>
                                    <td class="p-3"><?= $row['clock_out_time'] ? date('H:i', strtotime($row['clock_out_time'])) : '-' ?></td>
                                    <td class="p-3">
                                    <?php
                                        $status = $row['status'];
                                        $badgeClass = [
                                            'present' => 'bg-green-100 text-green-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            'leave' => 'bg-yellow-100 text-yellow-800',
                                            'default' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $icon = [
                                            'present' => 'check_circle',
                                            'absent' => 'cancel',
                                            'leave' => 'event_available',
                                            'default' => 'help'
                                        ];
                                        $statusLabel = [
                                            'present' => 'Hadir',
                                            'absent' => 'Tidak Hadir',
                                            'leave' => 'Cuti',
                                            'default' => 'Tidak Diketahui'
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $badgeClass[$status] ?? $badgeClass['default'] ?>" title="<?= $statusLabel[$status] ?? $statusLabel['default'] ?>">
                                        <span class="material-icons mr-1 text-sm"><?= $icon[$status] ?? $icon['default'] ?></span>
                                        <?= $statusLabel[$status] ?? $statusLabel['default'] ?>
                                    </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-gray-500">Belum ada data presensi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if ($this->session->userdata('role') === 'admin'): ?>
        <!-- Admin Recap Card (DataTables Enabled) -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Rekap Harian Pegawai</h1>
                    <p class="text-sm text-gray-500 mt-1">Data presensi tanggal <?= isset($date) ? date('d M Y', strtotime($date)) : date('d M Y') ?></p>
                </div>
                <form method="get" action="<?= base_url('attendance') ?>" class="flex items-center gap-3">
                    <input type="date" name="date" value="<?= $date ?? date('Y-m-d') ?>" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Admin Recap Table with DataTables -->
            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="adminRecapTable" class="w-full text-sm data-table">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">No</th>
                                <th class="p-3 font-medium">Profil</th>
                                <th class="p-3 font-medium">Nama</th>
                                <th class="p-3 font-medium">Masuk</th>
                                <th class="p-3 font-medium">Keluar</th>
                                <th class="p-3 font-medium">Status</th>
                                <th class="p-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recap)): ?>
                                <?php foreach ($recap as $index => $item): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-3"><?= $index + 1 ?></td>
                                    <td class="p-3">
                                        <div class="w-8 h-8 border border-gray-200 rounded-full overflow-hidden">
                                            <img src="<?= base_url('uploads/profiles/' . $item['employee']['profile_image']) ?>" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.src='<?= base_url('assets/img/default.png') ?>'">
                                        </div>
                                    </td>
                                    <td class="p-3"><?= htmlspecialchars($item['employee']['full_name']) ?></td>
                                    <td class="p-3"><?= $item['checkin'] ? date('H:i', strtotime($item['checkin'])) : '-' ?></td>
                                    <td class="p-3"><?= $item['checkout'] ? date('H:i', strtotime($item['checkout'])) : '-' ?></td>
                                    <td class="p-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $item['status_class'] ?>">
                                            <span class="material-icons mr-1 text-sm"><?= $item['status_icon'] ?></span>
                                            <?= $item['status'] ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <a href="<?= base_url('attendance/monthly?employee_id=' . $item['employee']['id']) ?>" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-md text-xs hover:bg-blue-200">
                                            <span class="material-icons text-sm mr-1">calendar_month</span>
                                            Bulanan
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center p-4 text-gray-500">Tidak ada data absensi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

<!-- jQuery & DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTables only on the admin recap table
    $('#adminRecapTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        // Disable ordering on the last column ("Aksi")
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
</script>
</body>
</html>
