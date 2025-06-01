<template>
    <section class="p-6">
      <h1 class="text-2xl font-bold mb-4">Mes clés API</h1>
  
      <!-- Affiche un message de succès si présent -->
      <!-- On vérifie d'abord que $page.props.flash existe avant de lire .success -->
      <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
          {{ $page.props.flash.success }}
        </div>
      </div>
  
      <!-- Formulaire de création d'une nouvelle clé -->
      <div class="mb-6">
        <form @submit.prevent="createKey">
          <div class="flex space-x-2 items-center">
            <input
              v-model="form.name"
              type="text"
              placeholder="Nom de la clé (ex. 'Mon Script')"
              class="border rounded px-3 py-2 w-full"
              required
            />
            <button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
              :disabled="form.processing"
            >
              <span v-if="!form.processing">Générer</span>
              <span v-else>...</span>
            </button>
          </div>
          <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">
            {{ form.errors.name }}
          </p>
        </form>
      </div>
  
      <!-- Si pas de clés, message, sinon tableau -->
      <div v-if="apiKeys.length === 0" class="text-gray-600">
        Vous n'avez pas encore créé de clés API.
      </div>
  
      <table v-else class="min-w-full bg-white border">
        <thead>
          <tr>
            <th class="px-4 py-2 border-b">Nom</th>
            <th class="px-4 py-2 border-b">Clé (key)</th>
            <th class="px-4 py-2 border-b">Créée le</th>
            <th class="px-4 py-2 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="key in apiKeys" :key="key.id" class="hover:bg-gray-50">
            <td class="px-4 py-2 border-b">{{ key.name }}</td>
            <td class="px-4 py-2 border-b font-mono text-sm break-all">{{ key.key }}</td>
            <td class="px-4 py-2 border-b">{{ formatDate(key.created_at) }}</td>
            <td class="px-4 py-2 border-b">
              <button
                @click="deleteKey(key.id)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                :disabled="processingDeletes.includes(key.id)"
              >
                <span v-if="!processingDeletes.includes(key.id)">Supprimer</span>
                <span v-else>…</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </template>
  
  <script>
  import { Inertia } from '@inertiajs/inertia';
  
  export default {
    props: {
      apiKeys: Array,
    },
    data() {
      return {
        form: {
          name: '',
          processing: false,
          errors: {},
        },
        processingDeletes: [], // IDs en cours de suppression
      };
    },
    methods: {
      /**
       * Envoie la requête POST pour créer une nouvelle clé.
       */
      createKey() {
        this.form.processing = true;
        this.form.errors = {};
  
        Inertia.post('/dashboard/api-keys', { name: this.form.name }, {
          onSuccess: () => {
            this.form.name = '';
          },
          onError: (errors) => {
            this.form.errors = errors;
          },
          onFinish: () => {
            this.form.processing = false;
          },
        });
      },
  
      /**
       * Supprime une clé (DELETE).
       */
      deleteKey(id) {
        if (!confirm("Voulez-vous vraiment supprimer cette clé ?")) {
          return;
        }
        this.processingDeletes.push(id);
        Inertia.delete(`/dashboard/api-keys/${id}`, {
          onFinish: () => {
            this.processingDeletes = this.processingDeletes.filter(x => x !== id);
          },
        });
      },
  
      /**
       * Formatage simple de date en français.
       */
      formatDate(dateString) {
        return new Date(dateString).toLocaleString('fr-FR', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        });
      },
    },
  };
  </script>
  
  <style scoped>
  /* Ajoutez d’éventuels styles ici */
  </style>
  