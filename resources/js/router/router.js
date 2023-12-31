// These can be imported from other files
// import Home from '@/views/Practice/Index.vue'
// import HomeDetails from '@/views/Practice/Details.vue'
// import Page404 from '@/views/PageError/NotFound.vue'
// import UserSettings from '@/views/Practice/NamedRouterView/UserSettings.vue'
// import UserEmailSubscription from '@/views/Practice/NamedRouterView/UserEmailSubscription.vue'
import UserProfile from '@/views/Practice/NamedRouterView/UserProfile.vue'
import Dashboard from '@/views/Practice/Dashboard.vue'
const About = { template: "<div>About</div>" };
// const Dashboard = { template: "<div>Dashboard</div>" };
const Posts = { template: "<div>Posts</div>" };
import { defineAsyncComponent } from 'vue'
import LoadingSpinner from '@/components/Loaders/Spinner.vue'
// 2. Define some routes
// Each route should map to a component.
// We'll talk about nested routes later.
const routes = [
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: () => import("@/views/PageError/NotFound.vue"),
    },
    {
        path: "/",
        name: "home",
        redirect: "/dashboard",
        component: () =>  import("@/views/Practice/Dashboard.vue"),
    },
    {
        path: "/dashboard",
        name: "/dashboard",
        component: () =>  import("@/views/Practice/Dashboard.vue"),
    },
    {
        path: "/settings",
        component: () =>
            import("@/views/Practice/NamedRouterView/UserSettings.vue"),
        children: [
            {
                path: "email",
                name: "email",
                component: () =>
                    import(
                        "@/views/Practice/NamedRouterView/UserEmailSubscription.vue"
                    ),
            },
            {
                path: "profile",
                name: "profile",
                components: {
                    default: () =>
                        import(
                            "@/views/Practice/NamedRouterView/UserProfile.vue"
                        ),
                    helper: () =>
                        import(
                            "@/views/Practice/NamedRouterView/UserProfileDetails.vue"
                        ),
                },
            },
        ],
    },
    {
        // path: '/app/:id',
        path: "/app",
        component: () => import("@/views/Practice/Index.vue"),
        children: [
            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                path: "",
                name: "home-details",
                component: () => import("@/views/Practice/Details.vue"),
            },
            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                path: "dashboard",
                name: "dashboard",
                component: Dashboard,
            },
            {
                // UserPosts will be rendered inside User's <router-view>
                // when /user/:id/posts is matched
                path: "posts",
                name: "posts",
                component: Posts,
            },
        ],
    },
    {
        path: "/test-1",
        name: "test-1",
        component: () => import("@/views/Practice/Test1.vue"),
    },
    {
        path: "/test-2",
        name: "test-2",
        component: () => import("@/views/Practice/Test2.vue"),
    },
    {
        path: "/challenge-1",
        name: "challenge-1",
        component: () => import("@/views/Practice/Challenge.vue"),
    },
    {
        path: "/products",
        name: "products",
        component: () => import("@/views/Product/Index.vue"),
        // component: () => defineAsyncComponent({
        //   displayName: 'Product',
        //   // the loader function
        //   loader: () => import("@/views/Product/Index.vue"),
        
        //   // A component to use while the async component is loading
        //   loadingComponent: LoadingSpinner,
        //   // Delay before showing the loading component. Default: 200ms.
        //   delay: 0,
        
        //   // // A component to use if the load fails
        //   // errorComponent: ErrorComponent,
        //   // // The error component will be displayed if a timeout is
        //   // // provided and exceeded. Default: Infinity.
        //   // timeout: 3000
        // }),
    },
    {
        path: "/register",
        name: "register",
        component: () => import("@/views/Authentication/Register.vue"),
    },
    {
        path: "/login",
        name: "login",
        component: () => import("@/views/Authentication/Login.vue"),
    },
    {
        path: "/shops",
        name: "shops",
        component: () => import("@/views/Shop/Index.vue"),
    },
    {
        path: "/ag-grid",
        name: "ag-grid",
        component: () => import("@/views/Practice/AgGrid.vue"),
    },

    /** OPTIONAL PARAMETER ? */
    // { path: '/app/:id/:name?', component: Home },
    { path: "/about", component: About },
];

export default routes;
