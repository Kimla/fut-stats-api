<template>
    <div class="py-8 px-6">
        <h1 class="text-2xl mb-4">
            Games
        </h1>

        <div class="mb-10 pb-10 border-b-2">
            <Game
                v-for="game in games"
                :key="game.id"
                :game="game"
                @click.native="openGame(game)"
            />
        </div>

        <div class="mb-10 pb-10 border-b-2">
            <h2 class="text-2xl mb-4">
                Stats
            </h2>

            <p>Played: {{ played }}</p>
            <p>Win/Loss: {{ totalWins }} - {{ totalLosses }}</p>
            <p>Goals: {{ totalGoals }} - {{ totalConceds }}</p>
            <p>Goals per game: {{ goalsPerGame }}</p>
            <p>Conceds per game: {{ concedsPerGame }}</p>
            <p>Overtime: {{ overtimeGames }}</p>
            <p>Penalties: {{ penaltiesGames }}</p>
        </div>

        <GameModal
            v-if="gameModalOpen"
            :game="gameModalData"
            @added="addGame"
            @updated="updateGame"
            @remove="removeGame"
            @close="closeModal"
        />

        <AddButton
            @click.native="gameModalOpen = true"
        />
    </div>
</template>

<script>
import { apiClient } from '@/services/API';
import AddButton from '@/components/ui/AddButton';
import Game from '@/components/ui/Game';
import GameModal from '@/components/gameModal/GameModal';

export default {
    components: {
        AddButton,
        Game,
        GameModal
    },

    data: () => ({
        gameModalOpen: false,
        gameModalData: null,
        games: []
    }),

    computed: {
        played () {
            return this.games.length;
        },

        totalWins () {
            return this.games.filter(game => game.outcome === 'win').length;
        },

        totalLosses () {
            return this.games.filter(game => game.outcome === 'loss').length;
        },

        totalGoals () {
            return this.games.reduce((total, game) => {
                total += game.goals;
                return total;
            }, 0);
        },

        totalConceds () {
            return this.games.reduce((total, game) => {
                total += game.conceded;
                return total;
            }, 0);
        },

        goalsPerGame () {
            return this.totalGoals > 0 ? (this.totalGoals / this.played).toFixed(2) : 0;
        },

        concedsPerGame () {
            return this.totalConceds > 0 ? (this.totalConceds / this.played).toFixed(2) : 0;
        },

        overtimeGames () {
            const games = this.games.filter(game => game.overtime);
            let wins = 0;
            let losses = 0;

            games.forEach(game => {
                if (game.outcome === 'win') {
                    wins++;
                } else {
                    losses++;
                }
            });

            return `${wins} - ${losses}`;
        },

        penaltiesGames () {
            const games = this.games.filter(game => game.penalties);
            let wins = 0;
            let losses = 0;

            games.forEach(game => {
                if (game.outcome === 'win') {
                    wins++;
                } else {
                    losses++;
                }
            });

            return `${wins} - ${losses}`;
        }
    },

    async created () {
        const res = await apiClient(`/weekend-leagues/${this.$route.params.id}`);

        this.games = res.data.games;
    },

    methods: {
        addGame (game) {
            this.games.push(game);

            this.closeModal();
        },

        updateGame (updatedGame) {
            const games = [...this.games];
            const index = this.games.indexOf(this.gameModalData);

            games[index] = updatedGame;
            this.games = games;

            this.closeModal();
        },

        removeGame () {
            const index = this.games.indexOf(this.gameModalData);

            this.games.splice(index, 1);

            this.closeModal();
        },

        openGame (game) {
            this.gameModalData = game;
            this.gameModalOpen = true;
        },

        closeModal () {
            this.gameModalOpen = false;
            this.gameModalData = null;
        }
    }
};
</script>
