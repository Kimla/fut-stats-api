<template>
    <div class="mb-4">
        <label
            :for="name"
            class="inline-block mb-2"
        >
            {{ label }}
        </label>
        <input
            :id="name"
            :name="name"
            :type="type"
            :autocomplete="autocomplete"
            class="bg-gray-200 p-3 block w-full rounded transition"
            required
            @input="$emit('input', $event.target.value)"
        >
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true
        },

        name: {
            type: String,
            required: true
        },

        type: {
            type: String,
            default: 'text'
        },

        autocomplete: {
            type: String,
            default: null
        }
    }
};
</script>
