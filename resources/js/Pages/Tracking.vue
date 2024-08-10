<template>
  <app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-blue-200">
      <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-2xl">
        <div class="mb-4">
          <h1 class="text-xl font-semibold">REKRUTMEN PEGAWAI</h1>
        </div>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 text-center" role="alert">
          <p class="font-bold">URL dan Tracking Code bersifat rahasia dan akan digunakan selama proses rekrutmen
            berlangsung. </p>
          <p class="font-extrabold"> Jangan dibagikan ke orang lain.</p>
        </div>
        <div class="mb-4 text-center">
          <h2 class="text-lg font-bold">Kode</h2>
          <p class="text-2xl">{{ application.tracking_code }}</p>
        </div>
        <div class="mb-4">
          <h2 class="text-lg font-bold">URL</h2>
          <p class="text-sm">Gunakan URL berikut untuk melacak status aplikasi Anda.</p>
          <div class="mt-2">
            <a :href="`${trackingBaseUrl}/${application.tracking_code}`" target="_blank" class="text-blue-500">
              {{ `${trackingBaseUrl}/${application.tracking_code}` }}
            </a>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between">
            <span class="font-semibold">Nama Lowongan</span>
            <span>{{ application.title }}</span>
          </div>
          <div class="flex justify-between">
            <span class="font-semibold">Nama Lengkap</span>
            <span>{{ application.first_name }} {{ application.last_name }}</span>
          </div>
        </div>
        <div class="grid grid-cols-1 divide-y">
          <div class="py-2">
            <div class=" flex font-semibold items-center justify-center">
              <i class="fas fa-clock"></i>
              <span>Mulai</span>
            </div>
            <div class="flex items-center justify-center">
              <p>{{ application.created_at }}</p>
              <!-- <p>{{ formattedCreatedAt }}</p> -->
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Review Data Diri</span>
            </div>
            <div class="flex  items-center justify-center">
              <!-- <p>Menunggu diproses...</p> -->
              <p>{{ application.valid_created_at ? application.valid_created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Apakah Lanjut Wawancara User?</span>
            </div>
            <div class="flex  items-center justify-center">
              <p>{{ application.user_interview.created_at ? application.user_interview.created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Input Hasil Wawancara User</span>
            </div>
            <div class="flex  items-center justify-center">
              <p>{{ application.user_interview_result.created_at ? application.user_interview_result.created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Apakah Lanjut Wawancara HR?</span>
            </div>
            <div class="flex  items-center justify-center">
              <p>{{ application.hr_interview.created_at ? application.hr_interview.created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Input Hasil Wawancara HR</span>
            </div>
            <div class="flex  items-center justify-center">
              <p>{{ application.hr_interview_result.created_at ? application.hr_interview_result.created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
          <div class="py-2">
            <div class="flex font-semibold items-center justify-center">
              <i class="fas fa-tasks mr-2"></i>
              <span>Apakah Diterima?</span>
            </div>
            <div class="flex  items-center justify-center">
              <p>{{ application.acceptance_status == (application.diterima || application.ditolak) ? application.status_created_at : "Menunggu diproses..." }}</p>
            </div>
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between bg-blue-100 border border-blue-300 p-4 rounded">
            <span>Status:</span>
            <span class="font-semibold text-blue-500">ACTIVE</span>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
// formate date from resource/js/utils/dateutils.js
import { formatDate } from '@/Utils/dateUtils';

export default {
  components: {
    AppLayout,

  },
  computed: {
    formattedCreatedAt() {
      return formatDate(this.application.created_at);
    }
  },
  props: {
    application: Object,
    trackingBaseUrl: String,
  },
};
</script>

<style scoped>
/* Add any additional styling here */
</style>
