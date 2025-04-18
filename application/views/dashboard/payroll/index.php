<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penggajian - Sistem Manajemen Kepegawaian</title>
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

    <main class="pt-20 p-6 flex-1 overflow-y-auto space-y-6">
        <!-- Payroll Records Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Daftar Penggajian</h1>
                    <p class="text-sm text-gray-500 mt-1">Riwayat pembayaran gaji pegawai</p>
                </div>
                
                <?php if($this->session->userdata('role') === 'admin'): ?>
                <a href="<?= site_url('payroll/generate') ?>" 
                   class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    <span class="material-icons mr-2 text-base">add</span>
                    Generate Payroll
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($this->session->flashdata('success'))): ?>
            <div class="px-6 pt-4">
                <div class="border-l-4 border-green-400 bg-green-50 text-green-700 px-4 py-2 text-sm">
                    <?= $this->session->flashdata('success') ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payroll Records Table -->
            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="payrollTable" class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">Pegawai</th>
                                <th class="p-3 font-medium">Periode</th>
                                <th class="p-3 font-medium">Gaji Pokok</th>
                                <th class="p-3 font-medium">Potongan</th>
                                <th class="p-3 font-medium">Total</th>
                                <th class="p-3 font-medium">Status</th>
                                <th class="p-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payrolls as $p): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-3"><?= htmlspecialchars($p['full_name'] ?? 'N/A') ?></td>
                                <td class="p-3"><?= date('F Y', strtotime($p['year_num'] . '-' . $p['month_num'] . '-01')) ?></td>                                
                                <td class="p-3">Rp<?= number_format($p['salary'], 2) ?></td>
                                <td class="p-3 text-red-600">Rp<?= number_format($p['deductions'], 2) ?></td>
                                <td class="p-3 font-semibold">Rp<?= number_format($p['total_salary'], 2) ?></td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $p['status'] == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <span class="material-icons mr-1 text-sm"><?= $p['status'] == 'paid' ? 'check_circle' : 'pending' ?></span>
                                        <?= ucfirst($p['status']) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <a href="<?= site_url('payroll/view/' . $p['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-md text-xs hover:bg-blue-200">
                                        <span class="material-icons mr-1 text-sm">visibility</span>
                                        Lihat
                                    </a>
                                    <?php if ($p['status'] == 'unpaid' && $this->session->userdata('role') === 'admin'): ?>
                                    <a href="<?= site_url('payroll/mark_as_paid/' . $p['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-md text-xs hover:bg-green-200">
                                        <span class="material-icons mr-1 text-sm">payments</span>
                                        Bayar
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($payrolls)): ?>
                            <tr>
                                <td colspan="7" class="text-center p-4 text-gray-500">
                                    Tidak ada data penggajian
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
    $('#payrollTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        columnDefs: [
            { orderable: false, targets: -1 } // Disable sorting on action column
        ],
        order: [[1, 'desc']] // Sort by period descending by default
    });
});
</script>
</body>
</html>