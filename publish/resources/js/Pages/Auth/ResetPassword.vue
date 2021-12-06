<template>
    <Head title="Reset Password" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <PhoneAuthLabel for="token" value="Verification Token" />
            <PhoneAuthInput id="token" type="text" class="mt-1 block w-full" v-model="form.token"
                            required autofocus autocomplete="token" />
            <PhoneAuthInputError :message="form.errors.token" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="number" value="Phone Number" />
            <PhoneAuthInput id="number" type="tel" class="mt-1 block w-full" v-model="form.number"
                            required autofocus autocomplete="tel" />
            <PhoneAuthInputError :message="form.errors.number" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="password" value="Password" />
            <PhoneAuthInput id="password" type="password" class="mt-1 block w-full"
                            v-model="form.password" required autocomplete="new-password" />
            <PhoneAuthInputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="password_confirmation" value="Confirm Password" />
            <PhoneAuthInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <PhoneAuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Reset Password
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
        PhoneAuthLabel,
        PhoneAuthInputError,
        Head,
    },

    props: {
        number: String,
        status : String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: '',
                number: this.number,
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.$page.url , {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
