<script>
import BookCard from '../Shared/BookCard.vue';
import Layout from '../Shared/Layout.vue';
export default {
	components: { Layout, BookCard, Layout },
	props: {
		books: Array,
	}
}
</script>

<template>
	<Layout>

		<h1>Books</h1>

		<div class="books">
			<book-card v-for="book in books" :key="book.isbn" v-bind="book">

			</book-card>
		</div>
	</Layout>
</template>


<style></style>
