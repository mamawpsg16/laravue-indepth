import { createRouter,createWebHistory } from "vue-router";
// These can be imported from other files
import Home from '@/views/Practice/Index.vue'
import HomeDetails from '@/views/Practice/Details.vue'
import Page404 from '@/views/PageError/NotFound.vue'
import UserSettings from '@/views/Practice/NamedRouterView/UserSettings.vue'
import UserEmailSubscription from '@/views/Practice/NamedRouterView/UserEmailSubscription.vue'
import UserProfile from '@/views/Practice/NamedRouterView/UserProfile.vue'
import UserProfileDetails from '@/views/Practice/NamedRouterView/UserProfileDetails.vue'
const About = { template: '<div>About</div>' }
const Dashboard = { template: '<div>Dashboard</div>' }
const Posts = { template: '<div>Posts</div>' }

// 2. Define some routes
// Each route should map to a component.
// We'll talk about nested routes later.
const routes = [
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: Page404 },
  {
    path:'/settings',
    component:UserSettings,
    children:[
      {
        path:'email',
        name:'email',
        component:UserEmailSubscription
      },
      {
        path:'profile',
        name:'profile',
        components:{
          default:UserProfile,
          helper:UserProfileDetails
        }
      },
    ]
  },
  { 
    // path: '/app/:id',
    path: '/app',
    component: Home,
    children: [
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: '',
        name: 'home-details',
        component: HomeDetails,
      },
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: 'dashboard',
        name: 'dashboard',
        component: Dashboard,
      },
      {
        // UserPosts will be rendered inside User's <router-view>
        // when /user/:id/posts is matched
        path: 'posts',
        name: 'posts',
        component: Posts,
      },
    ],
  },
  /** OPTIONAL PARAMETER ? */
  // { path: '/app/:id/:name?', component: Home },
  { path: '/about', component: About },
]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

export default router;