<template>
    <div
        class="w-full h-full top-0 left-0 fixed z-50"
        style="padding-top: 56px;"
    >
        <div
            class="absolute top-0 left-0 w-full h-full bg-gray-900 opacity-50"
            @click="close"
        ></div>

        <nav class="navbar absolute top-0 left-0 bg-white w-full z-50 shadow w-full flex items-center justify-between">
            <button
                type="button"
                class="link text-gray-700 block p-4 mr-4"
                @click="close"
            >
                <ChevronLeftIcon class="w-6 h-6 m-auto" />
            </button>

            <div class="px-4 flex">
                <Button
                    v-if="!isNew"
                    label="Delete"
                    bg="bg-red-600"
                    size="sm"
                    @click.native="remove"
                />

                <Button
                    label="Save"
                    size="sm"
                    class="ml-5"
                    @click.native="save"
                />
            </div>
        </nav>

        <div class="py-8 px-6 bg-white relative w-full h-full overflow-auto max-h-full">
            <FormGroup
                label="Outcome"
                name="outcome"
            >
                <AppSelect
                    v-model="formData.outcome"
                    :items="[{ label: 'Win', value: 'win' }, { label: 'Loss', value: 'loss' }]"
                    name="outcome"
                />
            </FormGroup>

            <FormGroup
                label="Goals"
                name="goals"
            >
                <AppSelect
                    v-model.number="formData.goals"
                    :items="selectNumberItems"
                    name="goals"
                />
            </FormGroup>

            <FormGroup
                label="Goals"
                name="conceded"
            >
                <AppSelect
                    v-model.number="formData.conceded"
                    :items="selectNumberItems"
                    name="conceded"
                />
            </FormGroup>

            <FormGroup
                label="Overtime"
                name="overtime"
            >
                <AppSelect
                    :value="formData.overtime"
                    :items="[{ label: 'No', value: false }, { label: 'Yes', value: true }]"
                    name="overtime"
                    @input="val => formData.overtime = val == 'true' ? true : false"
                />
            </FormGroup>

            <FormGroup
                label="Penalties"
                name="penalties"
            >
                <AppSelect
                    :value="formData.penalties"
                    :items="[{ label: 'No', value: false }, { label: 'Yes', value: true }]"
                    name="penalties"
                    @input="val => formData.penalties = val == 'true' ? true : false"
                />
            </FormGroup>

            <table
                v-if="playerStatistics && playerStatistics.length > 0"
                class="table-auto w-full mt-8"
            >
                <thead>
                    <tr>
                        <th class="py-2 pr-2 border-b text-sm text-left">
                            Player
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-12">
                            R
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-12">
                            G
                        </th>
                        <th class="pl-2 py-2 border-b text-sm w-12">
                            A
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="playerStatistic in playerStatistics"
                        :key="playerStatistic.player_id"
                    >
                        <td class="border-b pr-2 py-2">
                            {{ playerStatistic.player.name }}
                        </td>

                        <td class="border-b pl-2 py-2 w-12">
                            <AppSelect
                                v-model.number="playerStatistic.rating"
                                :items="selectPlayerRating"
                                name="goals"
                                size="sm"
                                style="text-align-last:center;"
                            />
                        </td>

                        <td class="border-b pl-2 py-2 w-12">
                            <AppSelect
                                v-model.number="playerStatistic.goals"
                                :items="selectNumberItems"
                                name="goals"
                                size="sm"
                                style="text-align-last:center;"
                            />
                        </td>

                        <td class="border-b pl-2 py-2 w-12">
                            <AppSelect
                                v-model.number="playerStatistic.assists"
                                :items="selectNumberItems"
                                name="assists"
                                size="sm"
                                style="text-align-last:center;"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { apiClient } from '@/services/API';
import AppSelect from '../ui/AppSelect';
import Button from '../ui/Button';
import FormGroup from '../ui/FormGroup';
import ChevronLeftIcon from '@/assets/chevron-left.svg';

export default {
    components: {
        AppSelect,
        Button,
        FormGroup,
        ChevronLeftIcon
    },

    props: {
        game: {
            type: Object,
            default: null
        }
    },

    data: () => ({
        formData: {
            outcome: 'win',
            goals: 0,
            conceded: 0,
            overtime: false,
            penalties: false
        },
        playerStatistics: [],
        loading: false,
        isNew: true
    }),

    computed: {
        selectNumberItems () {
            const items = [];

            for (let i = 0; i < 21; i++) {
                items.push({ label: i, value: i });
            }

            return items;
        },

        selectPlayerRating () {
            const items = [];

            for (let i = 0; i < 101; i++) {
                const rating = (i * 0.1).toFixed(1);
                items.push({ label: rating, value: rating });
            }

            return items;
        }
    },

    async created () {
        this.formData.weekend_league_id = this.$route.params.id;

        if (this.game) {
            this.formData = { ...this.game };
            this.playerStatistics = this.game.player_statistics.map(playerStatistic => ({
                ...playerStatistic,
                rating: playerStatistic.rating.toFixed(1)
            }));

            this.isNew = false;
        } else {
            const res = await apiClient('/team-players');

            this.playerStatistics = res.data.map(player => ({
                rating: (0.0).toFixed(1),
                goals: 0,
                assists: 0,
                player_id: player.id,
                player: player
            }));
        }
    },

    methods: {
        async save () {
            if (this.loading) return;

            this.loading = true;

            const action = this.isNew ? 'added' : 'updated';

            if (action === 'added') {
                const id = await this.add();
                this.formData.id = id;
            } else if (action === 'updated') {
                await this.update();
            }

            this.loading = false;

            this.$emit(action, this.formData);
        },

        async update () {
            const res = await apiClient.put(`/games/${this.game.id}`, {
                playerStatistics: this.playerStatistics,
                ...this.formData
            });

            return res;
        },

        async add () {
            const res = await apiClient.post('/games', {
                playerStatistics: this.playerStatistics,
                ...this.formData
            });

            return res.data.id;
        },

        async remove () {
            if (this.loading) return;

            this.loading = true;

            await apiClient.delete(`/games/${this.game.id}`);

            this.loading = false;

            this.$emit('remove');
        },

        close () {
            this.$emit('close');
        }
    }
};
</script>
