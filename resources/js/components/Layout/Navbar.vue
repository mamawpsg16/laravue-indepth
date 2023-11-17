<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item" v-if="!authenticated">
                        <router-link to="/register" class="nav-link">Register</router-link>
                    </li>
                    <li class="nav-item" v-if="!authenticated">
                        <router-link to="/login" class="nav-link">Login</router-link>
                    </li>
                    <li class="nav-item">{{ authenticated.data?.email }}</li>
                    <!-- Add more navigation items as needed -->
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        props:['authenticated']
    }
</script>

<style lang="scss" scoped>

</style>