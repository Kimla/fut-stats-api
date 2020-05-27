<template>
    <div
        id="app"
        :class="{ loggedIn }"
        class="w-full flex relative"
    >
        <div class="relative w-full overflow-hidden">
            <div class="blob bg-indigo-100 rounded-full opacity-50"></div>

            <Navbar v-if="loggedIn" />

            <div class="inner relative h-full">
                <transition
                    name="fade"
                    mode="out-in"
                >
                    <router-view />
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from './components/layout/Navbar';
import { mapGetters } from 'vuex';
import axios from 'axios';

export default {
    components: {
        Navbar
    },

    computed: {
        ...mapGetters('auth', ['loggedIn'])
    },

    created () {
        axios.defaults.withCredentials = true;
        axios.get('/api/sanctum/csrf-cookie');
    }
};
</script>

<style>
html,
body {
    height: 100%;
}

#app {
    font-family: 'Roboto', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #161e2e;
    background-color: #fff;
    font-size: 16px;
    font-weight: 400;
    min-height: 100%;
}

.fade-enter,
.fade-leave-active {
    opacity: 0;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.blob {
    width: 500px;
    height: 500px;
    position: absolute;
    top: -150px;
    right: -200px;
}

.inner {
    width: 400px;
    max-width: 100%;
    margin: 0 auto;
}

.loggedIn {
    padding-top: 56px;
}
</style>
