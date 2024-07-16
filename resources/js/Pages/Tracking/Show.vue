<template>
    <app-layout>
      <div class="container mx-auto py-12">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
          <h3 class="text-xl font-semibold mb-4">Application Tracking</h3>
          <p class="mb-2"><strong>Tracking Code:</strong> {{ application.tracking_code }}</p>
          <p class="mb-2"><strong>Job Title:</strong> {{ application.jobVacancy.title }}</p>
          <p class="mb-2"><strong>Applicant Name:</strong> {{ application.first_name }} {{ application.last_name }}</p>
          <p class="mb-2"><strong>Status:</strong> {{ application.status }}</p>
          <!-- Tambahkan informasi lainnya sesuai kebutuhan -->
        </div>
      </div>
    </app-layout>
  </template>

  <script>
  import AppLayout from '@/Layouts/AppLayout.vue';

  export default {
    components: {
      AppLayout,
    },
    props: {
      application: {
        type: Object,
        required: true,
      },
    },
  };
  </script>

  <style scoped>
  /* Tambahkan styling Tailwind CSS sesuai kebutuhan */
  </style>
