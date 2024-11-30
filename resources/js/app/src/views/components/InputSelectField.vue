<template>
    <div>
        <label class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 block" for="country-select">
            {{ fieldLabel }}
        </label>
        <select :value="value" id="country-select"
            class="flex h-10 w-full rounded-lg border bg-input px-3 py-2.5 text-sm caret-primary transition-all placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 focus-visible:ring-ring"
            required>
            <option disabled value="">Select a {{ fieldType }}</option>
            <option v-for="option in options" :key="option.code" :value="option.name">
                {{ option.name }}
            </option>
        </select>
        <span class="text-sm text-red-500 mt-1 ml-1" v-if="validationErrors.state">
            {{ validationErrors.state }}
        </span>
    </div>
</template>

<script>
export default {
    name: 'InputSelectField',
    components: {},
    props: {
        fieldLabel: {
            type: String,
            required: true
        },
        fieldType: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: false
        },
        validationErrors: {
            type: String,
            required: false
        },
        options: {
            type: Array,
            required: false
        }
    },
    data() {
        return {
        }
    },
    watch: {},
    computed: {},
    methods: {},
    created() { },
    mounted() { }
}
</script>

<style></style>
