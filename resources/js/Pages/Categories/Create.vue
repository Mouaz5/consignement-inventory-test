<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Category</h1>

        <!-- Success Message -->
        <div v-if="page.props.flash?.success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-700 text-sm">{{ page.props.flash.success }}</p>
        </div>

        <!-- Error Messages -->
        <div v-if="form.errors.name" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-red-700 text-sm">{{ form.errors.name }}</p>
        </div>

        <form @submit.prevent="form.post('/categories')" class="space-y-6">
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
              :disabled="form.processing"
              class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg transition"
            >
              {{ form.processing ? 'Creating...' : 'Create Category' }}
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
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

const form = useForm({
  name: '',
});

const goBack = () => {
  window.history.back();
};
</script>
