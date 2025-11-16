<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
        <p class="text-gray-600 mb-8">Manage your account information</p>

        <!-- Success Message -->
        <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-700">{{ successMessage }}</p>
        </div>

        <!-- Error Messages -->
        <div v-if="Object.keys(errors).length > 0" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <ul class="text-red-700 text-sm space-y-1">
            <li v-for="(messages, field) in errors" :key="field">
              {{ messages[0] }}
            </li>
          </ul>
        </div>

        <!-- Profile Form -->
        <form @submit.prevent="handleUpdate" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name Field -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Full Name
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Phone Field -->
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                Phone Number
              </label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>

            <!-- Address Field -->
            <div>
              <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                Address
              </label>
              <input
                id="address"
                v-model="form.address"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              />
            </div>
          </div>

          <!-- Role Display -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-gray-700 mb-2">Account Type</p>
            <p class="text-lg font-semibold text-indigo-600 capitalize">
              {{ user.role }}
            </p>
          </div>

          <!-- Vendor Information (if vendor) -->
          <div v-if="user.role === 'vendor' && user.vendor" class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <p class="text-sm font-medium text-gray-700 mb-3">Vendor Information</p>
            <div class="space-y-2 text-sm">
              <p><span class="font-medium">Vendor ID:</span> {{ user.vendor.id }}</p>
              <p><span class="font-medium">Created:</span> {{ formatDate(user.vendor.created_at) }}</p>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            >
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
            <Link
              href="/"
              class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200 text-center"
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
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  user: Object,
});

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
});

const loading = ref(false);
const errors = ref({});
const successMessage = ref('');

onMounted(() => {
  form.value = {
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    address: props.user.address,
  };
});

const handleUpdate = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';

  try {
    const response = await window.axios.put('/profile', form.value);
    successMessage.value = response.data.message;
    setTimeout(() => {
      successMessage.value = '';
    }, 3000);
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

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>
