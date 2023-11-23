import { defineStore } from 'pinia'

// You can name the return value of `defineStore()` anything you want,
// but it's best to use the name of the store and surround it with `use`
// and `Store` (e.g. `useUserStore`, `useCartStore`, `useProductStore`)
// the first argument is a unique id of the store across your application
export const useLayoutStore  = defineStore('layouts', {
  state: () => ({ 
    layout:{
      navbar:true,
      sidebar:true,
      main:true,
      footer:true,
    } 
  }),
  getters: {
  },
  actions: {
    setLayout(payload) {
      this.layout.navbar = payload.navbar;
      this.layout.sidebar = payload.sidebar;
      this.layout.main = payload.main;
      this.layout.footer = payload.footer;
    },
    hideLayout(showContent = true) {
      this.layout.navbar = false;
      this.layout.sidebar = false;
      this.layout.main = showContent;
      this.layout.footer = false;
    },
  },
})