<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/2fa-verification.css';
import '../../../css/responsive/auth/2fa-verification-responsive.css';

const props = defineProps({
    maskedEmail: {
        type: String,
        default: '',
    },
});

const otpDigits = ref(['', '', '', '', '', '']);
const otpInputs = ref([]);
const secondsRemaining = ref(120);
const errorMessage = ref('');
const isVerifying = ref(false);

let timerInterval = null;

const otpCode = computed(() => otpDigits.value.join(''));

const formattedTime = computed(() => {
    const minutes = Math.floor(secondsRemaining.value / 60);
    const seconds = secondsRemaining.value % 60;

    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

function focusInput(index) {
    if (index >= 0 && index < otpInputs.value.length) {
        otpInputs.value[index]?.focus();
    }
}

function handleInput(index, event) {
    const value = event.target.value.replace(/\D/g, '');
    otpDigits.value[index] = value.slice(-1);
    errorMessage.value = '';

    if (otpDigits.value[index] && index < 5) {
        focusInput(index + 1);
    }
}

function handleKeydown(index, event) {
    if (event.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
        focusInput(index - 1);
    }

    if (event.key === 'ArrowLeft' && index > 0) {
        focusInput(index - 1);
    }

    if (event.key === 'ArrowRight' && index < 5) {
        focusInput(index + 1);
    }
}

function handlePaste(event) {
    event.preventDefault();

    const pastedText = event.clipboardData
        .getData('text')
        .replace(/\D/g, '')
        .slice(0, 6);

    if (!pastedText) return;

    otpDigits.value = Array.from(
        { length: 6 },
        (_, index) => pastedText[index] || ''
    );

    errorMessage.value = '';
    focusInput(Math.min(pastedText.length, 5));
}

function startTimer() {
    clearInterval(timerInterval);

    secondsRemaining.value = 120;

    timerInterval = setInterval(() => {
        if (secondsRemaining.value > 0) {
            secondsRemaining.value--;
        } else {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }, 1000);
}

function verifyCode() {
    errorMessage.value = '';

    if (secondsRemaining.value === 0) {
        errorMessage.value =
            'Your verification code has expired. Please request a new code.';
        return;
    }

    if (otpCode.value.length !== 6) {
        errorMessage.value = 'Please enter all 6 digits of your verification code.';
        focusInput(otpDigits.value.findIndex(digit => !digit));
        return;
    }

    /*
     * UI ONLY:
     * Connect this function to your Laravel OTP verification endpoint.
     * Do not treat a six-digit code as verified until the server validates it.
     */
    isVerifying.value = true;

    errorMessage.value =
        'The verification service is not connected yet. Please try again when backend verification is available.';

    isVerifying.value = false;
}

function resendCode() {
    /*
     * UI ONLY:
     * Connect this function to a Laravel endpoint that generates
     * and emails a new OTP before enabling actual resend functionality.
     */
    otpDigits.value = ['', '', '', '', '', ''];
    errorMessage.value =
        'Resend is not connected yet. No new verification code has been sent.';

    focusInput(0);
}

onMounted(() => {
    startTimer();
});

onBeforeUnmount(() => {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
});
</script>

<template>
    <Head title="Verify Your Identity" />

    <main class="otp-page">
        <section class="otp-card" aria-labelledby="otpHeading">
            <!-- HEADER -->
            <header class="otp-header">
                <img
                    :src="'/assets/images/lendell/LOSISv2.png'"
                    alt="LOSIS"
                    class="otp-logo"
                >

                <div class="otp-icon" aria-hidden="true">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <h1 id="otpHeading">Verify Your Identity</h1>

                <p>
                    Enter the verification code sent to your
                    registered email address.
                </p>
            </header>

            <!-- VERIFICATION METHOD -->
            <section
                class="otp-method-section"
                aria-label="Verification method"
            >
                <div class="otp-methods">
                    <button
                        type="button"
                        class="method-btn active"
                        id="emailMethod"
                        data-method="email"
                        aria-pressed="true"
                    >
                        <i
                            class="fa-solid fa-envelope"
                            aria-hidden="true"
                        ></i>
                        <span>Email</span>
                    </button>
                </div>
            </section>

            <!-- OTP CONFIGURATION -->
            <input
                type="hidden"
                id="otpMethod"
                value="email"
            >

            <input
                type="hidden"
                id="maskedEmail"
                :value="maskedEmail"
            >

            <!-- DESTINATION INFORMATION -->
            <div
                class="otp-info"
                id="otpInfo"
                role="status"
                aria-live="polite"
            >
                <div class="otp-info-icon" aria-hidden="true">
                    <i class="fa-solid fa-envelope"></i>
                </div>

                <div class="otp-info-content">
                    <span class="otp-info-label">
                        Verification code sent to
                    </span>

                    <strong id="otpDestination">
                        {{ maskedEmail || 'Your registered email address' }}
                    </strong>
                </div>
            </div>

            <!-- OTP INPUTS -->
            <div class="otp-code-section">
                <label class="otp-code-label" id="otpCodeLabel">
                    Enter your 6-digit code
                </label>

                <div
                    class="otp-input-group"
                    role="group"
                    aria-labelledby="otpCodeLabel"
                >
                    <input
                        v-for="(_, index) in otpDigits"
                        :key="index"
                        :ref="el => otpInputs[index] = el"
                        v-model="otpDigits[index]"
                        type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric"
                        :autocomplete="index === 0 ? 'one-time-code' : 'off'"
                        :aria-label="`Digit ${index + 1}`"
                        @input="handleInput(index, $event)"
                        @keydown="handleKeydown(index, $event)"
                        @paste="handlePaste"
                    >
                </div>
            </div>

            <!-- OTP EXPIRATION TIMER -->
            <div class="otp-timer" id="otpTimerWrapper">
                <i class="fa-regular fa-clock" aria-hidden="true"></i>

                <span id="otpTimer">
                    {{ secondsRemaining > 0
                        ? `Expires in ${formattedTime}`
                        : 'Verification code expired'
                    }}
                </span>
            </div>

            <!-- VALIDATION MESSAGE -->
            <div
                v-if="errorMessage"
                class="error-text"
                role="alert"
                aria-live="polite"
            >
                {{ errorMessage }}
            </div>

            <!-- VERIFY BUTTON -->
            <button
                type="button"
                class="btn w-100"
                id="verifyBtn"
                :disabled="isVerifying || secondsRemaining === 0"
                @click="verifyCode"
            >
                <i
                    class="fa-solid fa-shield-halved"
                    aria-hidden="true"
                ></i>

                <span>
                    {{ isVerifying ? 'Verifying...' : 'Verify Code' }}
                </span>
            </button>

            <!-- RESEND CODE -->
            <div class="resend-text">
                <span>Didn't receive the code?</span>

                <a
                    href="#"
                    id="resendOtp"
                    @click.prevent="resendCode"
                >
                    Resend code
                </a>
            </div>

            <!-- SECURITY FOOTER -->
            <footer class="otp-security">
                <i
                    class="fa-solid fa-lock"
                    aria-hidden="true"
                ></i>

                <span>
                    Your verification code is private.
                    Never share it with anyone.
                </span>
            </footer>
        </section>
    </main>
</template>
