<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - Sistem Manajemen Kepegawaian</title>
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

    <main class="pt-20 p-6 flex-1 overflow-y-auto space-y-6">
        <!-- Payroll History Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Slip Gaji Bulanan</h1>
                    <p class="text-sm text-gray-500 mt-1">Riwayat penggajian Anda</p>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-blue-500 text-sm">badge</span>
                        <span><?= htmlspecialchars($employee['full_name']) ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-blue-500 text-sm">work</span>
                        <span><?= htmlspecialchars($employee['position']) ?></span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <?php if (!empty($payrolls)): ?>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="payrollHistoryTable" class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">Periode</th>
                                <th class="p-3 font-medium">Gaji Pokok</th>
                                <th class="p-3 font-medium">Bonus</th>
                                <th class="p-3 font-medium">Potongan</th>
                                <th class="p-3 font-medium">Total</th>
                                <th class="p-3 font-medium">Status</th>
                                <th class="p-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payrolls as $payroll): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-3">
                                    <?php 
                                    $bulan_bahasa = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    $bulan = $bulan_bahasa[$payroll['month_num']] ?? $payroll['month_num'];
                                    echo $bulan . ' ' . $payroll['year_num'];
                                    ?>
                                </td>
                                <td class="p-3">Rp<?= number_format($payroll['salary'], 0, ',', '.') ?></td>
                                <td class="p-3 text-green-600">+Rp<?= number_format($payroll['bonus'], 0, ',', '.') ?></td>
                                <td class="p-3 text-red-600">-Rp<?= number_format($payroll['deductions'], 0, ',', '.') ?></td>
                                <td class="p-3 font-semibold">Rp<?= number_format($payroll['total_salary'], 0, ',', '.') ?></td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $payroll['status'] == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <span class="material-icons mr-1 text-sm"><?= $payroll['status'] == 'paid' ? 'check_circle' : 'pending' ?></span>
                                        <?= $payroll['status'] == 'paid' ? 'Dibayar' : 'Belum Dibayar' ?>
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="<?= site_url('payroll/view/' . $payroll['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-md text-xs hover:bg-blue-200">
                                        <span class="material-icons mr-1 text-sm">visibility</span>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-8 text-gray-500">
                    <span class="material-icons text-4xl mb-2 text-gray-400">receipt</span>
                    <p>Belum ada data penggajian</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

<!-- jQuery & DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#payrollHistoryTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        order: [[0, 'desc']], // Sort by period descending by default
        columnDefs: [
            { orderable: false, targets: -1 } // Disable sorting on action column
        ]
    });
});
</script>
</body>
</html>