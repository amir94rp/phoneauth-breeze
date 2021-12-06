<template>
    <Head title="Forgot Password" />

    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your phone number and we will text you a token that will
        allow you to choose a new one.
    </div>

    <form @submit.prevent="submit">
        <div>
            <PhoneAuthLabel for="number" value="Phone Number" />
            <PhoneAuthInput id="number" type="tel" class="mt-1 block w-full" v-model="form.number"
                         required autofocus autocomplete="tel" />
            <PhoneAuthInputError :message="form.errors.number" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PhoneAuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                SEND VERIFICATION TOKEN
            </PhoneAuthButton>
        </div>
    </form>
</template>

<script>
import PhoneAuthButton from '@/Components/Button.vue'
import PhoneAuthGuestLayout from '@/Layouts/Guest.vue'
import PhoneAuthInput from '@/Components/Input.vue'
import PhoneAuthInputError from '@/Components/InputError.vue'
import PhoneAuthLabel from '@/Components/Label.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    layout: PhoneAuthGuestLayout,

    components: {
        PhoneAuthButton,
        PhoneAuthInput,
        PhoneAuthInputError,
        PhoneAuthLabel,
        Head,
    },

    data() {
        return {
            form: this.$inertia.form({
                number: ''
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('forgot.store'))
        }
    }
}
</script>
