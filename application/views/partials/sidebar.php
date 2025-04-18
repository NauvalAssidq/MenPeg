<div class="w-60 bg-white border-r border-gray-200 fixed h-screen z-50 flex flex-col font-[Inter]">
    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="flex items-center space-x-3">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <div class="font-semibold text-white text-lg leading-tight">
                Manajemen<br>Kepegawaian
            </div>
        </div>
    </div>

    <?php $segment = $this->uri->segment(1); ?>
    <div class="flex-1 px-3 py-6 overflow-y-auto">
        <nav class="space-y-2">
            <div class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Navigasi</div>

            <a href="<?= site_url('dashboard'); ?>"
               class="flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'dashboard' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                Dashboard
            </a>

            <?php if (in_array($this->session->userdata('role'), ['admin', 'manager'])): ?>
            <a href="<?= site_url('employee'); ?>"
               class="flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'employee' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'employee' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Data Pegawai
            </a>
            <?php endif; ?>

            <a href="<?= site_url('attendance'); ?>"
               class="flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'attendance' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'attendance' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Absensi
                <span class="ml-auto px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">New</span>
            </a>

            <a href="<?= site_url('leave'); ?>"
               class="flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'leave' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'leave' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Manajemen Cuti
            </a>

            <!-- Dropdown for Penggajian -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg text-gray-600 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Penggajian
                    <svg class="ml-auto w-4 h-4 text-gray-400 transform transition-transform duration-200"
                         :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                     class="ml-9 mt-1 space-y-2 pl-3 border-l-2 border-blue-100" style="display: none;">
                    <a href="<?= site_url('payroll/monthly'); ?>"
                       class="flex items-center py-2 text-sm transition-colors rounded-lg <?= $segment == 'payroll' && $this->uri->segment(2)=='monthly' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' ?>">
                        <svg class="w-4 h-4 mr-2 <?= $segment == 'payroll' && $this->uri->segment(2)=='monthly' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Slip Gaji
                    </a>
                    <?php if ($this->session->userdata('role') === 'admin'): ?>
                    <a href="<?= site_url('payroll'); ?>"
                       class="flex items-center py-2 text-sm transition-colors rounded-lg <?= $segment == 'payroll' && !$this->uri->segment(2) ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' ?>">
                        <svg class="w-4 h-4 mr-2 <?= $segment == 'payroll' && !$this->uri->segment(2) ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kelola Penggajian
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider mt-6 mb-3">Lainnya</div>

            <a href="<?= site_url('notifications'); ?>"
               class="hidden flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'notifications' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'notifications' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notifikasi
            </a>

            <a href="<?= site_url('settings'); ?>"
               class="flex items-center px-3 py-2.5 transition-all duration-200 rounded-lg <?= $segment == 'settings' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50' ?>">
                <svg class="w-5 h-5 mr-3 <?= $segment == 'settings' ? 'text-blue-600' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </a>
        </nav>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 mt-auto">
        <div class="flex items-center space-x-3">
            <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" src="<?= base_url('assets/img/default.png') ?>" alt="Profile">
            <div class="flex-1">
                <div class="font-medium text-gray-800 text-sm">
                    <?= $this->session->userdata('name') ?? 'Admin Sistem' ?>
                </div>
                <div class="text-xs text-gray-500 truncate">
                    <?= $this->session->userdata('email') ?? 'admin@company.id' ?>
                </div>
            </div>
            <a href="<?= site_url('auth/logout'); ?>" class="text-gray-400 hover:text-red-600 transition-colors" title="Logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </a>
        </div>
    </div>
</div>