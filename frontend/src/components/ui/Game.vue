<template>
    <div
        :class="[ game.outcome === 'win' ? 'bg-green-600' : 'bg-red-600' ]"
        class="game text-white shadow p-2 mb-3 text-xl text-center relative"
    >
        <p>
            {{ game.goals }} - {{ game.conceded }}
        </p>

        <p
            v-if="game.overtime"
            class="text-xs absolute tracking-wider right-0 top-0 px-3 py-1 bg-gray-800"
        >
            OT
        </p>

        <p
            v-if="game.penalties"
            class="text-xs absolute tracking-wider right-0 top-0 px-3 py-1 bg-gray-800"
        >
            PEN
        </p>
    </div>
</template>

<script>
export default {
    props: {
        game: {
            type: Object,
            required: true
        }
    }
};
</script>
