<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Good</h1>

        <!-- Error Messages -->
        <div v-if="Object.keys(errors).length > 0" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <ul class="text-red-700 text-sm space-y-1">
            <li v-for="(messages, field) in errors" :key="field">
              {{ messages[0] }}
            </li>
          </ul>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-700">{{ successMessage }}</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Good Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Good Name *
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Category -->
            <div>
              <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Category *
              </label>
              <select
                id="category_id"
                v-model="form.category_id"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              >
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <Link
                href="/categories/create"
                class="text-blue-600 text-sm mt-2 inline-block border-b-2 border-blue-600 hover:text-blue-800 hover:border-blue-800 transition"
              >
                + Add new category
              </Link>
            </div>

            <!-- Price -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                Price per Unit ($) *
              </label>
              <input
                id="price"
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Quantity -->
            <div>
              <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                Quantity *
              </label>
              <input
                id="quantity"
                v-model="form.quantity"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Received Date -->
            <div>
              <label for="recived_date" class="block text-sm font-medium text-gray-700 mb-2">
                Received Date *
              </label>
              <input
                id="recived_date"
                v-model="form.recived_date"
                type="date"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Total Value (Read-only) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Total Value
              </label>
              <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-semibold">
                ${{ totalValue.toFixed(2) }}
              </div>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
              Description *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              required
              rows="4"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
            />
          </div>

          <!-- Buttons -->
          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg transition"
            >
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
            <Link
              href="/vendor/goods"
              class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition text-center"
            >
              Cancel
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  good: Object,
  categories: Array,
});

const form = ref({
  name: '',
  description: '',
  price: '',
  quantity: '',
  recived_date: '',
  category_id: '',
});

const loading = ref(false);
const errors = ref({});
const successMessage = ref('');

const totalValue = computed(() => {
  const price = parseFloat(form.value.price) || 0;
  const quantity = parseFloat(form.value.quantity) || 0;
  return price * quantity;
});

onMounted(() => {
  form.value = {
    name: props.good.name,
    description: props.good.description,
    price: props.good.price,
    quantity: props.good.quantity,
    recived_date: props.good.recived_date,
    category_id: props.good.category_id,
  };
});

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';

  try {
    const response = await window.axios.put(`/vendor/goods/${props.good.id}`, form.value);
    successMessage.value = response.data.message;
    setTimeout(() => {
      router.visit('/vendor/goods');
    }, 1500);
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else if (error.response?.data?.message) {
      errors.value.general = [error.response.data.message];
    }
  } finally {
    loading.value = false;
  }
};
</script>
