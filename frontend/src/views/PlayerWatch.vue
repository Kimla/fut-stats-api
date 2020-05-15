<template>
    <div class="py-8 px-6">
        <h1 class="text-2xl mb-4">
            Player watch
        </h1>

        <form
            v-if="!selectedPlayer"
            class="mb-6"
        >
            <input
                id="player_name"
                type="text"
                placeholder="Player name..."
                name="player_name"
                class="bg-gray-200 p-3 block w-full rounded transition"
                required
                @input="search($event.target.value)"
            >
        </form>

        <div v-if="!selectedPlayer">
            <button
                v-for="player in players"
                :key="player.id"
                type="button"
                class="bg-white block w-full shadow py-2 px-4 mb-3 text-xl text-center relative"
                @click="selectPlayer(player)"
            >
                <p>
                    {{ player.full_name }} ({{ player.rating }})
                </p>
            </button>
        </div>

        <div v-if="selectedPlayer">
            <h1 class="mb-2 font-bold">
                Selected player
            </h1>

            <div class="bg-white block bg-indigo-600 text-white w-full shadow py-2 px-4 mb-3 text-xl text-center relative">
                <p>
                    {{ selectedPlayer.full_name }} ({{ selectedPlayer.rating }})
                </p>
            </div>

            <form @submit.prevent="handleSubmit">
                <div class="mb-6">
                    <label
                        for="min_amount"
                        class="inline-block mb-2"
                    >
                        Watch lower than
                    </label>

                    <input
                        id="min_amount"
                        v-model="newWatcher.min_amount"
                        type="number"
                        name="min_amount"
                        class="bg-gray-200 p-3 block w-full rounded transition"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label
                        for="max_amount"
                        class="inline-block mb-2"
                    >
                        Watch higher than
                    </label>

                    <input
                        id="max_amount"
                        v-model="newWatcher.max_amount"
                        type="number"
                        name="max_amount"
                        class="bg-gray-200 p-3 block w-full rounded transition"
                        required
                    >
                </div>

                <Button
                    label="Submit"
                    type="submit"
                />
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Button from '@/components/ui/Button';
import { apiClient } from '@/services/API';

export default {
    components: {
        Button
    },

    data: () => ({
        selectedPlayer: null,
        newWatcher: {
            min_amount: null,
            max_amount: null
        },
        players: []
    }),

    async mounted () {
        const res = await apiClient.get('/player-price-watch');

        console.log(res);
    },

    methods: {
        async search (searchTerm) {
            try {
                const res = await axios.get(`https://www.futbin.com/search?year=20&extra=1&v=1&term=${searchTerm}`, {
                    withCredentials: false
                });

                this.players = res.data;
            } catch (error) {
                this.players = [];
            }
        },

        selectPlayer (player) {
            this.selectedPlayer = player;
        },

        async handleSubmit () {
            const imagePaths = this.selectedPlayer.image.split('/');
            const endPaths = imagePaths[imagePaths.length - 1].split('.');
            const id = endPaths[0];

            const res = await apiClient.post('/player-price-watch', {
                futbin_id: id,
                title: `${this.selectedPlayer.full_name} (${this.selectedPlayer.rating})`,
                min_amount: this.newWatcher.min_amount,
                max_amount: this.newWatcher.max_amount
            });

            if (res) {
                this.selectPlayer = null;
                this.newWatcher = {};
                this.players = [];
            }
        }
    }
};
</script>

<style>
</style>
