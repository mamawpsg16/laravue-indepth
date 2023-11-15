<template>
    <div>
        <p>Nested Named Views</p>
        <UserSettingsNav/>
        <router-view></router-view>
        <router-view name="helper"></router-view>
    </div>
</template>

<script>
import UserSettingsNav from './UserSettingsNav.vue';
    export default {
        components:{
            UserSettingsNav
        }
    }
</script>

<style lang="scss" scoped>

</style>