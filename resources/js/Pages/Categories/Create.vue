<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Category</h1>

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
          <!-- Category Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Category Name *
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              autofocus
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              placeholder="e.g., Fruits, Vegetables, Hardware"
            />
          </div>

          <!-- Buttons -->
          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg transition"
            >
              {{ loading ? 'Creating...' : 'Create Category' }}
            </button>
            <button
              type="button"
              @click="goBack"
              class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = ref({
  name: '',
});

const loading = ref(false);
const errors = ref({});
const successMessage = ref('');

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';

  try {
    const response = await window.axios.post('/categories', form.value);
    successMessage.value = response.data.message;
    setTimeout(() => {
      goBack();
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

const goBack = () => {
  window.history.back();
};
</script>
