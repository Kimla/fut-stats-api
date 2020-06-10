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

            <table
                v-if="players && players.length > 0"
                class="table-auto w-full mt-3"
            >
                <thead>
                    <tr>
                        <th class="py-2 pr-2 border-b text-sm text-left">
                            Player
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-10">
                            G
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-10">
                            A
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-10">
                            P
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-12">
                            R
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="player in players"
                        :key="player.id"
                    >
                        <td class="border-b pr-2 py-2">
                            {{ player.name }}
                        </td>

                        <td class="border-b pl-2 py-2 w-10 text-center">
                            {{ player.goals }}
                        </td>

                        <td class="border-b pl-2 py-2 w-10 text-center">
                            {{ player.assists }}
                        </td>

                        <td class="border-b pl-2 py-2 w-10 text-center">
                            {{ player.games }}
                        </td>

                        <td class="border-b pl-2 py-2 w-12 text-center">
                            {{ player.rating }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="players && players.length > 0"
                class="mt-5"
            >
                <p class="text-sm mb-1">
                    G = Goals
                </p>

                <p class="text-sm mb-1">
                    A = Assists
                </p>

                <p class="text-sm mb-1">
                    P = Games played
                </p>

                <p class="text-sm mb-1">
                    R = Rating
                </p>
            </div>
        </div>

        <div class="w-32">
            <Button
                label="Delete"
                bg="bg-red-600"
                @click.native="removeWeekendLeague"
            />
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
import Button from '@/components/ui/Button';
import Game from '@/components/ui/Game';
import GameModal from '@/components/gameModal/GameModal';

export default {
    components: {
        AddButton,
        Button,
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
        },

        players () {
            const playersObject = this.games.map(game => {
                return game.player_statistics.map(playerStatistic => ({
                    id: playerStatistic.player.id,
                    name: playerStatistic.player.name,
                    rating: parseFloat(playerStatistic.rating),
                    goals: playerStatistic.goals,
                    assists: playerStatistic.assists
                }));
            }).flat(1).reduce((players, player) => {
                if (!players[player.id]) {
                    players[player.id] = {
                        id: player.id,
                        name: player.name,
                        rating: player.rating,
                        goals: player.goals,
                        assists: player.assists,
                        games: 1
                    };
                } else {
                    players[player.id].rating += player.rating;
                    players[player.id].goals += player.goals;
                    players[player.id].assists += player.assists;
                    if (player.rating > 0) {
                        players[player.id].games++;
                    }
                }

                return players;
            }, {});

            return Object.values(playersObject)
                .map(player => {
                    const rating = parseFloat(player.rating).toFixed(1);
                    player.rating = (rating / player.games).toFixed(1);
                    return player;
                })
                .sort((a, b) => b.rating - a.rating);
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
        },

        async removeWeekendLeague () {
            const res = await apiClient.delete(`/weekend-leagues/${this.$route.params.id}`);

            if (res && res.data) {
                this.$router.replace('/wl');
            }
        }
    }
};
</script>
