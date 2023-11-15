<template>
    <div>
       
        <li> 
            <span v-if="isActive">-></span>{{ team }}
        </li>
    </div>
</template>

<script>
    export default {
        props:['id','isActive','team']
    }
</script>

<style lang="scss" scoped>

</style>