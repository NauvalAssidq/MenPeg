<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Sistem Manajemen Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .notification-item {
            transition: all 0.2s ease;
        }
        .notification-item:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50">

<?php $this->load->view('partials/sidebar'); ?>
<div class="ml-60 flex-1 flex flex-col min-h-screen">
    <div class="fixed top-0 left-60 right-0 z-50">
        <?php $this->load->view('partials/header', ['notifications' => $all_notifications ?? [], 'notif_count' => $notif_count ?? 0]); ?>
    </div>

    <main class="pt-24 p-6 flex-1 overflow-y-auto">
        <!-- Notifications Card -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-800">Notifikasi</h1>
                    <p class="text-sm text-gray-500 mt-1">Daftar pemberitahuan sistem</p>
                </div>
                
                <?php if (!empty($all_notifications)): ?>
                <a href="<?= site_url('notifications/mark_all_read') ?>" 
                   class="flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 text-sm">
                    <span class="material-icons mr-2 text-base">done_all</span>
                    Tandai semua dibaca
                </a>
                <?php endif; ?>
            </div>

            <!-- Notifications List -->
            <div class="p-6">
                <?php if (!empty($all_notifications)): ?>
                    <ul class="divide-y divide-gray-100">
                        <?php foreach ($all_notifications as $notif): ?>
                            <li class="notification-item p-4 <?= !$notif['is_read'] ? 'bg-blue-50' : '' ?>">
                                <div class="flex items-start">
                                    <?php 
                                    // Select icon based on notification type
                                    $icon = 'info';
                                    $color = 'blue';
                                    
                                    switch($notif['type']) {
                                        case 'leave_request':
                                            $icon = 'event_busy';
                                            $color = 'purple';
                                            break;
                                        case 'leave_approved':
                                            $icon = 'check_circle';
                                            $color = 'green';
                                            break;
                                        case 'leave_rejected':
                                            $icon = 'cancel';
                                            $color = 'red';
                                            break;
                                        case 'payroll':
                                            $icon = 'payments';
                                            $color = 'teal';
                                            break;
                                        case 'attendance':
                                            $icon = 'access_time';
                                            $color = 'orange';
                                            break;
                                    }
                                    ?>
                                    <div class="flex-shrink-0 p-2 rounded-full bg-<?= $color ?>-100 mr-4">
                                        <span class="material-icons text-<?= $color ?>-500"><?= $icon ?></span>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between">
                                            <p class="text-sm font-medium text-gray-900 <?= !$notif['is_read'] ? 'font-semibold' : '' ?>">
                                                <?= htmlspecialchars($notif['message']) ?>
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-500">
                                                    <?= date('d M Y, H:i', strtotime($notif['created_at'])) ?>
                                                </span>
                                                <?php if (!$notif['is_read']): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                                        Baru
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 flex gap-4">
                                            <a href="<?= site_url('notifications/read/'.$notif['id']) ?>" 
                                               class="inline-flex items-center text-sm text-<?= $color ?>-600 hover:text-<?= $color ?>-800">
                                                <span class="material-icons mr-1 text-sm">launch</span>
                                                Lihat Detail
                                            </a>
                                            
                                            <?php if ($notif['is_read']): ?>
                                                <span class="inline-flex items-center text-sm text-gray-500">
                                                    <span class="material-icons mr-1 text-sm">done</span>
                                                    Telah dibaca
                                                </span>
                                            <?php else: ?>
                                                <a href="<?= site_url('notifications/mark_read/'.$notif['id']) ?>" 
                                                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800">
                                                    <span class="material-icons mr-1 text-sm">check_circle_outline</span>
                                                    Tandai dibaca
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center p-8 text-gray-500">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <span class="material-icons text-gray-400 text-3xl">notifications_off</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada notifikasi</h3>
                        <p class="text-gray-500">Anda belum memiliki notifikasi untuk ditampilkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('partials/footer'); ?>

</body>
</html>