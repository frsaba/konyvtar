<script>
import BookCard from '../Shared/BookCard.vue';
export default {
	components: {BookCard},
	props: {
		books: Array,
	}
}
</script>

<template>
	<h1>Books</h1>

	<div class="books">
		<book-card v-for="book in books" :key="book.isbn" v-bind="book">

		</book-card>
	</div>
</template>


<style></style>
