<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Bulanan - Sistem Manajemen Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<?php $this->load->view('partials/sidebar'); ?>
<div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
        <?php $this->load->view('partials/header'); ?>
    </div>

    <main class="pt-24 p-6 flex-1 overflow-y-auto space-y-6">
        <!-- Monthly Attendance Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Presensi Bulanan - <?= htmlspecialchars($employee['full_name']) ?></h1>
                    <p class="text-sm text-gray-500 mt-1">Riwayat kehadiran bulan <?= date("F", mktime(0, 0, 0, (int)$month, 10)) ?> <?= $year ?></p>
                </div>
                
                <form method="get" action="<?= base_url('attendance/monthly') ?>" class="flex items-center gap-3">
                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                    
                    <div class="flex items-center gap-2">
                        <label for="month" class="text-sm text-gray-600">Bulan:</label>
                        <select name="month" id="month" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            <?php for($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == (int)$month ? 'selected' : '' ?>>
                                    <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <label for="year" class="text-sm text-gray-600">Tahun:</label>
                        <select name="year" id="year" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            <?php $currentYear = date('Y'); ?>
                            <?php for($y = $currentYear - 5; $y <= $currentYear + 1; $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Monthly Attendance Table -->
            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="monthlyAttendanceTable" class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">Tanggal</th>
                                <th class="p-3 font-medium">Hari</th>
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
                                    <td class="p-3"><?= date('l', strtotime($row['date'])) ?></td>
                                    <td class="p-3"><?= $row['clock_in_time'] ? date('H:i', strtotime($row['clock_in_time'])) : '-' ?></td>
                                    <td class="p-3"><?= $row['clock_out_time'] ? date('H:i', strtotime($row['clock_out_time'])) : '-' ?></td>
                                    <td class="p-3">
                                        <?php
                                            $statusConfig = [
                                                'on_leave' => [
                                                    'class' => 'bg-blue-100 text-blue-800',
                                                    'icon' => 'event_available',
                                                    'label' => !empty($row['leave_type']) ? ucfirst($row['leave_type']) : 'Cuti'
                                                ],
                                                'present' => [
                                                    'class' => 'bg-green-100 text-green-800',
                                                    'icon' => 'check_circle',
                                                    'label' => 'Hadir'
                                                ],
                                                'halfday' => [
                                                    'class' => 'bg-yellow-100 text-yellow-800',
                                                    'icon' => 'schedule',
                                                    'label' => 'Belum Checkout'
                                                ],
                                                'absent' => [
                                                    'class' => 'bg-red-100 text-red-800',
                                                    'icon' => 'cancel',
                                                    'label' => 'Absen'
                                                ]
                                            ];
                                            
                                            $status = 'absent';
                                            if (!empty($row['is_on_leave'])) {
                                                $status = 'on_leave';
                                            } elseif ($row['clock_in_time'] && $row['clock_out_time']) {
                                                $status = 'present';
                                            } elseif ($row['clock_in_time']) {
                                                $status = 'halfday';
                                            }
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $statusConfig[$status]['class'] ?>">
                                            <span class="material-icons mr-1 text-sm"><?= $statusConfig[$status]['icon'] ?></span>
                                            <?= $statusConfig[$status]['label'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-gray-500">Tidak ada data presensi untuk periode ini</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-end">
            <a href="<?= $this->session->userdata('role') === 'admin' ? base_url('attendance') : base_url('employee') ?>" 
               class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                <span class="material-icons mr-2 text-base">arrow_back</span>
                Kembali
            </a>
        </div>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

<!-- jQuery & DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#monthlyAttendanceTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        order: [[0, 'desc']] // Sort by date descending by default
    });
});
</script>
</body>
</html>