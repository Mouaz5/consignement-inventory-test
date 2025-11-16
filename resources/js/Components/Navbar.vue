<template>
  <nav class="bg-white shadow-md">
    <div class="px-6 py-4 flex items-center justify-between">
      <!-- Logo/Title (only for regular users) -->
      <div class="flex items-center">
        <h1 v-if="user?.role !== 'vendor'" class="text-2xl font-bold text-indigo-600">User</h1>
        <h1 v-else class="text-2xl font-bold text-indigo-600">Vendor</h1>
      </div>

      <!-- Profile Icon and Dropdown -->
      <div class="relative">
        <button
          @click="toggleDropdown"
          class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition"
        >
          <!-- Avatar -->
          <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
            {{ getInitials(user?.name) }}
          </div>
          <span class="text-gray-700 font-medium hidden sm:inline">{{ user?.name }}</span>
          <svg
            :class="['w-4 h-4 text-gray-600 transition', isDropdownOpen ? 'rotate-180' : '']"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
          </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
          v-if="isDropdownOpen"
          class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
        >
          <Link
            href="/profile"
            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
            @click="isDropdownOpen = false"
          >
            My Profile
          </Link>
          <button
            @click="handleLogout"
            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
});

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const getInitials = (name) => {
  if (!name) return '?';
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
};

const handleLogout = () => {
  router.post('/logout');
};
</script>
