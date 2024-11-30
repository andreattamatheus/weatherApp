<template>
    <div class="absolute bottom-1 right-0 left-0 mr-auto ml-auto text-center w-40">
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-500 rounded-lg bg-green-50 bg-white-800 text-green-400 border-green-800"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                {{ props?.message ? props.message : 'Success alert!' }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NotificationPopUp",
    components: {},
    props: {
        message: {
            type: String,
            default: "Success alert!"
        },
        type: {
            type: String,
            default: "success"
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

<style>

</style>