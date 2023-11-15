<template>
    <input type="text" :value="title" @input="updateTitle" v-bind="$attrs">
    <input type="text" :value="author" @input="updateAuthor" v-bind="$attrs">
    <input type="text" :value="content" @input="updateContent" v-bind="$attrs">
</template>

<script setup>
const props = defineProps([
    'title',
    'author',
    'content'
]);
const emits = defineEmits(['update:title','update:author','update:content']);

const updateTitle = (e) => {
    return emits('update:title', e.target.value);
}

const updateAuthor = (e) => {
    return emits('update:author', e.target.value);
}

const updateContent = (e) => {
    return emits('update:content', e.target.value);
}

</script>

<style lang="scss" scoped></style>