<script setup>
defineProps(['book', 'language'])
import LanguageSelect from '../Shared/LanguageSelect.vue';
import Layout from '../Shared/Layout.vue';

let selectedLanguage;
</script>

<template>
	<Layout>
		<template v-slot:header>
			<div class="d-flex justify-between px-5 pt-2">
				<h1>Edit book</h1>
				<v-spacer></v-spacer>
				<Suspense>
					<language-select v-model="selectedLanguage" @input="selectedLanguage = $event"></language-select>
				</Suspense>

			</div>
		</template>
		<v-form>
			<v-text-field label="Title"></v-text-field>
			<v-textarea label="Description"></v-textarea>
		</v-form>
	</Layout>
</template>