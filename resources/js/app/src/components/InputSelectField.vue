<template>
    <div>
        <label class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 block" for="country-select">
            Country
        </label>
        <select :value="value" id="country-select"
            class="flex h-10 w-full rounded-lg border bg-input px-3 py-2.5 text-sm caret-primary transition-all placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 focus-visible:ring-ring"
            required>
            <option disabled value="">Select a country</option>
            <option v-for="country in options" :key="country.code" :value="country">{{
                country.name }}
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
            required: false
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
