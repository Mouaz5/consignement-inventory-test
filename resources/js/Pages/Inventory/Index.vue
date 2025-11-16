<template>
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Inventory Management</h1>

        <!-- Filters Section -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>
          <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Vendor Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
              <select
                v-model="filters.vendor_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              >
                <option value="">All Vendors</option>
                <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">
                  {{ vendor.user.name }}
                </option>
              </select>
            </div>

            <!-- Category Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
              <select
                v-model="filters.category_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              >
                <option value="">All Categories</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Search Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Good Name</label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search goods..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Start Date Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
              <input
                v-model="filters.start_date"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- End Date Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
              <input
                v-model="filters.end_date"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 items-end">
              <button
                type="submit"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition"
              >
                Filter
              </button>
              <button
                type="button"
                @click="clearFilters"
                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
              >
                Clear
              </button>
            </div>
          </form>
        </div>

        <!-- Goods Table -->
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-200 border-b-2 border-gray-400">
                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-800 w-12">
                  <input
                    type="checkbox"
                    @change="toggleSelectAll"
                    :checked="selectedGoods.length === (goods?.data?.length || 0) && (goods?.data?.length || 0) > 0"
                    class="w-4 h-4 cursor-pointer"
                  />
                </th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Good Name</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Vendor</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Category</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Price</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Quantity</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Total</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Received Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Description</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="good in (goods?.data || [])" :key="good.id" class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-center">
                  <input
                    type="checkbox"
                    :value="good.id"
                    @change="toggleGoodSelection(good)"
                    :checked="selectedGoods.some(g => g.id === good.id)"
                    class="w-4 h-4 cursor-pointer"
                  />
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.name }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.vendor?.user?.name || 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.category?.name || 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${{ parseFloat(good.price).toFixed(2) }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.quantity }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-indigo-600">
                  ${{ (good.price * good.quantity).toFixed(2) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(good.recived_date) }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 truncate" :title="good.description">
                  {{ good.description }}
                </td>
              </tr>
              <tr v-if="!goods?.data || goods.data.length === 0">
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                  No goods found. Try adjusting your filters.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Print Receipt Button -->
        <div class="mt-6 flex justify-end gap-4">
          <button
            @click="handlePrintReceipt"
            :disabled="selectedGoods.length === 0 || loadingReceipt"
            class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-semibold py-2 px-6 rounded-lg transition"
          >
            {{ loadingReceipt ? 'Generating...' : `Print Receipt (${selectedGoods.length} items)` }}
          </button>
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
  vendors: Array,
  categories: Array,
  filters: Object,
});

const filters = ref({
  vendor_id: props.filters?.vendor_id || '',
  category_id: props.filters?.category_id || '',
  search: props.filters?.search || '',
  start_date: props.filters?.start_date || '',
  end_date: props.filters?.end_date || '',
});

const selectedGoods = ref([]);
const loadingReceipt = ref(false);

const paginationLinks = computed(() => {
  const links = [];
  const lastPage = props.goods?.last_page || 1;
  const currentPage = props.goods?.current_page || 1;
  for (let i = 1; i <= lastPage; i++) {
    links.push({
      label: i,
      url: `/inventory?page=${i}`,
      active: i === currentPage,
    });
  }
  return links;
});

const applyFilters = () => {
  const queryParams = new URLSearchParams();
  if (filters.value.vendor_id) queryParams.append('vendor_id', filters.value.vendor_id);
  if (filters.value.category_id) queryParams.append('category_id', filters.value.category_id);
  if (filters.value.search) queryParams.append('search', filters.value.search);
  if (filters.value.start_date) queryParams.append('start_date', filters.value.start_date);
  if (filters.value.end_date) queryParams.append('end_date', filters.value.end_date);

  router.visit(`/inventory?${queryParams.toString()}`);
};

const clearFilters = () => {
  filters.value = {
    vendor_id: '',
    category_id: '',
    search: '',
    start_date: '',
    end_date: '',
  };
  router.visit('/inventory');
};

const toggleGoodSelection = (good) => {
  const index = selectedGoods.value.findIndex(g => g.id === good.id);
  if (index > -1) {
    selectedGoods.value.splice(index, 1);
  } else {
    selectedGoods.value.push(good);
  }
};

const toggleSelectAll = () => {
  if (selectedGoods.value.length === (props.goods?.data?.length || 0)) {
    selectedGoods.value = [];
  } else {
    selectedGoods.value = [...(props.goods?.data || [])];
  }
};

const handlePrintReceipt = async () => {
  if (selectedGoods.value.length === 0) {
    alert('Please select at least one item');
    return;
  }

  // Group selected goods by vendor
  const vendorGroups = {};
  selectedGoods.value.forEach(good => {
    const vendorId = good.vendor_id;
    if (!vendorGroups[vendorId]) {
      vendorGroups[vendorId] = [];
    }
    vendorGroups[vendorId].push(good);
  });

  // If multiple vendors, show error
  if (Object.keys(vendorGroups).length > 1) {
    alert('Please select items from only one vendor');
    return;
  }

  loadingReceipt.value = true;
  try {
    const vendorId = Object.keys(vendorGroups)[0];
    const response = await window.axios.post('/inventory/receipt/custom', {
      vendor_id: vendorId,
      goods: selectedGoods.value.map(g => ({ id: g.id, price: g.price, quantity: g.quantity })),
    });
    router.visit(`/inventory/receipt/${response.data.receipt.id}`);
    selectedGoods.value = [];
  } catch (error) {
    console.error('Error printing receipt:', error);
    alert('Failed to generate receipt');
  } finally {
    loadingReceipt.value = false;
  }
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};
</script>
