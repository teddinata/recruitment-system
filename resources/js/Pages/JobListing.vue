<template>
  <app-layout>
    <div>
      <form @submit.prevent="applyFilters">
        <div class="job-listing-area pt-28 pb-28">
          <div class="container mx-auto">
            <div class="flex flex-wrap">
              <!-- Left content -->
              <div class="w-full md:w-1/4 p-4">
                <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                  <h4 class="text-lg font-bold mb-4">Filter Jobs</h4>
                  <div class="mb-6">
                    <h5 class="text-md font-semibold mb-2">Job Category</h5>
                    <select v-model="filters.category_id" class="w-full p-2 border rounded">
                      <option value="">Any Category</option>
                      <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                      </option>
                    </select>
                  </div>
                  <div class="mb-6">
                    <h5 class="text-md font-semibold mb-2">Experience</h5>
                    <div v-for="exp in experienceOptions" :key="exp.value" class="mb-2">
                      <label class="inline-flex items-center">
                        <input type="checkbox" :value="exp.value" v-model="filters.experience" class="form-checkbox">
                        <span class="ml-2">{{ exp.label }}</span>
                      </label>
                    </div>
                  </div>
                  <div class="mb-6">
                    <h5 class="text-md font-semibold mb-2">Posted Within</h5>
                    <div v-for="post in postedWithinOptions" :key="post.value" class="mb-2">
                      <label class="inline-flex items-center">
                        <input type="radio" :value="post.value" v-model="filters.post" class="form-radio">
                        <span class="ml-2">{{ post.label }}</span>
                      </label>
                    </div>
                  </div>
                  <div class="flex justify-between">
                    <!-- <button type="submit" class="px-4 py-2 mr-2 bg-blue-500 text-white font-semibold rounded-lg
                      shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75
                      transition duration-300">
                      Filter
                    </button> -->
                    <button type="button" @click="resetFilters" class="w-full px-4 py-2 bg-slate-500 text-white font-semibold rounded-lg
                      shadow-md hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75
                      transition duration-300">
                      Reset Filter
                    </button>
                  </div>
                </div>
              </div>
              <!-- Right content -->
              <div class="w-full md:w-3/4 p-4">
                <section class="featured-job-area">
                  <div class="container mx-auto">
                    <div class="flex justify-between items-center mb-8">
                      <span>{{ jobVacancies.data.length }} Jobs found</span>
                      <div>
                        <span>Sort by</span>
                        <select v-model="filters.sort_by" class="ml-2 p-2 border rounded">
                          <option value="">None</option>
                          <option value="created_at">Newest</option>
                          <option value="experience">Experience</option>
                          <option value="salary">Salary</option>
                        </select>
                      </div>
                    </div>
                    <div v-for="jobVacancy in jobVacancies.data" :key="jobVacancy.id" class="single-job-items mb-8 bg-white shadow-md rounded-lg p-4 flex">
                      <!-- <Link :href="`/job-vacancies/${jobVacancy.id}`"> -->
                        <div class="w-1/4">
                          <a :href="`/job-vacancies/${jobVacancy.id}`">
                            <img :src="jobVacancy.image" alt="Job Image" class="w-24 h-24 object-cover rounded">
                          </a>
                        </div>
                        <div class="w-3/4 ml-4">
                          <a :href="`/job-vacancies/${jobVacancy.id}`" class="text-xl font-bold">
                            {{ jobVacancy.title }}
                          </a>
                          <ul class="flex text-gray-600 mt-2">
                            <li>{{ jobVacancy.work_hours }}</li>
                            <li style="margin: 0 8px; list-style-type: none;">•</li>
                            <li><i class="fas fa-map-marker-alt"></i> {{ jobVacancy.location }}</li>
                          </ul>
                          <div class="mt-4 flex justify-end items-center">
                            <span class="ml-4 text-gray-500">{{ jobVacancy.created_at_human }}</span>
                          </div>
                        </div>
                      <!-- </Link> -->
                      </div>
                    <div v-if="jobVacancies.data.length === 0" class="text-center py-6">
                      <h4 class="text-xl font-semibold">No Jobs Available</h4>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="pagination-area pb-28 text-center">
        <div class="container mx-auto">
          <pagination :links="jobVacancies.links" @page-changed="fetchJobs"/>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Pagination from '@/Components/Pagination.vue';
import { formatDate } from '@/Utils/dateUtils';

export default {
  props: {
    categories: Array,
    jobVacancies: Object,
  },
  components: {
    AppLayout,
    Link,
    Pagination
  },
  setup(props) {
    const filters = reactive({
      category_id: '',
      experience: [],
      post: '',
      sort_by: ''
    });

    const experienceOptions = [
      { value: 'Junior', label: 'Junior (1-2 Years)' },
      { value: 'Intermediate', label: 'Intermediate (2-3 Years)' },
      { value: 'Senior', label: 'Senior (3-6 Years)' }
    ];

    const postedWithinOptions = [
      { value: '0', label: 'Any' },
      { value: '24hours', label: 'Today' },
      { value: '3days', label: 'Last 3 days' },
      { value: '7days', label: 'Last 7 days' },
      { value: '30days', label: 'Last 30 days' }
    ];

    const applyFilters = () => {
      Inertia.get('/job-listing', filters, { preserveState: true });
    };

    const resetFilters = () => {
      filters.category_id = '';
      filters.experience = [];
      filters.post = '';
      filters.sort_by = '';
      applyFilters();
    };

    watch(filters, applyFilters);

    return {
      filters,
      experienceOptions,
      postedWithinOptions,
      applyFilters,
      resetFilters,
      formatDate
    };
  }
};
</script>

<style scoped>
/* Add any additional styling here if needed */
</style>
