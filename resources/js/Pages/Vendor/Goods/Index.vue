<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold text-gray-900">My Goods</h1>
          <Link
            href="/vendor/goods/create"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition"
          >
            Add New Good
          </Link>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-700">{{ successMessage }}</p>
        </div>

        <!-- Goods Table -->
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-200 border-b-2 border-gray-400">
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Good Name</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Category</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Price</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Quantity</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Total Value</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Received Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="good in (goods?.data || [])" :key="good.id" class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.name }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.category?.name || 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${{ parseFloat(good.price).toFixed(2) }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.quantity }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-indigo-600">
                  ${{ (good.price * good.quantity).toFixed(2) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(good.recived_date) }}</td>
                <td class="px-4 py-3 text-sm space-x-2">
                  <Link
                    :href="`/vendor/goods/${good.id}/edit`"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded transition"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deleteGood(good.id)"
                    class="inline-block bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded transition"
                  >
                    Delete
                  </button>
                </td>
              </tr>
              <tr v-if="!goods?.data || goods.data.length === 0">
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                  No goods yet. <Link href="/vendor/goods/create" class="text-indigo-600 hover:underline">Add your first good</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="goods?.last_page && goods.last_page > 1" class="mt-6 flex justify-center gap-2">
          <Link
            v-for="page in paginationLinks"
            :key="page.label"
            :href="page.url"
            :class="[
              'px-3 py-2 rounded-lg font-medium transition',
              page.active
                ? 'bg-indigo-600 text-white'
                : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
            ]"
            v-html="page.label"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  goods: Object,
  categories: Array,
});

const successMessage = ref('');

const paginationLinks = computed(() => {
  const links = [];
  const lastPage = props.goods?.last_page || 1;
  const currentPage = props.goods?.current_page || 1;
  for (let i = 1; i <= lastPage; i++) {
    links.push({
      label: i,
      url: `/vendor/goods?page=${i}`,
      active: i === currentPage,
    });
  }
  return links;
});

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const deleteGood = async (goodId) => {
  if (!confirm('Are you sure you want to delete this good?')) return;

  try {
    await window.axios.delete(`/vendor/goods/${goodId}`);
    successMessage.value = 'Good deleted successfully';
    setTimeout(() => {
      router.visit('/vendor/goods');
    }, 1000);
  } catch (error) {
    console.error('Error deleting good:', error);
    alert('Failed to delete good');
  }
};
</script>
