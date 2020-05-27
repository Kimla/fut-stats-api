<template>
    <div class="team py-8 px-6">
        <h1 class="text-2xl mb-4">
            Team
        </h1>

        <div class="mb-10 pb-10 border-b-2">
            <div
                v-for="player in players"
                :key="player"
                class="bg-white w-full flex justify-between shadow py-2 px-4 mb-3 text-xl relative"
            >
                <p>
                    {{ player }}
                </p>

                <button
                    type="button"
                    class="text-gray-700"
                    @click="removePlayer(player)"
                >
                    <RemoveIcon class="w-6 h-6" />
                </button>
            </div>
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
import Button from '@/components/ui/Button';
import RemoveIcon from '@/assets/x-circle.svg';

export default {
    components: {
        Button,
        RemoveIcon
    },

    data: () => ({
        players: [],
        newPlayer: null
    }),

    methods: {
        addPlayer () {
            this.players.push(this.newPlayer);

            this.newPlayer = null;
        },

        removePlayer (player) {
            const index = this.players.indexOf(player);

            this.players.splice(index);
        }
    }
};
</script>
