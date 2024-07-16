<template>
    <footer>
      <!-- Footer Start -->
      <div class="bg-[#010b1d] text-white py-12">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
          <div class="mb-6 md:mb-0">
            <h4 class="text-xl font-bold mb-4">About Us</h4>
            <p class="text-lg">Creating Stunning & Engaging Online Experiences.</p>
          </div>
          <div class="mb-6 md:mb-0">
            <h4 class="text-xl font-bold mb-4">Contact Info</h4>
            <ul>
              <li class="mb-2">Address : Yogyakarta, Indonesia</li>
              <li class="mb-2"><a href="tel:+6281235536644">Phone : +62 81235536644</a></li>
              <li><a href="mailto:halo@viscusmedia.com">Email : nurul@viscusmedia.com</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-xl font-bold mb-4">Important Links</h4>
            <ul>
              <li class="mb-2"><Link :href="`/job-listing`">Find Jobs</Link></li>
              <li><a target="_blank" href="https://viscusmedia.com">About Viscus</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Footer Bottom -->
      <div class="bg-[#010b1d] py-4">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left text-gray-400">
          <div class="mb-4 md:mb-0">
            <a href="index.html">
              <img :src="logoUrl" alt="Viscus Logo" class="w-32"/>
            </a>
          </div>
          <div>
            <div class="text-white">
                <!-- dynamic year -->
                &copy; {{ new Date().getFullYear() }} Viscus Media. All Rights Reserved.
            </div>
          </div>
        </div>
      </div>
      <!-- Footer End -->
    </footer>
  </template>

  <script>
  import logoUrl from '../../../public/frontend-new/img/viscus.png';
	import { Link } from '@inertiajs/inertia-vue3';

  export default {
		components: {
			Link,
		},
    data() {

      return {
        logoUrl
      };
    }
  }
  </script>

  <style scoped>
  /* Tambahkan CSS kustom sesuai kebutuhan */
  </style>
