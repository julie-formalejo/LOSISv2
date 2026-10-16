<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/security-pin.css';
import '../../../css/responsive/auth/security-pin-responsive.css';

const props = defineProps({
    hasSecurityPin: {
        type: Boolean,
        default: false,
    },
    userEmail: {
        type: String,
        default: '',
    },
});

const existingPin = ref(['', '', '', '', '', '']);
const createPin = ref(['', '', '', '', '', '']);
const confirmPin = ref(['', '', '', '', '', '']);

const existingPinRefs = ref([]);
const createPinRefs = ref([]);
const confirmPinRefs = ref([]);

const showExistingPin = ref(false);
const showCreatePin = ref(false);
const showConfirmPin = ref(false);

const errorMessage = ref('');
const isSubmitting = ref(false);

const combinedExistingPin = computed(() => existingPin.value.join(''));
const combinedCreatePin = computed(() => createPin.value.join(''));
const combinedConfirmPin = computed(() => confirmPin.value.join(''));

function handlePinInput(pinArray, refsArray, index, event) {
    const value = event.target.value.replace(/\D/g, '');
    pinArray[index] = value.slice(-1);
    errorMessage.value = '';

    if (pinArray[index] && index < 5) {
        refsArray[index + 1]?.focus();
    }
}

function handlePinKeydown(pinArray, refsArray, index, event) {
    if (
        event.key === 'Backspace' &&
        !pinArray[index] &&
        index > 0
    ) {
        refsArray[index - 1]?.focus();
    }

    if (event.key === 'ArrowLeft' && index > 0) {
        refsArray[index - 1]?.focus();
    }

    if (event.key === 'ArrowRight' && index < 5) {
        refsArray[index + 1]?.focus();
    }
}

function handlePinPaste(pinArray, refsArray, event) {
    event.preventDefault();

    const pastedValue = event.clipboardData
        .getData('text')
        .replace(/\D/g, '')
        .slice(0, 6);

    if (!pastedValue) return;

    pinArray.splice(
        0,
        6,
        ...Array.from(
            { length: 6 },
            (_, index) => pastedValue[index] || ''
        )
    );

    errorMessage.value = '';

    refsArray[Math.min(pastedValue.length, 5)]?.focus();
}

function validateSixDigitPin(pin) {
    return /^\d{6}$/.test(pin);
}

function verifyExistingPin() {
    errorMessage.value = '';

    if (!validateSixDigitPin(combinedExistingPin.value)) {
        errorMessage.value = 'Please enter your complete 6-digit Security PIN.';
        return;
    }

    /*
     * UI ONLY:
     * Connect to Laravel to verify the existing PIN securely.
     */
    errorMessage.value =
        'PIN verification is not connected yet. No account verification was performed.';
}

function createSecurityPin() {
    errorMessage.value = '';

    if (!validateSixDigitPin(combinedCreatePin.value)) {
        errorMessage.value = 'Please enter a complete 6-digit PIN.';
        return;
    }

    if (!validateSixDigitPin(combinedConfirmPin.value)) {
        errorMessage.value = 'Please confirm your complete 6-digit PIN.';
        return;
    }

    if (combinedCreatePin.value !== combinedConfirmPin.value) {
        errorMessage.value = 'Your PINs do not match. Please try again.';
        return;
    }

    if (/^(\d)\1{5}$/.test(combinedCreatePin.value)) {
        errorMessage.value =
            'Please avoid using the same digit six times.';
        return;
    }

    /*
     * UI ONLY:
     * Connect to Laravel to securely store the PIN.
     * Never store the PIN in plain text.
     */
    errorMessage.value =
        'PIN validation passed, but saving the Security PIN is not connected yet.';
}

function submitPinForm() {
    if (isSubmitting.value) return;

    isSubmitting.value = true;

    try {
        if (props.hasSecurityPin) {
            verifyExistingPin();
        } else {
            createSecurityPin();
        }
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Head title="Security PIN" />

    <main class="security-page">
        <section
            class="security-pin-card"
            aria-labelledby="securityHeading"
        >
            <!-- HEADER -->
            <header class="security-header">
                <img
                    :src="'/assets/images/lendell/LOSISv2.png'"
                    alt="LOSIS"
                    class="security-logo"
                >

                <div class="security-icon" aria-hidden="true">
                    <i
                        :class="
                            hasSecurityPin
                                ? 'fa-solid fa-shield-halved'
                                : 'fa-solid fa-key'
                        "
                    ></i>
                </div>

                <!-- EXISTING PIN HEADER -->
                <template v-if="hasSecurityPin">
                    <div class="security-badge">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <span>Additional Security Check</span>
                    </div>

                    <h1 id="securityHeading">
                        Enter Your Security PIN
                    </h1>

                    <p>
                        Enter your 6-digit Security PIN to
                        securely continue to your LOSIS account.
                    </p>
                </template>

                <!-- FIRST-TIME SETUP HEADER -->
                <template v-else>
                    <div class="security-badge setup">
                        <i
                            class="fa-solid fa-shield-halved"
                            aria-hidden="true"
                        ></i>
                        <span>Account Protection</span>
                    </div>

                    <h1 id="securityHeading">
                        Create Your Security PIN
                    </h1>

                    <p>
                        Add an extra layer of protection by
                        creating a personal 6-digit Security PIN.
                    </p>
                </template>
            </header>

            <!-- SIGNED-IN USER INFORMATION -->
            <section
                class="security-user-info"
                aria-label="Signed-in account"
            >
                <div class="security-user-icon" aria-hidden="true">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="security-user-details">
                    <span>Signed in as</span>

                    <strong id="securityUserEmail">
                        {{ userEmail || 'Registered account' }}
                    </strong>
                </div>

                <i
                    class="fa-solid fa-circle-check security-account-status"
                    aria-hidden="true"
                ></i>
            </section>

            <!-- EXISTING PIN VERIFICATION -->
            <form
                v-if="hasSecurityPin"
                id="securityPinForm"
                autocomplete="off"
                @submit.prevent="submitPinForm"
            >
                <div class="pin-section">
                    <div class="pin-section-header">
                        <label
                            class="pin-label"
                            id="securityPinLabel"
                        >
                            Security PIN
                        </label>

                        <span class="pin-required">6 digits</span>
                    </div>

                    <div class="security-pin-input-wrapper">
                        <div
                            class="security-pin-group"
                            id="securityPinGroup"
                            role="group"
                            aria-labelledby="securityPinLabel"
                        >
                            <input
                                v-for="(_, index) in existingPin"
                                :key="index"
                                :ref="el => existingPinRefs[index] = el"
                                v-model="existingPin[index]"
                                :type="showExistingPin ? 'text' : 'password'"
                                class="pin-input"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                :autocomplete="index === 0 ? 'one-time-code' : 'off'"
                                :aria-label="`Security PIN digit ${index + 1}`"
                                required
                                @input="handlePinInput(existingPin, existingPinRefs, index, $event)"
                                @keydown="handlePinKeydown(existingPin, existingPinRefs, index, $event)"
                                @paste="handlePinPaste(existingPin, existingPinRefs, $event)"
                            >
                        </div>

                        <button
                            type="button"
                            class="pin-visibility-btn"
                            id="toggleSecurityPin"
                            :aria-label="showExistingPin ? 'Hide Security PIN' : 'Show Security PIN'"
                            :aria-pressed="showExistingPin"
                            :title="showExistingPin ? 'Hide Security PIN' : 'Show Security PIN'"
                            @click="showExistingPin = !showExistingPin"
                        >
                            <i
                                :class="showExistingPin ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </div>

                <input
                    type="hidden"
                    id="securityPin"
                    name="security_pin"
                    :value="combinedExistingPin"
                >

                <p
                    v-if="errorMessage"
                    class="security-error"
                    role="alert"
                    aria-live="polite"
                >
                    {{ errorMessage }}
                </p>

                <button
                    type="submit"
                    class="security-btn"
                    id="verifySecurityPinBtn"
                    :disabled="isSubmitting"
                >
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    <span>Verify &amp; Continue</span>
                </button>
            </form>

            <!-- EXISTING PIN REMINDER -->
            <div v-if="hasSecurityPin" class="security-help-text">
                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                <span>
                    Enter the Security PIN you previously
                    created to continue.
                </span>
            </div>

            <!-- FIRST-TIME SECURITY PIN SETUP -->
            <form
                v-else
                id="setupSecurityPinForm"
                autocomplete="off"
                @submit.prevent="submitPinForm"
            >
                <!-- CREATE PIN -->
                <div class="pin-section">
                    <div class="pin-section-header">
                        <label class="pin-label" id="createPinLabel">
                            Create PIN
                        </label>

                        <span class="pin-required">6 digits</span>
                    </div>

                    <div class="security-pin-input-wrapper">
                        <div
                            class="security-pin-group"
                            id="createPinGroup"
                            role="group"
                            aria-labelledby="createPinLabel"
                        >
                            <input
                                v-for="(_, index) in createPin"
                                :key="index"
                                :ref="el => createPinRefs[index] = el"
                                v-model="createPin[index]"
                                :type="showCreatePin ? 'text' : 'password'"
                                class="setup-pin-input"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="off"
                                :aria-label="`Create PIN digit ${index + 1}`"
                                required
                                @input="handlePinInput(createPin, createPinRefs, index, $event)"
                                @keydown="handlePinKeydown(createPin, createPinRefs, index, $event)"
                                @paste="handlePinPaste(createPin, createPinRefs, $event)"
                            >
                        </div>

                        <button
                            type="button"
                            class="pin-visibility-btn"
                            id="toggleCreatePin"
                            :aria-label="showCreatePin ? 'Hide Create PIN' : 'Show Create PIN'"
                            :aria-pressed="showCreatePin"
                            :title="showCreatePin ? 'Hide Create PIN' : 'Show Create PIN'"
                            @click="showCreatePin = !showCreatePin"
                        >
                            <i
                                :class="showCreatePin ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- CONFIRM PIN -->
                <div class="pin-section confirm-pin-section">
                    <div class="pin-section-header">
                        <label class="pin-label" id="confirmPinLabel">
                            Confirm PIN
                        </label>

                        <span class="pin-required">Re-enter PIN</span>
                    </div>

                    <div class="security-pin-input-wrapper">
                        <div
                            class="security-pin-group"
                            id="confirmPinGroup"
                            role="group"
                            aria-labelledby="confirmPinLabel"
                        >
                            <input
                                v-for="(_, index) in confirmPin"
                                :key="index"
                                :ref="el => confirmPinRefs[index] = el"
                                v-model="confirmPin[index]"
                                :type="showConfirmPin ? 'text' : 'password'"
                                class="confirm-pin-input"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="off"
                                :aria-label="`Confirm PIN digit ${index + 1}`"
                                required
                                @input="handlePinInput(confirmPin, confirmPinRefs, index, $event)"
                                @keydown="handlePinKeydown(confirmPin, confirmPinRefs, index, $event)"
                                @paste="handlePinPaste(confirmPin, confirmPinRefs, $event)"
                            >
                        </div>

                        <button
                            type="button"
                            class="pin-visibility-btn"
                            id="toggleConfirmPin"
                            :aria-label="showConfirmPin ? 'Hide Confirm PIN' : 'Show Confirm PIN'"
                            :aria-pressed="showConfirmPin"
                            :title="showConfirmPin ? 'Hide Confirm PIN' : 'Show Confirm PIN'"
                            @click="showConfirmPin = !showConfirmPin"
                        >
                            <i
                                :class="showConfirmPin ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </div>

                <p
                    v-if="errorMessage"
                    class="security-error"
                    role="alert"
                    aria-live="polite"
                >
                    {{ errorMessage }}
                </p>

                <!-- SAVE SECURITY PIN -->
                <button
                    type="submit"
                    class="security-btn"
                    id="saveSecurityPinBtn"
                    :disabled="isSubmitting"
                >
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    <span>Create &amp; Continue</span>
                </button>
            </form>

            <!-- PIN CREATION REMINDER -->
            <div v-if="!hasSecurityPin" class="security-help-text">
                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                <span>
                    Avoid easily guessed PINs such as
                    123456 or your birth year.
                </span>
            </div>

            <!-- SECURITY FOOTER -->
            <footer class="security-footer-note">
                <i class="fa-solid fa-lock" aria-hidden="true"></i>

                <span>
                    Your Security PIN is protected by secure
                    encryption and should never be shared.
                </span>
            </footer>
        </section>
    </main>
</template>
