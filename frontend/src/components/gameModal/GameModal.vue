<template>
    <div class="w-full h-full top-0 left-0 fixed p-8 z-50 flex items-center">
        <div
            class="absolute top-0 left-0 w-full h-full bg-gray-900 opacity-50"
            @click="close"
        ></div>

        <div class="py-8 px-6 bg-white relative w-full m-auto overflow-auto max-h-full">
            <div class="mb-3">
                <label
                    class="inline-block mb-2"
                    for="outcome"
                >
                    Outcome
                </label>
                <select
                    id="outcome"
                    v-model="formData.outcome"
                    name="outcome"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option value="win">
                        Win
                    </option>
                    <option value="loss">
                        Loss
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label
                    for="goals"
                    class="inline-block mb-2"
                >
                    Goals
                </label>
                <select
                    id="goals"
                    v-model="formData.goals"
                    name="goals"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option
                        v-for="i in 21"
                        :key="i"
                        :value="i - 1"
                    >
                        {{ i - 1 }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label
                    for="conceded"
                    class="inline-block mb-2"
                >
                    Conceded
                </label>
                <select
                    id="conceded"
                    v-model="formData.conceded"
                    name="conceded"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option
                        v-for="i in 21"
                        :key="i"
                        :value="i - 1"
                    >
                        {{ i - 1 }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label
                    class="inline-block mb-2"
                    for="overtime"
                >
                    Overtime
                </label>
                <select
                    id="overtime"
                    v-model="formData.overtime"
                    name="overtime"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option :value="false">
                        No
                    </option>
                    <option :value="true">
                        Yes
                    </option>
                </select>
            </div>

            <div class="mb-5">
                <label
                    class="inline-block mb-2"
                    for="penalties"
                >
                    Penalties
                </label>
                <select
                    id="penalties"
                    v-model="formData.penalties"
                    name="penalties"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option :value="false">
                        No
                    </option>
                    <option :value="true">
                        Yes
                    </option>
                </select>
            </div>

            <Button
                class="mb-4"
                label="Save"
                @click.native="save"
            />

            <Button
                v-if="!isNew"
                label="Delete"
                bg="bg-red-600"
                @click.native="remove"
            />
        </div>
    </div>
</template>

<script>
import { apiClient } from '@/services/API';
import Button from '../ui/Button';

export default {
    components: {
        Button
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
        loading: false,
        isNew: true
    }),

    created () {
        this.formData.weekend_league_id = this.$route.params.id;

        if (this.game) {
            this.formData = { ...this.game };
            this.isNew = false;
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
            const res = await apiClient.put(`/games/${this.game.id}`, this.formData);

            return res;
        },

        async add () {
            const res = await apiClient.post('/games', this.formData);

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
