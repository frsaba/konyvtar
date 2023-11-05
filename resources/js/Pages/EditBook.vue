<script setup>
const props = defineProps(['page', 'book', 'languages', 'authors', 'tags'])
import axios from 'axios';
import Layout from '../Shared/Layout.vue';
import { validationRules } from '../Shared/formValidationRules';
import { computed, ref, watchEffect, onMounted, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'

let form = true;
let isbn = ref(props.book.isbn);
let isbnRules = [validationRules.required, validationRules.isbn];
let publishYear = ref(props.book.publish_year);
let publishYearRules = [validationRules.required, , validationRules.year];
let thumbnail = ref(props.book.thumbnail);


let selectedLanguage = ref(props.languages[0]);
let selectedAuthorNames = ref(props.book.authors.map(a => a.name));
let selectedTags = ref([]);

let newTagDialog = ref(false);
// let selectedLanguageIndex = computed(() => props.languages.indexOf(l => {
// 	console.log(l, selectedLanguage)
// 	return l.id == selectedLanguage.value.id
// }))
let selectedLanguageIndex = ref(0);
let newTagTranslations = ref(props.languages.map(l => ""))

onMounted(() => {
	const queryParams = new URLSearchParams(window.location.search);
	const lang = queryParams.get('lang');
	if (lang) selectedLanguage.value = props.languages.find(l => l.short_name == lang)

	selectedTags.value = props.book.tags.map(tag => getTagTranslationName(tag));
});

let activeTranslation;
//watch for selectedLanguage change
watchEffect(() => {
	//if this book doesn't have a translation for this language yet, create it
	if (props.book.translations.every(translation => translation.language_id != selectedLanguage.value.id)) {
		let newTranslation = {
			book_id: props.book.id,
			language_id: selectedLanguage.value.id,
			title: "",
			description: "",
			id: null
		}
		props.book.translations.push(newTranslation)
	}

	activeTranslation = props.book.translations.find(translation => translation.language_id == selectedLanguage.value.id)
	selectedTags.value = props.book.tags.map(tag => getTagTranslationName(tag));
	selectedLanguageIndex.value = props.languages.findIndex(l => {
		return l.id == selectedLanguage.value.id
	})
	// console.log(selectedLanguageIndex, props.languages)
	// console.log(props.book.translations, selectedLanguage.value.id)
})

let allAuthorNames = computed(() => {
	return props.authors.map(a => a.name)
})

let allTagNames = computed(() => {
	return props.tags.map(tag => getTagTranslationName(tag))
})

function getTagTranslationName(tag) {
	let tagTranslation = tag.translations.find(translation => translation.language_id == selectedLanguage.value.id) ?? tag.translations[0]
	return tagTranslation.name
}

watch(selectedTags, () => {
	let newTag = selectedTags.value.find(name => allTagNames.value.includes(name) == false);
	if (newTag) {
		newTagDialog.value = true;
		newTagTranslations.value[selectedLanguageIndex.value] = newTag;
	}
})

async function closeNewTagDialog() {
	let allTags = (await axios.get('/tags')).data;
	selectedTags.value = selectedTags.value.filter(name => allTags.some(tag => getTagTranslationName(tag) == name)); //remove the newly added tag
	newTagDialog.value = false;
	newTagTranslations.value = props.languages.map(l => "");
	// console.log("click outside")

}

async function createNewTag() {
	await Inertia.post('/tags', { translations: newTagTranslations.value, languages: props.languages })
	newTagDialog.value = false;
	newTagTranslations.value = props.languages.map(l => "");
}


async function saveBook() {
	let authorsToRemoveFromBook = props.book.authors.filter(a => selectedAuthorNames.value.includes(a.name) == false)
	let authorsToAddToBook = selectedAuthorNames.value.filter(name => props.book.authors.some(a => a.name == name) == false)
	let authorsToCreate = selectedAuthorNames.value.filter(name => allAuthorNames.value.includes(name) == false)


	let allTags = (await axios.get('/tags')).data;
	let tagNamesToAddToBook = selectedTags.value.filter(name => props.book.tags.some(tag => getTagTranslationName(tag) == name) == false)
	let tagsToAddToBook = tagNamesToAddToBook.map(name => allTags.find(tag => tag.translations.some(translation => translation.name == name)))
	let tagsToRemoveFromBook = props.book.tags.filter(tag => selectedTags.value.includes(getTagTranslationName(tag)) == false)

	// console.log(tagsToAddToBook, tagsToRemoveFromBook)
	// console.log({originalAuthors: props.book.authors, selectedAuthorNames, authorsToRemoveFromBook, authorsToAddToBook, authorsToCreate})
	await Inertia.put(`/books/${props.book.id}`, {
		id: props.book.id,
		isbn,
		publishYear,
		thumbnail,
		translations: props.book.translations,
		authorsToCreate,
		authorsToAddToBook,
		authorsToRemoveFromBook,
		tagsToAddToBook,
		tagsToRemoveFromBook
	})
}
async function deleteBook() {
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
		<v-dialog v-model="newTagDialog" width="500" @click:outside="closeNewTagDialog" @keydown.esc="closeNewTagDialog" >
			<v-card class="pa-5">
				<v-form @submit.prevent="createNewTag">
					<v-text-field v-for="language, i in languages" name="name" required :label="`New tag (${language.long_name})`"
					v-model.trim="newTagTranslations[i]" autofocus></v-text-field>
					<v-btn color="success" type="submit" :disabled="newTagTranslations.some(t => t == '')">Add new tag</v-btn>
				</v-form>
			</v-card>
		</v-dialog>

		<v-form v-model="form" @submit.prevent="saveBook">
			<v-text-field label="ISBN" :rules="isbnRules" v-model="isbn"></v-text-field>
			<v-text-field type="number" :rules="publishYearRules" label="Publication year"
				v-model="publishYear"></v-text-field>
			<v-text-field label="Thumbnail link" v-model="thumbnail"></v-text-field>
			<v-combobox v-model="selectedAuthorNames" :items="allAuthorNames" multiple chips clearable deletable-chips
				hide-selected label="Select or create authors">
			</v-combobox>

			<v-combobox v-model="selectedTags" :items="allTagNames" multiple chips clearable deletable-chips hide-selected
				label="Select or create tags">
			</v-combobox>

			<v-text-field label="Title" v-model="activeTranslation.title"></v-text-field>
			<v-textarea label="Description" v-model="activeTranslation.description"></v-textarea>
			<!-- {{ book.translations }} -->
			<!-- {{ book }} -->


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