<template>
    <nav class="navbar fixed top-0 left-0 bg-white w-full z-50 shadow w-full">
        <ul class="flex">
            <li class="w-1/5">
                <router-link
                    to="/wl"
                    class="link text-gray-700 block p-4"
                >
                    <CalendarIcon class="w-6 h-6 m-auto" />
                </router-link>
            </li>

            <li class="w-1/5">
                <router-link
                    to="/team"
                    class="link text-gray-700 block p-4"
                >
                    <UsersIcon class="w-6 h-6 m-auto" />
                </router-link>
            </li>

            <li class="w-1/5">
                <router-link
                    to="/watch"
                    class="link text-gray-700 block p-4"
                >
                    <InboxIcon class="w-6 h-6 m-auto" />
                </router-link>
            </li>

            <li class="w-1/5">
                <router-link
                    to="/bell"
                    class="link text-gray-700 block p-4"
                >
                    <BellIcon class="w-6 h-6 m-auto" />
                </router-link>
            </li>

            <li class="w-1/5">
                <button
                    type="button"
                    class="link text-gray-700 block p-4"
                    @click="logout"
                >
                    <LogOutIcon class="w-6 h-6 m-auto" />
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
import BellIcon from '@/assets/bell.svg';
import CalendarIcon from '@/assets/calendar.svg';
import InboxIcon from '@/assets/inbox.svg';
import UsersIcon from '@/assets/users.svg';
import LogOutIcon from '@/assets/log-out.svg';

export default {
    components: {
        BellIcon,
        CalendarIcon,
        InboxIcon,
        UsersIcon,
        LogOutIcon
    },

    methods: {
        logout () {
            this.$store.dispatch('auth/logout').then(() => {
                this.$router.push('/login');
            });
        }
    }
};
</script>

<style scoped>
.router-link-active {
    color: #4c51bf;
}
</style>
