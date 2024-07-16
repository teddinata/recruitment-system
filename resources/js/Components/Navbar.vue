<template>
  <header>
    <!-- Header Start -->
    <div class="bg-transparent fixed top-0 w-full z-50">
      <div class="bg-white shadow-md w-full py-4">
        <div class="container mx-auto flex items-center justify-between">
          <div class="flex items-center">
            <!-- Logo -->
            <div class="logo">
              <inertia-link href="{{ route('home') }}">
                <img :src="logoUrl" alt="Viscus Logo" class="w-32"/>
              </inertia-link>
            </div>
          </div>
          <div class="hidden lg:flex">
            <!-- Main-menu -->
            <nav>
              <ul class="flex space-x-6">
                <li :class="{'text-indigo-600': route().current('home')}">
                  <Link :href="`/`">Home</Link>
                </li>
                <li :class="{'text-indigo-600': route().current('job-listing')}">
                  <Link :href="`/job-listing`">Find Jobs</Link>
                </li>
                <li>
                  <a target="_blank" href="https://viscusmedia.com">About Viscus</a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- Mobile Menu -->
          <div class="lg:hidden">
            <button @click="toggleMobileMenu" class="text-gray-500 focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Header End -->
  </header>
</template>

<script>
import logoUrl from '../../../public/frontend-new/img/viscus.png';
import { Link } from '@inertiajs/inertia-vue3';
export default {
  methods: {
      toggleMobileMenu() {
          const mobileMenu = document.querySelector('.mobile-menu');
          mobileMenu.classList.toggle('hidden');
      }
  },
	components: {
			Link,
	},
  data() {
    return {
      logoUrl,

    };
  }
  }
</script>

<style scoped>
/* Tambahkan CSS kustom sesuai kebutuhan */
</style>
