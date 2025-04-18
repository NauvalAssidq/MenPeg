<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penggajian - Sistem Manajemen Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50">

<?php $this->load->view('partials/sidebar'); ?>
<div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
        <?php $this->load->view('partials/header'); ?>
    </div>

    <main class="pt-20 p-6 flex-1 overflow-y-auto space-y-6">
        <!-- Payroll Details Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-lg font-medium text-gray-800">Detail Penggajian</h1>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap pembayaran gaji</p>
            </div>

            <div class="p-6">
                <?php if ($payroll): ?>
                <div class="space-y-4">
                    <!-- Employee and Period Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-700 mb-2">Informasi Pegawai</h3>
                            <p class="text-gray-600"><?= htmlspecialchars($payroll['full_name'] ?? 'N/A') ?></p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-700 mb-2">Periode</h3>
                            <p class="text-gray-600"><?= date('F Y', strtotime($payroll['year_num'] . '-' . $payroll['month_num'] . '-01')) ?></p>
                        </div>
                    </div>

                    <!-- Salary Breakdown -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-medium">Gaji Pokok</td>
                                    <td class="p-3 text-right">Rp<?= number_format($payroll['salary'], 2) ?></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-medium">Bonus</td>
                                    <td class="p-3 text-right text-green-600">+Rp<?= number_format($payroll['bonus'], 2) ?></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-medium text-red-600">Potongan</td>
                                    <td class="p-3 text-right text-red-600">-Rp<?= number_format($payroll['deductions'], 2) ?></td>
                                </tr>
                                <tr class="hover:bg-gray-50 bg-gray-50">
                                    <td class="p-3 font-medium">Total Gaji</td>
                                    <td class="p-3 text-right font-bold">Rp<?= number_format($payroll['total_salary'], 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Status and Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $payroll['status'] == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <span class="material-icons mr-1 text-sm"><?= $payroll['status'] == 'paid' ? 'check_circle' : 'pending' ?></span>
                                <?= ucfirst($payroll['status']) ?>
                            </span>
                        </div>
                        <div class="flex gap-2">
                        <?php
                            $return_to = $this->input->get('return_to');
                            $role = $this->session->userdata('role');
                            if (!$return_to) {
                                $return_to = ($role !== 'admin') ? 'payroll/monthly' : 'payroll';
                            }
                            $return_label = (strpos($return_to, 'monthly') !== false) ? 'Laporan Bulanan' : 'Daftar';
                            ?>
                            <a href="<?= site_url($return_to) ?>" 
                            class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                                <span class="material-icons mr-2 text-base">arrow_back</span>
                                Kembali ke <?= $return_label ?>
                            </a>
                            <?php if($this->session->userdata('role') === 'admin' && $payroll['status'] !== 'paid'): ?>
                            <a href="<?= site_url("payroll/mark_as_paid/$payroll[id]") ?>" 
                               class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                <span class="material-icons mr-2 text-base">payments</span>
                                Tandai Sudah Dibayar
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center p-8 text-gray-500">
                        <span class="material-icons text-4xl mb-2 text-gray-400">receipt</span>
                        <p>Data penggajian tidak ditemukan</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

</body>
</html>