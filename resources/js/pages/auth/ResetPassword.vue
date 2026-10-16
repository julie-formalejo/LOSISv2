<script setup>
import { ref, computed, watch } from 'vue';
import { Head } from '@inertiajs/vue3';

import '../../../css/auth/reset-password.css';
import '../../../css/responsive/auth/reset-password-responsive.css';

const password = ref('');
const confirmPassword = ref('');
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const passwordError = ref('');

const submitted = ref(false);

const requirements = computed(() => ({
    length: password.value.length >= 8,
    uppercase: /[A-Z]/.test(password.value),
    lowercase: /[a-z]/.test(password.value),
    number: /[0-9]/.test(password.value),
    special: /[^A-Za-z0-9]/.test(password.value),
    match:
        confirmPassword.value.length > 0 &&
        password.value === confirmPassword.value,
}));

const passedRequirements = computed(() =>
    Object.values(requirements.value).filter(Boolean).length
);

const strength = computed(() => {
    if (!password.value) {
        return {
            text: 'Weak',
            percentage: 0,
        };
    }

    const score = [
        requirements.value.length,
        requirements.value.uppercase,
        requirements.value.lowercase,
        requirements.value.number,
        requirements.value.special,
    ].filter(Boolean).length;

    const strengthLevels = {
        0: { text: 'Weak', percentage: 10 },
        1: { text: 'Weak', percentage: 20 },
        2: { text: 'Fair', percentage: 40 },
        3: { text: 'Good', percentage: 60 },
        4: { text: 'Strong', percentage: 80 },
        5: { text: 'Very Strong', percentage: 100 },
    };

    return strengthLevels[score];
});

const isPasswordValid = computed(() =>
    requirements.value.length &&
    requirements.value.uppercase &&
    requirements.value.lowercase &&
    requirements.value.number &&
    requirements.value.special &&
    requirements.value.match
);

function togglePassword(field) {
    if (field === 'newPassword') {
        showPassword.value = !showPassword.value;
    } else {
        showConfirmPassword.value = !showConfirmPassword.value;
    }
}

function submitResetForm() {
    submitted.value = true;
    passwordError.value = '';

    if (!password.value || !confirmPassword.value) {
        passwordError.value = 'Please complete both password fields.';
        return;
    }

    if (!isPasswordValid.value) {
        passwordError.value =
            'Please meet all password requirements before continuing.';
        return;
    }

    /*
     * UI ONLY:
     * Connect this form to your Laravel password-reset endpoint
     * when the backend functionality is ready.
     */
    passwordError.value =
        'Password validation passed. Password reset is not connected yet.';
}

watch([password, confirmPassword], () => {
    if (submitted.value) {
        passwordError.value = '';
    }
});
</script>

<template>
    <Head title="Reset Password" />

    <main class="reset-page">
        <section
            class="reset-card"
            aria-labelledby="resetHeading"
        >
            <!-- HEADER -->
            <header class="reset-header">
                <a
                    class="reset-brand"
                    href="/"
                    aria-label="Return to LOSIS sign in"
                >
                    <img
                        :src="'/assets/images/lendell/LOSISv2.png'"
                        alt="LOSIS - Lendell Online Standards Integrated System"
                        width="165"
                    >
                </a>

                <h1 id="resetHeading">Reset Password</h1>

                <p>Create a new secure password to continue</p>
            </header>

            <!-- RESET PASSWORD FORM -->
            <form
                method="POST"
                id="resetForm"
                autocomplete="on"
                novalidate
                @submit.prevent="submitResetForm"
            >
                <!-- NEW PASSWORD -->
                <div class="field reset-field">
                    <label for="newPassword">New Password</label>

                    <div class="reset-input-shell">
                        <i
                            class="fa-solid fa-lock icon-left"
                            aria-hidden="true"
                        ></i>

                        <input
                            v-model="password"
                            :type="showPassword ? 'text' : 'password'"
                            id="newPassword"
                            name="password"
                            placeholder="New password"
                            autocomplete="new-password"
                            required
                            aria-describedby="passwordStrengthLabel passwordError"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            :aria-label="
                                showPassword
                                    ? 'Hide new password'
                                    : 'Show new password'
                            "
                            aria-controls="newPassword"
                            @click="togglePassword('newPassword')"
                        >
                            <i
                                :class="
                                    showPassword
                                        ? 'fa-solid fa-eye-slash'
                                        : 'fa-solid fa-eye'
                                "
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- PASSWORD STRENGTH -->
                <section
                    class="strength-wrapper"
                    aria-labelledby="passwordStrengthLabel"
                >
                    <div
                        class="strength-bar"
                        role="progressbar"
                        aria-label="Password strength"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        :aria-valuenow="strength.percentage"
                    >
                        <span
                            id="strengthFill"
                            :style="{ width: `${strength.percentage}%` }"
                        ></span>
                    </div>

                    <div class="strength-meta">
                        <span id="passwordStrengthLabel">
                            Password strength
                        </span>

                        <strong id="strengthText">
                            {{ strength.text }}
                        </strong>
                    </div>
                </section>

                <!-- PASSWORD REQUIREMENTS -->
                <section
                    class="password-requirements"
                    aria-label="Password requirements"
                >
                    <p class="requirements-heading">
                        Your password must contain:
                    </p>

                    <div
                        class="password-rule"
                        :class="requirements.length ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.length
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>Minimum of 8 characters</span>
                    </div>

                    <div
                        class="password-rule"
                        :class="requirements.uppercase ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.uppercase
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>At least one uppercase letter</span>
                    </div>

                    <div
                        class="password-rule"
                        :class="requirements.lowercase ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.lowercase
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>At least one lowercase letter</span>
                    </div>

                    <div
                        class="password-rule"
                        :class="requirements.number ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.number
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>At least one number</span>
                    </div>

                    <div
                        class="password-rule"
                        :class="requirements.special ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.special
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>At least one special character</span>
                    </div>

                    <div
                        class="password-rule"
                        :class="requirements.match ? 'valid' : 'invalid'"
                    >
                        <i
                            class="fa-solid"
                            :class="
                                requirements.match
                                    ? 'fa-circle-check'
                                    : 'fa-circle'
                            "
                            aria-hidden="true"
                        ></i>
                        <span>Passwords must match</span>
                    </div>
                </section>

                <!-- CONFIRM PASSWORD -->
                <div class="field reset-field">
                    <label for="confirmPassword">
                        Confirm Password
                    </label>

                    <div class="reset-input-shell">
                        <i
                            class="fa-solid fa-lock icon-left"
                            aria-hidden="true"
                        ></i>

                        <input
                            v-model="confirmPassword"
                            :type="
                                showConfirmPassword ? 'text' : 'password'
                            "
                            id="confirmPassword"
                            name="confirm_password"
                            placeholder="Confirm password"
                            autocomplete="new-password"
                            required
                            aria-describedby="ruleMatch passwordError"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            :aria-label="
                                showConfirmPassword
                                    ? 'Hide confirm password'
                                    : 'Show confirm password'
                            "
                            aria-controls="confirmPassword"
                            @click="togglePassword('confirmPassword')"
                        >
                            <i
                                :class="
                                    showConfirmPassword
                                        ? 'fa-solid fa-eye-slash'
                                        : 'fa-solid fa-eye'
                                "
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- VALIDATION ERROR -->
                <div
                    id="passwordError"
                    class="error-text"
                    role="alert"
                    aria-live="polite"
                >
                    {{ passwordError }}
                </div>

                <!-- SUBMIT BUTTON -->
                <button
                    type="submit"
                    class="btn btn-success reset-submit-btn"
                    id="resetBtn"
                >
                    <span class="btn-text">Update Password</span>

                    <i
                        class="fa-solid fa-arrow-right"
                        aria-hidden="true"
                    ></i>
                </button>

                <!-- BACK TO LOGIN -->
                <div class="bottom-text">
                    <a href="/">
                        <i
                            class="fa-solid fa-arrow-left"
                            aria-hidden="true"
                        ></i>
                        <span>Back to Login</span>
                    </a>
                </div>
            </form>

            <!-- SECURITY REMINDER -->
            <div class="reset-security-note">
                <i
                    class="fa-solid fa-shield-halved"
                    aria-hidden="true"
                ></i>

                <p>
                    Keep your new password private and never share it
                    with anyone.
                </p>
            </div>
        </section>
    </main>
</template>
