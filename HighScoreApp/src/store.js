import Vue from 'vue'
import Vuex, { mapState } from 'vuex'
import firebase from 'firebase'
import firebaseui from 'firebaseui'

Vue.use(Vuex)

const config = {
  apiKey: "AIzaSyDJX8KvKzv5RtA8aqIAcCiYu0MXPGNXS4M",
  authDomain: "mbtd-e3ffc.firebaseapp.com",
  databaseURL: "https://mbtd-e3ffc.firebaseio.com",
  projectId: "mbtd-e3ffc",
  storageBucket: "mbtd-e3ffc.appspot.com",
  messagingSenderId: "304422916874"
};
firebase.initializeApp(config);

export default new Vuex.Store({
  state: {
    userID: '',
    userName: null,
    loggedIn: false,
    ui: new firebaseui.auth.AuthUI(firebase.auth()),
    db: firebase.database(),
    leaderboardObj: null,
    usersRef: firebase.database().ref().child("users"),
    scoresRef: firebase.database().ref().child("scores"),
    scores: null,
    users: null,
  },

  mutations: {
    LOGIN(state, profile) {
      state.loggedIn = true;
      state.userID = profile.uid;

      state.usersRef.once('value', function(snapshot) {
        var ss = snapshot.toJSON()[state.userID];

        if (ss == null) {
          // Add user to firebase
          state.db.ref('users/' + profile.uid).set({
            userName: profile.displayName,
            //uid: profile.uid
          });
          console.log(state);
          state.userName = profile.displayName;
          console.log(profile.displayName);
          console.log(state.userName);

        } else {
          // Get preexisting username
          state.userName = ss.userName;
        }
      });
    },

    LOGOUT(state) {
      state.loggedIn = false;
      state.userName = null;
      state.userID = '';
    },

    INSERT_SCORE(state, score) {
      var currentScore = 0;
      state.scoresRef.child(state.userID).once('value', function(snapshot) {
        
        // If a score already exists:
        if (snapshot.val() != null) {
          currentScore = snapshot.val();

          if (parseInt(currentScore) < score) {
            state.db.ref('scores/' + state.userID).set(score);
          }

        } else {
            state.db.ref('scores/' + state.userID).set(score);
        }
      });
    },

    CREATE_LISTENERS(state) {
      state.scoresRef.on("value", snapshot => {
        state.scores = snapshot.toJSON();
      });

      state.usersRef.on("value", snapshot => {
        state.users = snapshot.toJSON();
      })
    },

    DELETE_SCORE(state, key){
    	state.scoresRef.child(key).remove();
    },

	  RENAME_USER(state, name){
      state.usersRef.child(state.userID).child("userName").set(name);
      state.userName = name;
    },
  },

  actions: {
    async login({commit}) {
      const provider = new firebase.auth.GoogleAuthProvider();
      try {
        const result = await firebase.auth().signInWithPopup(provider);
        commit('LOGIN', result.user);
      } catch (error) {
        //console.log(error);
      }
    },

    async logout({commit}) {
      firebase.auth().signOut();
      commit('LOGOUT');
    },

    createListeners({commit}) {
      commit('CREATE_LISTENERS');
    },

    addScore({commit}, score) {
      commit('INSERT_SCORE', score);
    },

    deleteScore({commit}, key){
    	commit('DELETE_SCORE', key);
    },
	
	  renameUser({commit}, name){
    	commit('RENAME_USER', name);
    },
   },

  computed: mapState({
    loggedIn: state => state.loggedIn,
    userName: state => state.userName,
    userID: state => state.userID,
    scores: state => state.scores,
    users: state => state.users,
  }),
})