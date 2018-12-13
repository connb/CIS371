<template>
<v-container fluid>
    <h2>Account</h2>
    <br>
    <v-layout row align-center justify-center>
        
		<v-flex offset-xs1 xs2>
            <v-layout column align-start justify-center>
                <h3>Change username</h3>
                <v-text-field label="New Username" v-model="newName"></v-text-field>
                <v-btn @click='rename'>Rename</v-btn>
            </v-layout>
        </v-flex>
        
        <v-flex xs1></v-flex>

        <v-flex xs2 >
            <v-layout column align-start justify-center>
                <h3>New highscore</h3>
                <v-text-field label="Score" v-model="score"></v-text-field>
                <v-btn @click='add'>Add</v-btn>
            </v-layout>
        </v-flex>   

    </v-layout>
    <v-layout column align-center>
        <v-flex xs4>
            <br><br>
            <v-btn class="centered" @click="goHome"> Return</v-btn>
        </v-flex>
    </v-layout>
</v-container>
</template>


<script>
import { mapState, mapActions } from 'vuex';
    export default {
        name: 'Account',
        data: function() {
            return {
                score: 0,
				newName: "",
            }
        },
        computed: {
            ...mapState(['scores', 'userID'])
        },
        methods: {
            ...mapActions(['addScore','renameUser']),
            goHome() {
                this.$router.push({path: '/'});
            },
            add() {
                if (this.validateInput()) {
                    this.addScore(this.score);
					this.score = 0;
                }
            },
			rename() {
				this.renameUser(this.newName);
				this.newName = "";
				//console.log("YEE");
            },
            validateInput() {
                if (this.score % 1 != 0 || (this.score < 1 || this.score > 10000) || isNaN(this.score)) {
                    alert("Oops! Score must be a whole number between 1 - 10,000");
                    return false;
                } else {
                    return true;
                }
            },

            checkLoggedOut() {
            if (this.userID == null) {
                this.goHome();
            }
            }
        },

        updated() {
            this.checkLoggedOut();
        }
    };
</script>