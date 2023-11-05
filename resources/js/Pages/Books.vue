<script>
import BookCard from '../Shared/BookCard.vue';
import Layout from '../Shared/Layout.vue';
import { validationRules } from '../Shared/formValidationRules';
export default {
	components: { Layout, BookCard, Layout },
	props: {
		books: Array,
		tags: Array,
		languages: Array
	},
	data: () => ({
		searchTitle: "",
		searchAuthors: "",
		searchTags: [],
		selectedLanguage: "",
		errors: [],
		newBookISBN: "",
		isbnRules: [
			validationRules.required,
			validationRules.isbn,
			validationRules.isbn_not_exists
		],
		newBookYear: 2023,
		publishYearRules: [validationRules.required, , validationRules.year],
		newBookForm: false
	}),
	mounted() {
		const queryParams = new URLSearchParams(window.location.search);
		const lang = queryParams.get('lang');
		if (lang) this.selectedLanguage = this.languages.find(l => l.short_name == lang)
		else
			this.selectedLanguage = this.languages[0];


	},
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
		createNewBook() {
			this.$inertia.post("/books", { isbn: this.newBookISBN, year: this.newBookYear })
		}
	},
	watch: {
		selectedLanguage(language) {
			// console.log(this.selectedLanguage)
			this.$inertia.get('/', { lang: language.short_name }, { preserveState: true, replace: true })
		}
	}
}
</script>

<template>
	<Layout>
		<template v-slot:header>
			<div class="header d-flex justify-between flex-wrap align-center px-5 pt-2">
				<h1>Search books</h1>
				<v-spacer></v-spacer>
				<v-dialog width="500">
					<template v-slot:activator="{ props }">
						<v-btn color="success" v-bind="props" text="New book"> </v-btn>
					</template>

					<template v-slot:default>
						<v-card title="New book" class="pa-5">
							<div class="errors text-center mb-2">
								<span color="error" v-for="error, n of errors" :key="n">{{ error }}</span>
							</div>
							<v-form v-model="newBookForm" @submit.prevent="createNewBook">
								<v-text-field v-model.trim="newBookISBN" :rules="isbnRules" label="ISBN"></v-text-field>
								<v-text-field type="number" v-model.number="newBookYear" :rules="publishYearRules"
									label="Publication year"></v-text-field>

								<v-btn block color="success" text="Create new book" type="submit"
									:disabled="errors.length > 0 || !newBookForm"></v-btn>
							</v-form>
						</v-card>
					</template>
				</v-dialog>

				<v-select class="select" hide-details label="Language" :items="languages" v-model="selectedLanguage"
					:item-props="(item) => ({ title: item.short_name, subtitle: item.long_name, })">
				</v-select>
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
			<book-card v-for="book in filteredBooks ?? []" :key="book.isbn" v-bind="book"
				@edit-book="$inertia.visit(`/books/${book.id}/edit`, { data: { lang: selectedLanguage?.short_name ?? languages[0].short_name } });">

			</book-card>
			<div class="text-center" v-if="filteredBooks.length == 0">No results</div>
		</div>
	</Layout>
</template>


<style scoped>
.header {
	gap: 1em;
}

.errors {
	font-style: italic;
	color: red;
	height: 1.2em;
}

.select {
	max-width: 10em;
	width: 5em;
	min-width: 5em;
}

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
