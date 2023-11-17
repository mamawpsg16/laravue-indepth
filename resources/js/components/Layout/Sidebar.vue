<template>
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar vh-100">
        <div class="position-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/products" class="nav-link">Products</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/settings" class="nav-link">Settings</router-link>
                </li>
                <!-- Add more sidebar items as needed -->
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        
    }
</script>

<style lang="scss" scoped>

</style>