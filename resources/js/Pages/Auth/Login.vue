<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
        <p class="text-gray-600 mb-8">Sign in to your account</p>

        <!-- Error Messages -->
        <div v-if="form.errors.email" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-red-700 text-sm">{{ form.errors.email }}</p>
        </div>

        <!-- General Error -->
        <div v-if="generalError" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-red-700 text-sm">{{ generalError }}</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
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
              placeholder="you@example.com"
            />
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
              placeholder="••••••••"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
          >
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <!-- Contact Admin -->
        <p class="mt-6 text-center text-gray-600">
          Need an account?
          <span class="text-indigo-600 font-semibold">Contact your administrator</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const generalError = ref('');

const handleSubmit = () => {
  generalError.value = '';
  
  form.post('/login', {
    onError: (errors) => {
      console.error('Login errors:', errors);
      if (errors.email) {
        generalError.value = errors.email;
      } else {
        generalError.value = 'An error occurred during login. Please try again.';
      }
    },
    onSuccess: (response) => {
      console.log('Login successful, response:', response);
    },
  });
};
</script>
