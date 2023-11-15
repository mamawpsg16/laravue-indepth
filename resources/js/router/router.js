// These can be imported from other files
// import Home from '@/views/Practice/Index.vue'
// import HomeDetails from '@/views/Practice/Details.vue'
// import Page404 from '@/views/PageError/NotFound.vue'
// import UserSettings from '@/views/Practice/NamedRouterView/UserSettings.vue'
// import UserEmailSubscription from '@/views/Practice/NamedRouterView/UserEmailSubscription.vue'
// import UserProfile from '@/views/Practice/NamedRouterView/UserProfile.vue'
// import UserProfileDetails from '@/views/Practice/NamedRouterView/UserProfileDetails.vue'
const About = { template: '<div>About</div>' }
const Dashboard = { template: '<div>Dashboard</div>' }
const Posts = { template: '<div>Posts</div>' }

// 2. Define some routes
// Each route should map to a component.
// We'll talk about nested routes later.
const routes = [
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: () => import('@/views/PageError/NotFound.vue')},
  {
    path:'/settings',
    component:() => import('@/views/Practice/NamedRouterView/UserSettings.vue'),
    children:[
      {
        path:'email',
        name:'email',
        component:  () => import('@/views/Practice/NamedRouterView/UserEmailSubscription.vue'),
      },
      {
        path:'profile',
        name:'profile',
        components:{
          default: () => import('@/views/Practice/NamedRouterView/UserProfile.vue'),
          helper:() => import('@/views/Practice/NamedRouterView/UserProfileDetails.vue')
        }
      },
    ]
  },
  { 
    // path: '/app/:id',
    path: '/app',
    component: () => import('@/views/Practice/Index.vue'),
    children: [
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: '',
        name: 'home-details',
        component: () => import('@/views/Practice/Details.vue'),
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
  {
    path: '/test-1',
    name:'test-1',
    component: () => import('@/views/Practice/Test1.vue')
  },
  {
    path: '/test-2',
    name:'test-2',
    component: () => import('@/views/Practice/Test2.vue')
  },
  {
    path: '/challenge-1',
    name:'challenge-1',
    component: () => import('@/views/Practice/Challenge.vue')
  },
  /** OPTIONAL PARAMETER ? */
  // { path: '/app/:id/:name?', component: Home },
  { path: '/about', component: About },
]

export default  routes;