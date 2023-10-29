<script>
import BookCard from '../Shared/BookCard.vue';
import Layout from '../Shared/Layout.vue';
import LanguageSelect from '../Shared/LanguageSelect.vue';
export default {
	components: { Layout, BookCard, Layout, LanguageSelect },
	props: {
		books: Array,
		tags: Array,
		languages: Array
	},
	data: () => ({
		searchTitle: "",
		searchAuthors: "",
		searchTags: [],
		selectedLanguage: ""
	}),
	computed: {
		filteredBooks() {
			let books = this.books.filter(book =>
				this.matches(this.getBookTitle(book), this.searchTitle) &&
				this.matches(this.getBookAuthors(book), this.searchAuthors));

			if (this.searchTags.length > 0)
				books = books.filter(book => this.searchTagIDs.some(tagID => this.bookHasTag(book, tagID)));
			return books;
		},
		tagNames() {
			return this.tags?.map(tag => tag?.translations[0]?.name)
		},
		searchTagIDs() {
			return this.searchTags.map(i => this.tags[i].id)
		}
	},
	methods: {
		getBookTitle(book) {
			return book.translations[0]?.title
		},
		getBookAuthors(book) {
			return book.authors?.map(author => author?.name).join(", ");
		},
		bookHasTag(book, tagID) {
			return book.tags.some(tag => tag.id == tagID);
		},
		matches(str, query) {
			if (query == "") return true;
			return str.toLowerCase().includes(query.toLowerCase())
		},
	},
	watch: {
		selectedLanguage(language) {
			this.$inertia.get('/', { lang: language.short_name }, { preserveState: true, replace: true })
		}
	}
}
</script>

<template>
	<Layout>
		<template v-slot:header>
			<div class="d-flex justify-between px-5 pt-2">
				<h1>Search books</h1>
				<v-spacer></v-spacer>
				<Suspense>
					<language-select v-model="selectedLanguage" @input="selectedLanguage = $event"></language-select>
				</Suspense>
			</div>
		</template>


		<div class="sidebar">

			<v-text-field name="title_search" v-model.trim="searchTitle" label="Search in title"></v-text-field>
			<v-text-field name="author_search" label="Search authors" v-model.trim="searchAuthors"></v-text-field>
			<v-chip-group v-model="searchTags" column multiple>
				<v-chip v-for="tag in tagNames">{{ tag }}</v-chip>
			</v-chip-group>

		</div>

		<div class="books">
			<book-card v-for="book in filteredBooks ?? []" :key="book.isbn" v-bind="book">

			</book-card>
			<div class="text-center" v-if="filteredBooks.length == 0">No results</div>
		</div>
	</Layout>
</template>


<style scoped>
.sidebar {
	padding: 0.5em;
}

@media screen and (min-width: 800px) {
	.sidebar {
		width: 24%;
		max-width: 16em;
		position: fixed;
	}

	.books {
		margin-left: 25%;
	}
}
</style>
