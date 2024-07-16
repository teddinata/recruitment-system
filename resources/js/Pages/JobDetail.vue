<template>
  <app-layout>
    <div class="container mt-10 mx-auto py-12">
      <div class="flex flex-wrap justify-between">
          <!-- Left Content -->
          <div class="w-full lg:w-2/3 mb-10 lg:mb-0">
          <!-- Job single -->
          <div class="bg-white shadow-md rounded-lg p-6 mb-8">
              <div class="flex items-center mb-4">
              <div class="w-24 h-24 mr-4">
                <img :src="job.image" alt="" class="w-full h-full object-cover rounded-full">
              </div>
                <div>
                  <h4 class="text-2xl font-semibold">{{ job.title }}</h4>
                  <ul class="flex space-x-2 text-gray-600">
                  <li>{{ job.category.name }}</li>
                  <li><i class="fas fa-map-marker-alt"></i> {{ job.location }}</li>
                  </ul>
                </div>
              </div>
          </div>
          <!-- Job single End -->

          <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h4 class="text-xl font-semibold mb-4">Job Description</h4>
            <div class="prose" v-html="job.description"></div>
          </div>

          <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h4 class="text-xl font-semibold mb-4">Required Knowledge, Skills, and Abilities</h4>
            <div class="prose" v-html="job.qualifications"></div>
          </div>
        </div>

           <!-- Right Content -->
        <div class="w-full lg:w-1/3 bg-white shadow-lg rounded-lg p-6">
          <h4 class="text-xl font-semibold mb-4">Job Overview</h4>
          <div class="grid grid-cols-2 gap-2 text-gray-600">
            <span class="font-semibold">Posted date:</span>
            <span>{{ job.created_at_formatted }}</span>
            <span class="font-semibold">Location:</span>
            <span>{{ job.location }}</span>
            <span class="font-semibold">Job nature:</span>
            <span>{{ job.work_hours }}</span>
            <span class="font-semibold">Application date:</span>
            <span>{{ job.application_deadline }}</span>
          </div>
          <div class="mt-6">
            <Link :href="`${job.id}/apply-job`"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Apply Now</Link>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
	components: {
		AppLayout,
        Link,
	},
	props: {
		job: Object,
	},
};
</script>

<style scoped>
/* Tambahkan styling Tailwind CSS sesuai kebutuhan */
</style>
