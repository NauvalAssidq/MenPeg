<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Cuti - Sistem Manajemen Kepegawaian</title>
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
        <!-- Leave Management Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Manajemen Permintaan Cuti</h1>
                    <p class="text-sm text-gray-500 mt-1">Daftar permintaan cuti pegawai</p>
                </div>
                
                <?php if($this->session->userdata('role') !== 'admin'): ?>
                <a href="<?= base_url('leave/create') ?>" 
                   class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    <span class="material-icons mr-2 text-base">add</span>
                    Ajukan Cuti
                </a>
                <?php endif; ?>
            </div>

            <?php if($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
            <div class="px-6 pt-4">
                <div class="<?= $this->session->flashdata('success') ? 'border-l-4 border-green-400 bg-green-50 text-green-700' : 'border-l-4 border-red-400 bg-red-50 text-red-700' ?> px-4 py-2 text-sm">
                    <?= $this->session->flashdata('success') ?: $this->session->flashdata('error') ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Leave Requests Table -->
            <div class="p-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table id="leaveRequestsTable" class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-700 border-b border-gray-200">
                                <th class="p-3 font-medium">No</th>
                                <?php if($this->session->userdata('role') === 'admin'): ?>
                                <th class="p-3 font-medium">Pegawai</th>
                                <?php endif; ?>
                                <th class="p-3 font-medium">Jenis</th>
                                <th class="p-3 font-medium">Mulai</th>
                                <th class="p-3 font-medium">Selesai</th>
                                <th class="p-3 font-medium">Alasan</th>
                                <th class="p-3 font-medium">Status</th>
                                <?php if($this->session->userdata('role') === 'admin'): ?>
                                <th class="p-3 font-medium text-right">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($leave_requests)): ?>
                                <?php foreach($leave_requests as $index => $leave): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-3"><?= $index + 1 ?></td>
                                    <?php if($this->session->userdata('role') === 'admin'): ?>
                                    <td class="p-3"><?= htmlspecialchars($leave['full_name']) ?></td>
                                    <?php endif; ?>
                                    <td class="p-3"><?= ucfirst($leave['leave_type']) ?></td>
                                    <td class="p-3"><?= date('d M Y', strtotime($leave['start_date'])) ?></td>
                                    <td class="p-3"><?= date('d M Y', strtotime($leave['end_date'])) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($leave['reason']) ?></td>
                                    <td class="p-3">
                                        <?php
                                            $statusConfig = [
                                                'pending' => [
                                                    'class' => 'bg-yellow-100 text-yellow-800',
                                                    'icon' => 'pending',
                                                    'label' => 'Pending'
                                                ],
                                                'approved' => [
                                                    'class' => 'bg-green-100 text-green-800',
                                                    'icon' => 'check_circle',
                                                    'label' => 'Disetujui'
                                                ],
                                                'rejected' => [
                                                    'class' => 'bg-red-100 text-red-800',
                                                    'icon' => 'cancel',
                                                    'label' => 'Ditolak'
                                                ]
                                            ];
                                            $status = $leave['status'];
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs <?= $statusConfig[$status]['class'] ?>">
                                            <span class="material-icons mr-1 text-sm"><?= $statusConfig[$status]['icon'] ?></span>
                                            <?= $statusConfig[$status]['label'] ?>
                                        </span>
                                    </td>
                                    <?php if($this->session->userdata('role') === 'admin'): ?>
                                    <td class="p-3 text-right space-x-2">
                                        <?php if($leave['status'] === 'pending'): ?>
                                        <a href="<?= base_url('leave/approve/' . $leave['id']) ?>" 
                                           class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-md text-xs hover:bg-green-200">
                                            <span class="material-icons mr-1 text-sm">check</span>
                                            Setujui
                                        </a>
                                        <a href="<?= base_url('leave/reject/' . $leave['id']) ?>" 
                                           class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-md text-xs hover:bg-red-200">
                                            <span class="material-icons mr-1 text-sm">close</span>
                                            Tolak
                                        </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('leave/delete/' . $leave['id']) ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus permintaan cuti ini?');"
                                           class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-md text-xs hover:bg-gray-200">
                                            <span class="material-icons mr-1 text-sm">delete</span>
                                            Hapus
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $this->session->userdata('role') === 'admin' ? '8' : '7' ?>" class="text-center p-4 text-gray-500">
                                        Tidak ada data permintaan cuti
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
    $('#leaveRequestsTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"top flex justify-between items-center p-3"lfr>t<"bottom flex justify-between items-center p-3"ip>',
        <?php if($this->session->userdata('role') === 'admin'): ?>
        columnDefs: [
            { orderable: false, targets: -1 } // Disable sorting on action column
        ]
        <?php endif; ?>
    });
});
</script>
</body>
</html>