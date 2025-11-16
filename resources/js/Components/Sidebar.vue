<template>
  <aside class="w-64 bg-gray-900 text-white flex flex-col">
    <!-- Sidebar Header -->
    <div class="px-6 py-8 border-b border-gray-800">
      <h2 class="text-xl font-bold">Menu</h2>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-2">
      <Link
        href="/"
        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        :class="isActive('/') ? 'bg-indigo-600' : ''"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3" />
        </svg>
        <span>Dashboard</span>
      </Link>

      <Link
        href="/profile"
        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        :class="isActive('/profile') ? 'bg-indigo-600' : ''"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>My Profile</span>
      </Link>

      <!-- Goods link (only for non-vendors) -->
      <Link
        v-if="user?.role !== 'vendor'"
        href="/inventory"
        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
        :class="isActive('/inventory') ? 'bg-indigo-600' : ''"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span>Goods</span>
      </Link>

      <!-- Admin Menu (if user is admin) -->
      <div v-if="user?.role === 'admin'">
        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
        <Link
          href="/admin/dashboard"
          class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
          :class="isActive('/admin/dashboard') ? 'bg-indigo-600' : ''"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>Dashboard</span>
        </Link>
        <Link
          href="/admin/users"
          class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
          :class="isActive('/admin/users') ? 'bg-indigo-600' : ''"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
          </svg>
          <span>Users</span>
        </Link>
        <Link
          href="/admin/vendors"
          class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
          :class="isActive('/admin/vendors') ? 'bg-indigo-600' : ''"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4" />
          </svg>
          <span>Vendors</span>
        </Link>
      </div>

      <!-- Vendor Menu (if user is vendor) -->
      <div v-if="user?.role === 'vendor'">
        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vendor</p>
        <Link
          href="/vendor/goods"
          class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition"
          :class="isActive('/vendor/goods') ? 'bg-indigo-600' : ''"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4" />
          </svg>
          <span>My Goods</span>
        </Link>
      </div>
    </nav>

    <!-- Logout Button -->
    <div class="px-4 py-6 border-t border-gray-800">
      <button
        @click="handleLogout"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition font-medium"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span>Logout</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
});

const page = usePage();

const isActive = (path) => {
  return page.url.startsWith(path);
};

const handleLogout = () => {
  router.post('/logout');
};
</script>
