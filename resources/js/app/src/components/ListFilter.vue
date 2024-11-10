<template>
    <div class="mt-8 flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <div></div>
        <div class="flex flex-col items-center gap-2 max-lg:flex-1 min-[375px]:flex-row lg:gap-4 mb-4">
            <button type="button"
                class="flex items-center justify-center gap-2 rounded-full px-3 py-2 font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background max-lg:w-full bg-navy-black-800"><svg
                    viewBox="0 0 20 20" fill="none" class="size-5 flex-shrink-0">
                    <path fill="currentColor" fill-rule="evenodd"
                        d="M6.01 15.74a.6.6 0 0 0 .6-.6V5.288a.6.6 0 0 0-1.025-.424l-2.41 2.421a.6.6 0 1 0 .85.847L5.411 6.74v8.4a.6.6 0 0 0 .6.6M8.684 6.175a.6.6 0 0 1 .6-.6h1.896a.6.6 0 0 1 0 1.2H9.284a.6.6 0 0 1-.6-.6m0 4.09a.6.6 0 0 1 .6-.6h4.024a.6.6 0 0 1 0 1.2H9.284a.6.6 0 0 1-.6-.6m0 4.09a.6.6 0 0 1 .6-.6h7.117a.6.6 0 0 1 0 1.2H9.284a.6.6 0 0 1-.6-.6"
                        clip-rule="evenodd"></path>
                </svg>Newest</button><button type="button"
                class="flex items-center justify-center gap-2 rounded-full px-3 py-2 font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background max-lg:w-full text-muted-foreground hover:bg-accent/50"><svg
                    fill="none" viewBox="0 0 20 20" class="size-5 flex-shrink-0">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.01057 4.68437C6.34194 4.68437 6.61057 4.953 6.61057 5.28437V15.1384C6.61057 15.3813 6.4641 15.6002 6.23958 15.693C6.01506 15.7857 5.75679 15.7338 5.5854 15.5618L3.17482 13.1408C2.94102 12.906 2.94183 12.5261 3.17665 12.2922C3.41146 12.0585 3.79136 12.0593 4.02518 12.2941L5.41057 13.6854V5.28437C5.41057 4.953 5.6792 4.68437 6.01057 4.68437Z"
                        fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.6844 6.175C8.6844 5.84363 8.95304 5.575 9.2844 5.575H16.4006C16.7319 5.575 17.0006 5.84363 17.0006 6.175C17.0006 6.50636 16.7319 6.775 16.4006 6.775H9.2844C8.95304 6.775 8.6844 6.50636 8.6844 6.175ZM8.6844 10.2649C8.6844 9.93352 8.95304 9.66488 9.2844 9.66488H13.3085C13.6398 9.66488 13.9085 9.93352 13.9085 10.2649C13.9085 10.5962 13.6398 10.8649 13.3085 10.8649H9.2844C8.95304 10.8649 8.6844 10.5962 8.6844 10.2649ZM8.6844 14.3554C8.6844 14.0241 8.95304 13.7554 9.2844 13.7554H11.1802C11.5115 13.7554 11.7802 14.0241 11.7802 14.3554C11.7802 14.6869 11.5115 14.9554 11.1802 14.9554H9.2844C8.95304 14.9554 8.6844 14.6869 8.6844 14.3554Z"
                        fill="currentColor"></path>
                </svg>Oldest</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ListFilter',
    components: {},
    props: {},
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
