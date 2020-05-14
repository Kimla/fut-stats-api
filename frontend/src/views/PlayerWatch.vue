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
                        for="amount"
                        class="inline-block mb-2"
                    >
                        Watch lower than
                    </label>

                    <input
                        id="amount"
                        v-model="amount"
                        type="number"
                        name="amount"
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

export default {
    components: {
        Button
    },

    data: () => ({
        selectedPlayer: null,
        amount: null,
        players: []
    }),

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

        handleSubmit () {
        }
    }
};
</script>

<style>
</style>
