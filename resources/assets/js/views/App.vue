<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <router-link :to="{name: 'home'}" class="navbar-brand">Treclon</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <Search></Search>
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->

                        <router-link :to="{ name: 'login' }" class="nav-link" v-if="!isLoggedIn">Login</router-link>
                        <router-link :to="{ name: 'register' }" class="nav-link" v-if="!isLoggedIn">Register</router-link>
                     <!--   <li class="nav-link" v-if="isLoggedIn"> Hi, {{name}}</li>-->
                        <ul v-else class="nav-link ">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, {{name}} <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/user/preferences"><i class="icon-cog"></i> Preferences</a></li>
                                    <li><a href="/help/support"><i class="icon-envelope"></i> Contact Support</a></li>
                                    <li class="divider"></li>
                                    <li><a @click="logout" href="#"><i class="icon-off"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                      <!--  <router-link :to="{ name: 'board' }" class="nav-link" v-if="isLoggedIn">Board</router-link>-->
                        <router-link :to="{ name: 'newnote' }" class="nav-link" v-if="isLoggedIn">New note</router-link>
                        <router-link :to="{ name: 'notelist' }" class="nav-link">notes</router-link>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <router-view></router-view>
        </main>
    </div>
</template>


<script>
    import Search from './Search';
export default {
    data(){
        return {
            isLoggedIn : null,
            name : null
        }
    },
    mounted(){
        this.isLoggedIn = localStorage.getItem('jwt')
        this.name = localStorage.getItem('user')
    },
    methods : {
        logout(e){
            e.preventDefault();

            /*axios.post('api/logout', {})
                    .then(response => {
                        localStorage.removeItem('user');
                        localStorage.removeItem('jwt');
                        this.isLoggedIn = null;
                        this.name=null;
                        // goto board
                    })
                    .catch(function (error) {
                        console.error(error);
                    });*/
            localStorage.removeItem('user');
            localStorage.removeItem('jwt');
            this.isLoggedIn = null;
            this.name=null;
            this.$radio.$emit('logout');

        }
    },
    components: {
        Search
    }
}
</script>
