<script setup>
const props = defineProps(['page', 'book', 'languages'])
import axios from 'axios';
import Layout from '../Shared/Layout.vue';
import { validationRules } from '../Shared/formValidationRules';
import { computed, ref, watchEffect, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'

let form = true;
let isbn = ref(props.book.isbn);
let isbnRules = [validationRules.required, validationRules.isbn];
let publishYear = ref(props.book.publish_year);
let publishYearRules = [validationRules.required, , validationRules.year];
let thumbnail = ref(props.book.thumbnail);


let selectedLanguage = ref(props.languages[0]);

onMounted(() => {
	const queryParams = new URLSearchParams(window.location.search);
	const lang = queryParams.get('lang');
	if (lang) selectedLanguage.value = props.languages.find(l => l.short_name == lang)
});

let activeTranslation;
let newlyCreatedTranslations = [];
watchEffect(() => {
	if (props.book.translations.every(translation => translation.language_id != selectedLanguage.value.id)) {
		let newTranslation = {
			book_id: props.book.id,
			language_id: selectedLanguage.value.id,
			title: "",
			description: "",
			id: null
		}
		props.book.translations.push(newTranslation)
		newlyCreatedTranslations.push(newTranslation)
	}

	activeTranslation = props.book.translations.find(translation => translation.language_id == selectedLanguage.value.id)
	// console.log(props.book.translations, selectedLanguage.value.id)
})

async function saveBook() {
	await Inertia.put(`/books/${props.book.id}`, {
		id: props.book.id,
		isbn,
		publishYear,
		thumbnail,
		translations: props.book.translations
	})
}
async function deleteBook(){
	await Inertia.delete(`/books/${props.book.id}`);
}

</script>

<template>
	<Layout>
		<template v-slot:header>
			<div class="d-flex justify-between px-5 pt-2">
				<h1>Edit book #{{ book.isbn }}</h1>
				<v-spacer></v-spacer>
				<v-select class="select" hide-details label="Language" :items="languages" v-model="selectedLanguage"
					:item-props="(item) => ({ title: item.short_name, subtitle: item.long_name, })">
				</v-select>

			</div>
		</template>
		<v-form v-model="form" @submit.prevent="saveBook">
			<v-text-field label="ISBN" :rules="isbnRules" v-model="isbn"></v-text-field>
			<v-text-field type="number" :rules="publishYearRules" label="Publication year"
				v-model="publishYear"></v-text-field>
			<v-text-field label="Thumbnail link" v-model="thumbnail"></v-text-field>
			<v-text-field label="Title" v-model="activeTranslation.title"></v-text-field>
			<v-textarea label="Description" v-model="activeTranslation.description"></v-textarea>
			<!-- {{ book.translations }} -->
			{{ book }}
			<v-btn color="error" @click="deleteBook">Delete book</v-btn>
			<v-btn color="success" type="submit">Save changes</v-btn>
		</v-form>
	</Layout>
</template>

<style scoped>
.select {
	max-width: 10em;
	width: 5em;
	min-width: 5em;
}
</style>