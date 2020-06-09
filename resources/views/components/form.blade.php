<ValidationObserver v-slot="{ handleSubmit }">
    <form @submit.prevent="handleSubmit(save)">
        {{ $slot }}
    </form>
</ValidationObserver>