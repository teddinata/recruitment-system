<template>
	<app-layout>
      <div>
        <!-- Tampilan Mobile -->
        <div class="relative lg:hidden mt-10 md:-mt-6 bg-cover bg-center h-screen flex flex-col">
          <!-- Gambar -->
          <div class="flex-shrink-0 w-full">
            <img :src="sliderImage" alt="Slider Image" class="w-full h-full">
          </div>
          <!-- Teks -->
          <div class="container flex bg-[#0064AF] mx-auto h-full items-center justify-center text-center">
            <h1 class="text-2xl sm:text-4xl font-bold text-white mb-6 px-4">
              Creating stunning & engaging online experiences
            </h1>
          </div>
        </div>

        <!-- Tampilan Desktop -->
        <div
        class="relative hidden lg:block mt-10 md:-mt-6 bg-cover bg-center h-screen"
        :style="{ backgroundImage: `url(${sliderImage})` }">
        <div class="container mx-auto h-full flex items-center">
          <div class="text-left">
            <h1 class="text-4xl lg:text-7xl font-bold text-[#0064AF] mb-6">
              Creating Stunning &<br />Engaging Online <br /> Experiences
            </h1>
          </div>
        </div>
        </div>
      </div>
		<!-- Slider Area End -->

		<!-- Our Services Start -->
		<div class="py-16 bg-gray-100">
			<div class="container mx-auto px-4">
				<div class="text-center mb-12">
					<h2 class="text-3xl font-bold">Browse Top Categories</h2>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
					<div v-for="category in categories" :key="category.id" class="bg-white p-6 rounded-lg shadow-lg text-center">
                      <Link :href="`/job-listing?category=${category.id}`">
                      <img :src="category.image" alt="" class="w-24 h-24 mx-auto mb-4"/>
						<h5 class="text-lg font-semibold mb-2">
                          {{ category.name }}
                        </h5>
                      </Link>
						<span class="text-gray-600">{{ category.jobs_count }} Jobs</span>
					</div>
				</div>
				<div class="text-center mt-12">
					<Link :href="route('job-listing')" class="inline-block px-8 py-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-200">
						Browse All Sectors
					</Link>
				</div>
			</div>
		</div>
		<!-- Our Services End -->

		<!-- Featured Job Start -->
		<section class="py-16 bg-white">
			<div class="container mx-auto px-4">
				<div class="text-center mb-12">
					<span class="text-lg text-gray-500">Recent Job</span>
					<h2 class="text-3xl font-bold">Featured Jobs</h2>
				</div>
				<div class="grid grid-cols-1 gap-6">
					<div v-for="jobVacancy in jobVacancies" :key="jobVacancy.id" class="bg-white p-6 rounded-lg shadow-lg">
                        <Link :href="`/job-vacancies/${jobVacancy.id}`">
                          <div class="flex items-center">
							<img :src="jobVacancy.image" alt="" class="w-24 h-24 rounded-full object-cover"/>
							<div class="ml-6">
                              <h4 class="text-xl font-semibold">
                                {{ jobVacancy.title }}
                              </h4>
                              <ul class="flex space-x-4 text-gray-600 mt-2">
                                <li>{{ jobVacancy.work_hours }}</li>
                                <li><i class="fas fa-map-marker-alt"></i> {{ jobVacancy.location }}</li>
                              </ul>
                            </div>
                          </div>
                        </Link>
						<div class="flex justify-end mt-4">
							<!-- <Link :href="route('job-vacancies.show', jobVacancy.id)" class="text-indigo-600 hover:text-indigo-800">
								{{ jobVacancy.title }}
							</Link> -->
							<span class="ml-4 text-gray-500">{{ jobVacancy.created_at_human }}</span>
						</div>
					</div>
					<div v-if="!jobVacancies.length" class="text-center py-6">
						<h4 class="text-xl font-semibold">No Jobs Available</h4>
					</div>
				</div>
			</div>
		</section>
		<!-- Featured Job End -->

		<!-- How Apply Process Start -->
		<div class="py-24 bg-cover bg-center" :style="{ backgroundImage: `url(${applyBgImage})` }">
			<div class="container mx-auto px-4 text-center text-white">
				<div class="mb-12">
					<span class="text-lg">Apply process</span>
					<h2 class="text-3xl font-bold">How it works</h2>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<div class="p-6 bg-white bg-opacity-10 rounded-lg">
						<div class="mb-4">
							<span class="text-4xl">🔍</span>
						</div>
						<h5 class="text-xl font-semibold mb-2">1. Search a job</h5>
						<p>Explore job opportunities tailored to your skills and interests.</p>
					</div>
					<div class="p-6 bg-white bg-opacity-10 rounded-lg">
						<div class="mb-4">
							<span class="text-4xl">📄</span>
						</div>
						<h5 class="text-xl font-semibold mb-2">2. Apply for job</h5>
						<p>Apply for the job that fits you best and showcase your qualifications.</p>
					</div>
					<div class="p-6 bg-white bg-opacity-10 rounded-lg">
						<div class="mb-4">
							<span class="text-4xl">🏆</span>
						</div>
						<h5 class="text-xl font-semibold mb-2">3. Get your job</h5>
						<p>Secure the job you applied for and embark on a new career journey.</p>
					</div>
				</div>
			</div>
		</div>
		<!-- How Apply Process End -->
	</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Ziggy } from '../ziggy'; // Import file ziggy.js yang telah di-generate
import { Link } from '@inertiajs/inertia-vue3';


export default {
  components: {
    AppLayout,
    Link
  },
  props: {
    categories: Array,
    jobVacancies: Array,
    sliderImage: String,
    applyBgImage: String
  }
}
</script>

<style scoped>
/* Tambahkan styling Tailwind CSS sesuai kebutuhan */
</style>
