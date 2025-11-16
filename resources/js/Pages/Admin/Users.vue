<template>
  <AppLayout>
    <div class="p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Manage Users</h1>
        <button
          @click="showCreateModal = true"
          class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition"
        >
          + Add User
        </button>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Phone</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Address</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b border-gray-200 hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-900">{{ user.name }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.email }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.phone }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.address }}</td>
              <td class="px-6 py-4 text-sm space-x-2">
                <button
                  @click="editUser(user)"
                  class="text-blue-600 hover:text-blue-700 font-medium"
                >
                  Edit
                </button>
                <button
                  @click="deleteUser(user.id)"
                  class="text-red-600 hover:text-red-700 font-medium"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Create/Edit Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">
            {{ editingUser ? 'Edit User' : 'Add New User' }}
          </h2>

          <form @submit.prevent="saveUser" class="space-y-4">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
              <input
                v-model="form.phone"
                type="tel"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Address -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
              <input
                v-model="form.address"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Password (only for new users) -->
            <div v-if="!editingUser">
              <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
              />
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
              <button
                type="submit"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition"
              >
                Save
              </button>
              <button
                type="button"
                @click="closeModal"
                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-2 px-4 rounded-lg transition"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const users = ref([]);
const showCreateModal = ref(false);
const editingUser = ref(null);

const form = useForm({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
});

onMounted(async () => {
  await loadUsers();
});

const loadUsers = async () => {
  try {
    const response = await window.axios.get('/admin/users/list');
    users.value = response.data;
  } catch (error) {
    console.error('Failed to load users:', error);
  }
};

const editUser = (user) => {
  editingUser.value = user;
  form.name = user.name;
  form.email = user.email;
  form.phone = user.phone;
  form.address = user.address;
  form.password = '';
  showCreateModal.value = true;
};

const saveUser = () => {
  if (editingUser.value) {
    form.put(`/admin/users/${editingUser.value.id}`, {
      onSuccess: () => {
        closeModal();
        loadUsers();
      },
    });
  } else {
    form.post('/admin/users', {
      onSuccess: () => {
        closeModal();
        loadUsers();
      },
    });
  }
};

const deleteUser = (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    router.delete(`/admin/users/${id}`, {
      onSuccess: () => {
        loadUsers();
      },
    });
  }
};

const closeModal = () => {
  showCreateModal.value = false;
  editingUser.value = null;
  form.reset();
};
</script>
