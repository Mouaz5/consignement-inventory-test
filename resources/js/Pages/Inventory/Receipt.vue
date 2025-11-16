<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8 pb-8 border-b-2 border-gray-300">
          <h1 class="text-4xl font-bold text-gray-900">RECEIPT</h1>
          <p class="text-gray-600 mt-2">Receipt #{{ receipt.id }}</p>
        </div>

        <!-- Vendor Information -->
        <div class="grid grid-cols-2 gap-8 mb-8">
          <div>
            <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Vendor Information</h3>
            <p class="text-lg font-semibold text-gray-900">{{ vendor?.user?.name || 'N/A' }}</p>
            <p class="text-gray-600">{{ vendor?.phone || 'N/A' }}</p>
            <p class="text-gray-600">{{ vendor?.address || 'N/A' }}</p>
          </div>
          <div class="text-right">
            <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Receipt Details</h3>
            <p class="text-gray-700"><span class="font-semibold">Date:</span> {{ formatDate(receipt?.printed_at) }}</p>
            <p class="text-gray-700"><span class="font-semibold">Printed By:</span> {{ receipt?.user?.name || 'N/A' }}</p>
          </div>
        </div>

        <!-- Goods Table -->
        <div class="mb-8">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-200 border-b-2 border-gray-400">
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Good Name</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-800">Category</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-800">Price</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-800">Quantity</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-800">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="good in (goods || [])" :key="good.id" class="border-b border-gray-200">
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.name }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ good.category?.name || 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-right text-gray-700">${{ parseFloat(good.price).toFixed(2) }}</td>
                <td class="px-4 py-3 text-sm text-right text-gray-700">{{ good.quantity }}</td>
                <td class="px-4 py-3 text-sm text-right font-semibold text-indigo-600">
                  ${{ (good.price * good.quantity).toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Total Section -->
        <div class="flex justify-end mb-8">
          <div class="w-64">
            <div class="flex justify-between py-2 border-t-2 border-gray-400">
              <span class="font-bold text-lg">Total Amount:</span>
              <span class="font-bold text-lg text-indigo-600">${{ parseFloat(totalAmount).toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-8 border-t-2 border-gray-300">
          <p class="text-gray-600 text-sm">Thank you for your business!</p>
          <p class="text-gray-600 text-sm mt-2">This is an automated receipt. No signature required.</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center mt-8">
          <button
            @click="handlePrint"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
          >
            Print Receipt
          </button>
          <Link
            href="/inventory"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-center"
          >
            Back to Inventory
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  receipt: Object,
  vendor: Object,
  goods: Array,
  totalAmount: Number,
});

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const handlePrint = () => {
  window.print();
};
</script>
