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
    <v-card class="pa-5 card">
        <h2 class="title">{{ title }} ({{ publish_year }})</h2>
        <v-img class="thumbnail" :src="thumbnail"></v-img>
        <span class="authors">
            Authored by: {{ joinedAuthors }}
        </span>

        <span class="isbn">   
            #{{ isbn }}
        </span>
        <div class="tags">

            <v-chip v-for="tag in tagNames">{{ tag }}</v-chip>
        </div>
    </v-card>
</template>

<style scoped>
.card{
    display: grid;
    gap: 2em;
    grid-template-areas: 
        "thumbnail title isbn"
        "thumbnail authors authors"
        "thumbnail tags tags";
    grid-template-columns: minmax(3em, 10em) 4fr min-content;
}
.title{
    grid-area: title;
}
.authors{
    font-style: italic;
    grid-area: authors;
}
.isbn{
    grid-area: isbn;
}
.thumbnail{
    grid-area: thumbnail;
}
.tags{
    grid-area: tags;
}
</style>