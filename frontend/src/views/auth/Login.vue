<template>
    <div class="py-8 px-6 flex flex-col justify-between h-full">
        <AuthTop
            heading="Welcome to FutStats"
            text="Please login to continue"
        />

        <div class="mb-8">
            <form
                class="mb-6"
                @submit.prevent="login"
            >
                <AuthField
                    v-model="email"
                    label="Email"
                    name="email"
                    type="email"
                    autocomplete="username"
                />

                <AuthField
                    v-model="password"
                    label="Password"
                    name="password"
                    type="password"
                    class="mb-6"
                    autocomplete="current-password"
                />

                <Button
                    label="Log me in"
                    type="submit"
                />
            </form>

            <AuthLink
                title="Forgot password"
                url="/reset-password"
            />
        </div>

        <div>
            <p class="text-center mb-2">
                Don't have an account?
            </p>
            <router-link
                to="/register"
                class="block w-full text-center uppercase w-full border border-indigo-500 border-solid text-indigo-500 p-3 rounded tracking-wider text-sm font-medium"
            >
                Register
            </router-link>
        </div>
    </div>
</template>

<script>
import AuthTop from '@/components/auth/AuthTop';
import AuthField from '@/components/auth/AuthField';
import AuthLink from '@/components/auth/AuthLink';
import Button from '@/components/ui/Button';
import { mapGetters } from 'vuex';

export default {
    components: {
        AuthTop,
        AuthField,
        Button,
        AuthLink
    },

    data: () => ({
        email: null,
        password: null
    }),

    computed: {
        ...mapGetters('auth', ['authUser', 'error', 'loading'])
    },

    methods: {
        login () {
            this.$store.dispatch('auth/login', this.$data).then(() => {
                this.$router.push(this.$route.query.redirect || '/');
            });
        }
    }
};
</script>
