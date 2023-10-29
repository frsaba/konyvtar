<script setup>
import axios from 'axios'

defineProps(['modelValue'])
defineEmits(['update:modelValue', 'input'])

let languages = (await axios.get('/languages')).data

function languageItemProps(item) {
	return {
		title: item.short_name,
		subtitle: item.long_name,
	}
}

</script>

<template>
	<v-select class="select" hide-details label="Language" :items="languages" :value="modelValue" @update:modelValue="$emit('input', $event)"
		:item-props="languageItemProps"></v-select>
</template>

<style scoped>
.select{
	max-width: 10em;
	width: 5em;
	min-width: 5em;
}
</style>