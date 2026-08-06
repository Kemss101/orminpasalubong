<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Ormin's Pasalubong Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CDEFD3',
                        secondary: '#FFE169',
                        primaryStrong: '#85C88A',
                        secondaryStrong: '#F5C542',
                        deep: '#1F2937',
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-b from-secondary via-primary to-white text-gray-800">
    <div class="mx-auto flex min-h-screen w-full max-w-2xl items-center justify-center px-4 py-10">
        <div class="grid w-full overflow-hidden rounded-3xl border border-secondary bg-white shadow-2xl lg:grid-cols-1">

            <main class="p-6 sm:p-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primaryStrong hover:text-secondaryStrong">&larr; Back to Home</a>
                <h2 class="mt-6 text-3xl font-extrabold text-deep">Register</h2>
                <p class="mt-1 text-sm text-gray-500">Fill out the form to create your account.</p>

                @if (isset($errors) && $errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/register') }}" method="POST" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Full Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                            placeholder="Your full name"
                        >
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="user_type" class="mb-1 block text-sm font-semibold text-gray-700">Account Role</label>
                        <select
                            id="user_type"
                            name="user_type"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                        >
                            <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>Select a role</option>
                            <option value="seller" {{ old('user_type') === 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="customer" {{ old('user_type') === 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>

                    <div id="registerAddressGroup" class="{{ old('user_type') === 'customer' ? '' : 'hidden' }}">
                        <label for="address" class="mb-1 block text-sm font-semibold text-gray-700">Address <span class="text-red-500">*</span></label>
                        <input
                            id="address"
                            name="address"
                            type="text"
                            value="{{ old('address') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                            placeholder="Enter your full address"
                        >
                        <p class="mt-1 text-xs text-gray-500">Required for customer accounts.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-1 block text-sm font-semibold text-gray-700">Password</label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-12 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                                    placeholder="At least 8 characters"
                                >
                                <button id="toggleRegisterPassword" type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-gray-500 hover:text-gray-700" aria-label="Show password">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.27 2.943 9.542 7-1.272 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700">Confirm Password</label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-12 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                                    placeholder="Retype password"
                                >
                                <button id="toggleRegisterPasswordConfirmation" type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-gray-500 hover:text-gray-700" aria-label="Show confirm password">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.27 2.943 9.542 7-1.272 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p id="registerPasswordMatch" class="hidden text-xs font-semibold"></p>

                    <button
                        type="submit"
                        class="mt-2 w-full rounded-xl bg-primary px-4 py-3 font-bold text-deep transition hover:bg-primaryStrong"
                    >
                        Create Account
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-primaryStrong hover:text-secondaryStrong">Login here</a>
                </p>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupPasswordToggle(inputId, buttonId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);

                if (!input || !button) {
                    return;
                }

                button.addEventListener('click', function () {
                    const shouldShow = input.type === 'password';
                    input.type = shouldShow ? 'text' : 'password';
                    button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                });
            }

            function setupCustomerAddressField(selectId, groupId, inputId) {
                const roleSelect = document.getElementById(selectId);
                const addressGroup = document.getElementById(groupId);
                const addressInput = document.getElementById(inputId);

                if (!roleSelect || !addressGroup || !addressInput) {
                    return;
                }

                const syncAddressField = function () {
                    const isCustomer = roleSelect.value === 'customer';
                    addressGroup.classList.toggle('hidden', !isCustomer);
                    addressInput.required = isCustomer;

                    if (!isCustomer) {
                        addressInput.value = '';
                    }
                };

                roleSelect.addEventListener('change', syncAddressField);
                syncAddressField();
            }

            function setupPasswordMatch(passwordId, confirmId, messageId) {
                const passwordField = document.getElementById(passwordId);
                const confirmField = document.getElementById(confirmId);
                const message = document.getElementById(messageId);

                if (!passwordField || !confirmField || !message) {
                    return;
                }

                const updateMessage = function () {
                    if (!confirmField.value) {
                        message.classList.add('hidden');
                        message.classList.remove('text-red-600', 'text-emerald-600');
                        confirmField.setCustomValidity('');
                        return;
                    }

                    message.classList.remove('hidden');

                    if (passwordField.value === confirmField.value) {
                        message.textContent = 'Passwords match.';
                        message.classList.remove('text-red-600');
                        message.classList.add('text-emerald-600');
                        confirmField.setCustomValidity('');
                        return;
                    }

                    message.textContent = 'Passwords do not match.';
                    message.classList.remove('text-emerald-600');
                    message.classList.add('text-red-600');
                    confirmField.setCustomValidity('Passwords do not match.');
                };

                passwordField.addEventListener('input', updateMessage);
                confirmField.addEventListener('input', updateMessage);
                updateMessage();
            }

            setupPasswordToggle('password', 'toggleRegisterPassword');
            setupPasswordToggle('password_confirmation', 'toggleRegisterPasswordConfirmation');
            setupPasswordMatch('password', 'password_confirmation', 'registerPasswordMatch');
            setupCustomerAddressField('user_type', 'registerAddressGroup', 'address');
        });
    </script>
</body>
</html>
