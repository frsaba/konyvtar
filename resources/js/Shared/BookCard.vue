<script>
import {DateTime} from 'luxon'
export default {
	props: {
		id: Number,
		isbn: String,
		authors: Array,
		publish_year: Number,
		thumbnail: String,
		tags: Array,
		translations: Array,
		thumbnail: String,
        created_at: String,
        updated_at: String
	},
    methods:{
        formatDate(date){
            return DateTime.fromISO(date).toRelative();

        }
    },
	computed: {
		title() {
			return this.translations[0]?.title
		},
		description() {
			return this.translations[0]?.description
		},
		joinedAuthors() {
			return this.authors?.map(author => author?.name).join(", ");
		},
		tagNames() {
			return this.tags?.map(tag => tag.translations[0]?.name)
		}
	}
}
</script>

<template>
	<v-card class="pa-5 ma-5 card">
		<h2 class="title">{{ title }} ({{ publish_year }})</h2>
		<v-img class="thumbnail" :src="thumbnail"></v-img>
		<span class="authors">
			Authored by: {{ joinedAuthors }}
		</span>

		<span class="description">{{ description }}</span>

		<v-btn color="success" @click="$emit('edit-book')">Edit</v-btn>

		<span class="isbn">
			#{{ isbn }}
		</span>
		<div class="tags">

			<v-chip v-for="tag in tagNames" :key="tag">{{ tag }}</v-chip>
		</div>
        <span class="create-date text-small">Created: {{ formatDate(created_at) }}</span>
        <span class="update-date text-small">Last updated: {{ formatDate(updated_at) }}</span>

	</v-card>
</template>

<style scoped>
.card {
	display: grid;
	gap: 1em;
	grid-template-areas:
		"thumbnail title isbn"
		"thumbnail authors authors"
		"thumbnail description description"
		"thumbnail tags edit"
        "thumbnail create-date create-date"
        "thumbnail update-date update-date";
	grid-template-columns: minmax(3em, 10em) 4fr max-content;
}
.text-small{
    font-size: smaller;
    font-style: italic;
}

.title {
	grid-area: title;
}

.edit {
	grid-area: edit;
}

.authors {
	font-style: italic;
	grid-area: authors;
}

.description {
	grid-area: description;
	font-size: small;
}

.isbn {
	grid-area: isbn;
}

.thumbnail {
	grid-area: thumbnail;
}

.tags {
	grid-area: tags;
    display: flex;
    flex-wrap: wrap;
    gap: 0.2em;
}
.create-date {
    grid-area: create-date;
}

.update-date{
    grid-area: update-date;
}
</style>