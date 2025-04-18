<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Penggajian - Sistem Manajemen Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50">

<?php $this->load->view('partials/sidebar'); ?>
<div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
        <?php $this->load->view('partials/header'); ?>
    </div>

    <main class="pt-20 p-6 flex-1 overflow-y-auto">
        <!-- Generate Payroll Card -->
        <div class="bg-white border border-gray-200 rounded-lg max-w-md mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-lg font-medium text-gray-800">Generate Penggajian</h1>
                <p class="text-sm text-gray-500 mt-1">Buat daftar gaji untuk periode tertentu</p>
            </div>

            <?php if (!empty($this->session->flashdata('error'))): ?>
            <div class="px-6 pt-4">
                <div class="border-l-4 border-red-400 bg-red-50 text-red-700 px-4 py-2 text-sm">
                    <?= $this->session->flashdata('error') ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="p-6">
                <form method="POST" action="<?= site_url('payroll/generate') ?>" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $currentYear = date('Y'); ?>
                            <?php for ($y = $currentYear; $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            <span class="material-icons mr-2 text-base">calculate</span>
                            Generate Payroll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

</body>
</html>