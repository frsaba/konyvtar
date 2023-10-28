<script>
import BookCard from '../Shared/BookCard.vue';
import Layout from '../Shared/Layout.vue';
export default {
	components: { Layout, BookCard, Layout },
	props: {
		books: Array,
	},
	data:() =>({
		searchTitle: "",
		searchAuthors: ""
	}),
	computed: {
		filteredBooks(){
			let books = this.books;
			if(this.searchTitle) 
				books = books.filter(b => this.matches(this.getBookTitle(b),this.searchTitle))
			if(this.searchAuthors)
				books = books.filter(b => this.matches(this.getBookAuthors(b),this.searchAuthors))
			return books;
		},
	},
	methods:{
		getBookTitle(book){
			return book.translations[0]?.title
		},
		getBookAuthors(book) {
			return book.authors?.map(author => author?.name).join(", ");
		},
		matches(str,query){
			return str.toLowerCase().includes(query.toLowerCase())
		}
	}
}
</script>

<template>
	<Layout>

		
		<div class="sidebar">
			<h1>Search books</h1>
			<v-text-field
				name="title_search"
				v-model.trim="searchTitle"
				label="Search in title"
			></v-text-field>

			<v-text-field
				name="author_search"
				label="Search authors"
				v-model.trim="searchAuthors"
			></v-text-field>

		</div>

		<div class="books">
			<book-card v-for="book in filteredBooks ?? []" :key="book.isbn" v-bind="book">

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
