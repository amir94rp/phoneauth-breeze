<template>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form @submit.prevent="submit">
        <div>
            <PhoneAuthLabel for="password" value="Password" />
            <PhoneAuthInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                         required autocomplete="current-password" autofocus />
            <PhoneAuthInputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="flex justify-end mt-4">
            <PhoneAuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Confirm
            </PhoneAuthButton>
        </div>
    </form>
</template>

<script>
import PhoneAuthButton from '@/Components/Button.vue'
import PhoneAuthGuestLayout from '@/Layouts/Guest.vue'
import PhoneAuthInput from '@/Components/Input.vue'
import PhoneAuthLabel from '@/Components/Label.vue'
import PhoneAuthInputError from '@/Components/InputError.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    layout: PhoneAuthGuestLayout,

    components: {
        PhoneAuthButton,
        PhoneAuthInput,
        PhoneAuthLabel,
        PhoneAuthInputError,
        Head,
    },

    data() {
        return {
            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.confirm'), {
                onFinish: () => this.form.reset(),
            })
        }
    }
}
</script>
