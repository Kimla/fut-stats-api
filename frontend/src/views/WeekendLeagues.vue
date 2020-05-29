<template>
    <div class="py-8 px-6">
        <h1 class="text-2xl mb-4">
            Weekend leagues
        </h1>

        <div class="mb-10 pb-10 border-b-2">
            <router-link
                v-for="weekendLeague in weekendLeagues"
                :key="weekendLeague.id"
                :to="`/wl/${weekendLeague.id}`"
                class="bg-white w-full flex justify-between shadow py-2 px-4 mb-3 text-xl text-center relative"
            >
                <p>
                    {{ weekendLeague.title }}
                </p>

                <p class="pl-4 flex-shrink-0">
                    {{ weekendLeague.score.wins }} - {{ weekendLeague.score.losses }}
                </p>
            </router-link>
        </div>

        <div class="mb-10 pb-10 border-b-2">
            <h1 class="text-2xl mb-4">
                Add new
            </h1>

            <form @submit.prevent="submitHandler">
                <div class="flex">
                    <input
                        id="title"
                        v-model="formData.title"
                        name="title"
                        placeholder="Title..."
                        type="text"
                        class="bg-gray-200 p-3 block w-full rounded transition"
                        required
                    >

                    <div class="buttonHolder w-24 flex-shrink-0">
                        <Button
                            label="Add"
                            type="submit"
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Button from '@/components/ui/Button';
import { apiClient } from '@/services/API';

export default {
    components: {
        Button
    },

    data: () => ({
        formData: {},
        weekendLeagues: []
    }),

    computed: {

    },

    async created () {
        const res = await apiClient('/weekend-leagues');

        this.weekendLeagues = res.data;
    },

    methods: {
        async submitHandler () {
            const res = await apiClient.post('/weekend-leagues', this.formData);

            this.weekendLeagues.unshift(res.data.item);
        }
    }
};
</script>
