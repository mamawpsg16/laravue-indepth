<template>
    <div>
        <router-link to="/app">Home</router-link> ||
        <router-link to="/settings">Named Route</router-link> ||
        <router-link :to="{name:'challenge-1'}">Challenge 1</router-link> ||
        <router-link :to="{name:'test-1'}">Test 1</router-link> ||
        <router-link :to="{name:'test-2'}">Test 2</router-link> ||
        <router-link to="/app/dashboard">Dashboard</router-link> ||
        <router-link to="/app/posts">Posts</router-link> ||
        <router-link to="/about">About</router-link> 
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

</style>