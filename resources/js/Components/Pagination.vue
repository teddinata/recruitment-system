<template>
	<nav aria-label="Page navigation example">
		<ul class="flex justify-center space-x-1">
			<li
				v-for="link in links"
				:key="link.url"
				:class="[
					'page-item',
					link.active ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300',
					!link.url ? 'cursor-not-allowed' : '',
					'px-4 py-2 border rounded transition duration-300'
				]"
			>
				<a
					v-if="link.url"
					@click.prevent="changePage(link.url)"
					class="page-link hover:bg-gray-200"
					href="#"
				>
					<span v-html="link.label"></span>
				</a>
				<span
					v-else
					class="page-link bg-gray-100 text-gray-500"
					v-html="link.label"
				></span>
			</li>
		</ul>
	</nav>
</template>

<script>
export default {
	props: {
		links: Array
	},
	methods: {
		changePage(url) {
			const page = new URL(url).searchParams.get('page');
			this.$emit('page-changed', page);
		}
	}
};
</script>
