<template>
    <div class="team py-8 px-6">
        <h1 class="text-2xl mb-4">
            Team
        </h1>

        <div class="mb-10 pb-10 border-b-2">
            <draggable
                v-model="players"
                @end="updateSortOrder"
            >
                <div
                    v-for="player in players"
                    :key="player.id"
                    class="bg-white w-full flex items-center justify-between shadow py-2 px-4 mb-3 text-xl relative"
                >
                    <p class="pr-6">
                        {{ player.name }}
                    </p>

                    <button
                        type="button"
                        class="text-gray-700"
                        @click="removePlayer(player.id)"
                    >
                        <RemoveIcon class="w-6 h-6" />
                    </button>
                </div>
            </draggable>
        </div>

        <div class="mb-10 pb-10 border-b-2">
            <h1 class="text-2xl mb-4">
                Add player
            </h1>

            <form @submit.prevent="addPlayer">
                <div class="flex">
                    <input
                        id="player"
                        v-model="newPlayer"
                        name="player"
                        placeholder="Name..."
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
import draggable from 'vuedraggable';
import Button from '@/components/ui/Button';
import RemoveIcon from '@/assets/x-circle.svg';
import { apiClient } from '@/services/API';

export default {
    components: {
        Button,
        RemoveIcon,
        draggable
    },

    data: () => ({
        players: [],
        newPlayer: null
    }),

    async created () {
        const res = await apiClient('/team-players');

        this.players = res.data;
    },

    methods: {
        async addPlayer () {
            const res = await apiClient.post('/team-players', {
                name: this.newPlayer
            });

            this.newPlayer = null;

            if (res) {
                this.players.push(res.data.player);
            }
        },

        async removePlayer (id) {
            const res = await apiClient.delete(`/team-players/${id}`, {
                name: this.newPlayer
            });

            if (res) {
                const player = this.players.find(p => p.id === id);
                const index = this.players.indexOf(player);
                this.players.splice(index, 1);
            }
        },

        async updateSortOrder () {
            await apiClient.post('/team-players/sort-order', {
                players: this.players.map((player, index) => ({
                    id: player.id,
                    sort_order: index
                }))
            });
        }
    }
};
</script>
