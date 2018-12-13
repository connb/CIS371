<template>
  <v-app>
    <v-toolbar app>
      <v-toolbar-title class="headline">
        <span>MBTD:</span>
        <span class="font-weight-light">MicroBio Tower Defense</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn v-if="this.loggedIn" @click="goToAccount">{{this.userName}}</v-btn>

    <v-btn v-if="this.loggedIn" @click="log_out">Logout</v-btn>
    <v-btn
      class="mr-5"
      v-else
      flat
      @click="this.login"
    >Login</v-btn>
    </v-toolbar>

    <v-content>
      <transition name="moveInUp">
      <router-view/>
      </transition>
    </v-content>
  </v-app>
</template>

<script>
import { mapState } from 'vuex';
import { mapActions } from 'vuex';
export default {
  name: 'App',
  components: {
  },
  data () {
    return {
      //
    }
  },
  computed: {
      ...mapState(['loggedIn', 'userName'])
  },
  methods: {
    ...mapActions(['login', 'logout']),
    goToAccount() {
      this.$router.push({path: '/Account'});
    },

    log_out() {
      console.log(this.$router.currentRoute);
      this.logout();
      if (this.$router.currentRoute.path == "/Account") {
        this.$router.push({path: '/'});
      }
    },

  }
}
</script>