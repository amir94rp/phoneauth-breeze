<template>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <PhoneAuthLabel for="number" value="Phone Number" />
            <PhoneAuthInput id="number" type="tel" class="mt-1 block w-full" v-model="form.number"
                         required autofocus autocomplete="tel" />
            <PhoneAuthInputError :message="form.errors.number" class="mt-2"/>
        </div>

        <div class="mt-4">
            <PhoneAuthLabel for="password" value="Password" />
            <PhoneAuthInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                         required autocomplete="current-password" />
            <PhoneAuthInputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <PhoneAuthCheckbox name="remember" v-model:checked="form.remember" />
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link v-if="canResetPassword" :href="route('forgot.create')" class="underline text-sm text-gray-600
            hover:text-gray-900">
                Forgot your password?
            </Link>

            <PhoneAuthButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
            </PhoneAuthButton>
        </div>
    </form>
</template>

<script>
import PhoneAuthButton from '@/Components/Button.vue'
import PhoneAuthCheckbox from '@/Components/Checkbox.vue'
import PhoneAuthGuestLayout from '@/Layouts/Guest.vue'
import PhoneAuthInput from '@/Components/Input.vue'
import PhoneAuthInputError from '@/Components/InputError.vue'
import PhoneAuthLabel from '@/Components/Label.vue'
import PhoneAuthValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    layout: PhoneAuthGuestLayout,

    components: {
        PhoneAuthButton,
        PhoneAuthCheckbox,
        PhoneAuthInput,
        PhoneAuthLabel,
        PhoneAuthInputError,
        PhoneAuthValidationErrors,
        Head,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                number: '',
                password: '',
                remember: false
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}
</script>
