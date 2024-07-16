<template>
	<div>
		<!-- Preloader Start -->
		<!-- <div id="preloader-active">
			<div class="preloader d-flex align-items-center justify-content-center">
				<div class="preloader-inner position-relative">
					<div class="preloader-circle"></div>
					<div class="preloader-img pere-text">
						<img :src="logoUrl" alt="Viscus Logo">
					</div>
				</div>
			</div>
		</div> -->
		<!-- Preloader End -->

		<!-- Navbar -->
		<Navbar />

		<!-- Main Content -->
		<main>
			<slot></slot>
		</main>

		<!-- Footer -->
		<Footer />
	</div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue'; // Pastikan Anda memiliki komponen Navbar.vue
import Footer from '@/Components/Footer.vue'; // Pastikan Anda memiliki komponen Footer.vue
import logoUrl from '../../../public/frontend-new/img/viscus.png';

export default {
	components: {
      Navbar,
      Footer
	},
	data() {
      return {
      logoUrl
      };
	}
}
</script>

<style scoped>
/* Tambahkan CSS Anda sesuai kebutuhan */
</style>
