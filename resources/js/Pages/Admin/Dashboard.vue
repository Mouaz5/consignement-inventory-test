<template>
  <AppLayout>
    <div class="p-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Users Card -->
        <Link href="/admin/users" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Total Users</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
              </svg>
            </div>
          </div>
        </Link>

        <!-- Vendors Card -->
        <Link href="/admin/vendors" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Total Vendors</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalVendors }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4" />
              </svg>
            </div>
          </div>
        </Link>

        <!-- Goods Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-600 text-sm font-medium">Total Goods</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalGoods }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <Link
            href="/admin/users"
            class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 transition text-left"
          >
            <h3 class="font-semibold text-blue-900">Manage Users</h3>
            <p class="text-sm text-blue-700 mt-1">Add, edit, or remove users</p>
          </Link>

          <Link
            href="/admin/vendors"
            class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 transition text-left"
          >
            <h3 class="font-semibold text-green-900">Manage Vendors</h3>
            <p class="text-sm text-green-700 mt-1">Add, edit, or remove vendors</p>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const stats = ref({
  totalUsers: 0,
  totalVendors: 0,
  totalGoods: 0,
});

onMounted(async () => {
  try {
    const response = await window.axios.get('/admin/stats');
    stats.value = response.data;
  } catch (error) {
    console.error('Failed to load stats:', error);
  }
});
</script>
