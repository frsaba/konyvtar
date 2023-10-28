<script>
import BookCard from '../Shared/BookCard.vue';
import Layout from '../Shared/Layout.vue';
export default {
	components: { Layout, BookCard, Layout },
	props: {
		books: Array,
	},
}
</script>

<template>
	<Layout>

		
		<div class="sidebar">
			<h1>Search books</h1>
			<v-text-field
				name="title_search"
				label="Search in title"
			></v-text-field>

			<v-text-field
				name="author_search"
				label="Search authors"
			></v-text-field>

		</div>

		<div class="books">
			<book-card v-for="book in books ?? []" :key="book.isbn" v-bind="book">

			</book-card>
		</div>
	</Layout>
</template>


<style scoped>
.sidebar{
	width: 24%;
	max-width: 16em;
	padding: 0.4em;
	position: fixed;
}
.books{
	margin-left: 25%;
}
</style>
