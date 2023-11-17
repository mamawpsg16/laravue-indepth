import { createRouter,createWebHistory } from "vue-router";
import routes from './router.js'
import { isUserAuthenticated } from "../composables/authentication.js";
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
// GOOD
// This is a navigation guard in Vue Router. It runs before each navigation.
router.beforeEach(async (to, from, next) => {
    const isAuthenticated = await isUserAuthenticated();
    const isTryingToAccessRestrictedRoute = ['login', 'register'].includes(to.name);

    if (isAuthenticated) {
        // If the user is authenticated and trying to access 'login' or 'register', redirect to '/dashboard'.
        if (isTryingToAccessRestrictedRoute) {
            next('/dashboard');
        } else {
            // Continue with the navigation for other routes.
            next();
        }
    } else {
        // If the user is not authenticated and is not navigating to 'login', redirect to 'login'.
        if (!isAuthenticated && !isTryingToAccessRestrictedRoute) {
            next('login');
        } else {
            // Continue with the navigation for the 'login' route when the user is not authenticated.
            next();
        }
    }
});


export default router;