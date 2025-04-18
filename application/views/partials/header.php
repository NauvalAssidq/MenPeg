<?php 
  $notifications = isset($notifications) ? $notifications : [];
  $notif_count = count($notifications);
?>
<nav class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center" 
     x-data="{ notifOpen: false, profileOpen: false }"
     @keydown.escape="notifOpen = false; profileOpen = false">
  <div class="flex items-center">
    <h2 class="text-lg font-medium text-gray-800">
      <?php 
        $segments = $this->uri->segment_array();
        echo ucfirst($segments[1] ?? 'Dashboard');
      ?>
    </h2>
  </div>
  <div class="flex items-center space-x-5">
    <div class="relative">
      <button @click="notifOpen = !notifOpen; profileOpen = false" class="relative p-1.5 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-50 transition-colors">
        <i data-lucide="bell" class="w-6 h-6 text-blue-600"></i>
        <?php if($notif_count > 0): ?>
          <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white text-xs font-semibold">
            <?= $notif_count ?>
          </span>
        <?php endif; ?>
      </button>
      <div x-show="notifOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1"
           x-transition:enter-end="opacity-100 translate-y-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 translate-y-0"
           x-transition:leave-end="opacity-0 translate-y-1"
           @click.outside="notifOpen = false"
           class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-200"
           style="display: none">
        <div class="p-4 space-y-3">
          <div class="flex items-center justify-between px-2">
            <h3 class="text-sm font-medium text-gray-800">Notifikasi</h3>
            <?php if ($notif_count > 0): ?>
              <a href="<?= site_url('notifications/mark_all_read') ?>" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai semua dibaca</a>
            <?php endif; ?>
          </div>
          <div class="max-h-96 overflow-y-auto space-y-2">
            <?php if ($notif_count > 0): ?>
              <?php foreach ($notifications as $notif): ?>
                <?php
                  $iconConfig = [
                    'leave_request' => ['svg' => 'calendar', 'color' => 'text-purple-600'],
                    'leave_approved' => ['svg' => 'check', 'color' => 'text-green-600'],
                    'leave_rejected' => ['svg' => 'x', 'color' => 'text-red-600'],
                    'payroll' => ['svg' => 'currency-dollar', 'color' => 'text-teal-600'],
                    'attendance' => ['svg' => 'clock', 'color' => 'text-orange-600']
                  ];
                  $icon = $iconConfig[$notif['type']] ?? ['svg' => 'info', 'color' => 'text-blue-600'];
                ?>
                <a href="<?= site_url('notifications/read/'.$notif['id']) ?>" class="flex items-start p-3 space-x-3 rounded-lg hover:bg-gray-50 transition-colors <?= !$notif['is_read'] ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-white' ?>">
                  <div class="flex-shrink-0 mt-0.5 <?= $icon['color'] ?>">
                    <?php switch($icon['svg']):
                      case 'calendar': ?>
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <?php break; case 'check': ?>
                        <i data-lucide="check" class="w-5 h-5"></i>
                        <?php break; case 'x': ?>
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <?php break; case 'currency-dollar': ?>
                        <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        <?php break; case 'clock': ?>
                        <i data-lucide="clock" class="w-5 h-5"></i>
                        <?php break; default: ?>
                        <i data-lucide="info" class="w-5 h-5"></i>
                    <?php endswitch; ?>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm <?= $notif['is_read'] ? 'text-gray-600' : 'text-gray-900 font-medium' ?>">
                      <?= htmlspecialchars($notif['message']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1"><?= date('d M H:i', strtotime($notif['created_at'])) ?></p>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="p-4 text-center">
                <p class="text-gray-400 text-sm">Tidak ada notifikasi baru</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="relative">
      <button @click="profileOpen = !profileOpen; notifOpen = false" class="flex items-center space-x-2 group focus:outline-none">
        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-full text-sm font-medium">
          <?= substr($this->session->userdata('username'), 0, 1) ?>
        </div>
        <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900 transition-colors">
          <?= $this->session->userdata('username'); ?>
        </span>
        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transform transition-transform" :class="{ 'rotate-180': profileOpen }"></i>
      </button>
      <div x-show="profileOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1"
           x-transition:enter-end="opacity-100 translate-y-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 translate-y-0"
           x-transition:leave-end="opacity-0 translate-y-1"
           @click.outside="profileOpen = false"
           class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg z-50 border border-gray-200 divide-y divide-gray-200"
           style="display: none">
        <div class="p-2">
          <a href="<?= site_url('profile'); ?>" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
            <i data-lucide="user" class="w-4 h-4 text-gray-600 mr-2"></i>Profil
          </a>
          <a href="<?= site_url('settings'); ?>" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
            <i data-lucide="settings" class="w-4 h-4 text-gray-600 mr-2"></i>Pengaturan
          </a>
        </div>
        <div class="p-2">
          <a href="<?= site_url('auth/logout'); ?>" class="flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
            <i data-lucide="log-out" class="w-4 h-4 text-red-600 mr-2"></i>Keluar
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>
