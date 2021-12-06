<template>
    <Head title="Register" />

    <form @submit.prevent="submit">
        <div>
            <PhoneAuthLabel for="name" value="Name" />
            <PhoneAuthInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                            required autofocus autocomplete="name" />
            <PhoneAuthInputError :message="form.errors.name" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="number" value="Phone Number" />
            <PhoneAuthInput id="number" type="tel" class="mt-1 block w-full" v-model="form.number"
                            required autocomplete="tel" />
            <PhoneAuthInputError :message="form.errors.number" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="password" value="Password" />
            <PhoneAuthInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="new-password" />
            <PhoneAuthInputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="password_confirmation" value="Confirm Password" />
            <PhoneAuthInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />
            <PhoneAuthInputError :message="form.errors.password_confirmation" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Already registered?
            </Link>

            <PhoneAuthButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register
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
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: PhoneAuthGuestLayout,

    components: {
        PhoneAuthButton,
        PhoneAuthInput,
        PhoneAuthInputError,
        PhoneAuthLabel,
        Head,
        Link,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                number: '',
                password: '',
                password_confirmation: '',
                terms: false,
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
