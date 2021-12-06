<template>
    <Head title="Phone Number Verification" />

    <div v-if="status" class="mb-4 font-medium text-sm text-blue-600">
        {{ status }}
    </div>

    <div class="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, could you verify your phone number by typing the token we just
        text to you? If you didn't receive the text message, we will gladly send you another.
    </div>

    <form @submit.prevent="submitResend" class="mb-4">
        <div class="mt-4 flex items-center justify-between">
            <PhoneAuthButton :class="{ 'opacity-25': resend.processing }" :disabled="resend.processing">
                RESEND VERIFICATION TOKEN
            </PhoneAuthButton>
        </div>
    </form>

    <form @submit.prevent="submitVerify">
        <div>
            <PhoneAuthLabel for="token" value="Verification Token" />
            <PhoneAuthInput id="token" type="text" class="mt-1 block w-full" v-model="verify.token"
                            required autofocus autocomplete="token" />
            <PhoneAuthInputError :message="verify.errors.token" class="mt-2"/>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <PhoneAuthButton :class="{ 'opacity-25': verify.processing }" :disabled="verify.processing">
                Submit
            </PhoneAuthButton>

            <Link :href="route('logout')" method="post" as="button"
                  class="underline text-sm text-gray-600 hover:text-gray-900">Log Out</Link>
        </div>
    </form>
</template>

<script>
    import PhoneAuthInput from '@/Components/Input.vue'
    import PhoneAuthInputError from '@/Components/InputError.vue'
    import PhoneAuthLabel from '@/Components/Label.vue'
    import PhoneAuthGuestLayout from '@/Layouts/Guest.vue'
    import PhoneAuthButton from '@/Components/Button.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        layout: PhoneAuthGuestLayout,

        components: {
            PhoneAuthInput,
            PhoneAuthInputError,
            PhoneAuthLabel,
            PhoneAuthButton,
            Head,
            Link,
        },

        props: {
            status: String,
            number : String
        },

        data() {
            return {
                verify: this.$inertia.form({
                    token : null
                }),
                resend: this.$inertia.form({
                    number : this.number
                })
            }
        },

        methods: {
            submitVerify() {
                this.verify.post(this.$page.url)
            },
            submitResend(){
                this.resend.post(this.route('resend'))
            }
        }
    }
</script>
