<script>
export default {
	props: {
		isbn: String,
        authors: Array,
        publish_year: Number,
        thumbnail: String,
        tags: Array,
        translations: Array,
        thumbnail: String
	},
    computed:{
        title(){
            return this.translations[0].title
        },
        joinedAuthors(){
            return this.authors.map(author => author.name).join(", ");
        },
        tagNames(){
            return this.tags.map(tag => tag.translations[0].name)
        }
    }
}
</script>

<template>
    <v-card class="pa-5">
        <v-card-title class="title">{{ title }} ({{ publish_year }})</v-card-title>
        <span class="author">
            Authored by: {{ joinedAuthors }}
        </span>

        <span class="isbn">   
            #{{ isbn }}
        </span>
        <div class="chips">

            <v-chip v-for="tag in tagNames">{{ tag }}</v-chip>
        </div>
    </v-card>
</template>

<style scoped>

</style>