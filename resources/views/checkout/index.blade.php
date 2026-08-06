<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Ormin's Pasalubong Center</title>
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
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-primary/20 text-gray-800">
    <header class="border-b bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Checkout</h1>
                <p class="text-sm text-gray-600">Shopee-style review before placing your order.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('cart.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">Back to Cart</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-lg border border-primaryStrong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl bg-white p-6 shadow">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900">1. Shipping Address</h2>
                        <p class="text-sm text-gray-600">Confirm your address before payment, similar to a marketplace checkout.</p>
                    </div>

                    <form method="POST" action="{{ route('orders.checkout') }}" class="space-y-6" id="checkoutForm">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Full Name</label>
                                <input type="text" value="{{ $defaultName }}" readonly class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-700">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                                <input type="text" value="{{ $defaultEmail }}" readonly class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-700">
                            </div>
                        </div>

                        <div>
                            <label for="contact_number" class="mb-2 block text-sm font-semibold text-gray-700">Contact Number</label>
                            <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primaryStrong focus:outline-none focus:ring-2 focus:ring-primary/40">
                        </div>

                        <div>
                            <label for="delivery_address" class="mb-2 block text-sm font-semibold text-gray-700">Delivery Address</label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" placeholder="House no., street, barangay, city, province" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primaryStrong focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('delivery_address', $defaultAddress) }}</textarea>
                        </div>

                        <div>
                            <label for="order_notes" class="mb-2 block text-sm font-semibold text-gray-700">Order Note</label>
                            <textarea id="order_notes" name="order_notes" rows="2" placeholder="Optional note for the seller or rider" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-primaryStrong focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('order_notes') }}</textarea>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                            <h3 class="text-lg font-bold text-gray-900">2. Delivery Option</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="delivery-option cursor-pointer rounded-2xl border-2 {{ $defaultDeliveryMethod === 'standard' ? 'border-primaryStrong bg-primary/30 ring-2 ring-primaryStrong/30' : 'border-gray-200 bg-white' }} p-4 transition hover:border-primaryStrong" data-option-group="delivery_method" data-option-value="standard">
                                    <input type="radio" name="delivery_method" value="standard" class="sr-only" {{ $defaultDeliveryMethod === 'standard' ? 'checked' : '' }}>
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-bold text-gray-900">Standard Delivery</p>
                                            <p class="text-sm text-gray-600">3-5 business days</p>
                                        </div>
                                        <p class="font-bold text-gray-900">P 35.00</p>
                                    </div>
                                </label>
                                <label class="delivery-option cursor-pointer rounded-2xl border-2 {{ $defaultDeliveryMethod === 'express' ? 'border-primaryStrong bg-primary/30 ring-2 ring-primaryStrong/30' : 'border-gray-200 bg-white' }} p-4 transition hover:border-primaryStrong" data-option-group="delivery_method" data-option-value="express">
                                    <input type="radio" name="delivery_method" value="express" class="sr-only" {{ $defaultDeliveryMethod === 'express' ? 'checked' : '' }}>
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-bold text-gray-900">Express Delivery</p>
                                            <p class="text-sm text-gray-600">1-2 business days</p>
                                        </div>
                                        <p class="font-bold text-gray-900">P 75.00</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                            <h3 class="text-lg font-bold text-gray-900">3. Payment Method</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="payment-option cursor-pointer rounded-2xl border-2 {{ $defaultPaymentMethod === 'gcash' ? 'border-primaryStrong bg-primary/30 ring-2 ring-primaryStrong/30' : 'border-gray-200 bg-white' }} p-4 transition hover:border-primaryStrong" data-option-group="payment_method" data-option-value="gcash">
                                    <input type="radio" name="payment_method" value="gcash" class="sr-only" {{ $defaultPaymentMethod === 'gcash' ? 'checked' : '' }}>
                                    <p class="font-bold text-gray-900">GCash Online Payment</p>
                                    <p class="text-sm text-gray-600">Pay securely, then verify your transaction</p>
                                </label>
                                <label class="payment-option cursor-pointer rounded-2xl border-2 {{ $defaultPaymentMethod === 'cod' ? 'border-primaryStrong bg-primary/30 ring-2 ring-primaryStrong/30' : 'border-gray-200 bg-white' }} p-4 transition hover:border-primaryStrong" data-option-group="payment_method" data-option-value="cod">
                                    <input type="radio" name="payment_method" value="cod" class="sr-only" {{ $defaultPaymentMethod === 'cod' ? 'checked' : '' }}>
                                    <p class="font-bold text-gray-900">Cash on Delivery</p>
                                    <p class="text-sm text-gray-600">Pay when your order arrives</p>
                                </label>
                            </div>

                            <div id="gcashDetails" class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                                <p class="font-semibold">GCash steps</p>
                                <p class="mt-1">After placing the order, you will be redirected to the payment page to complete your online payment.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <aside class="h-fit rounded-2xl bg-white p-6 shadow lg:sticky lg:top-6">
                <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
                <p class="mt-1 text-sm text-gray-600">Review before placing your order.</p>

                <div class="mt-5 space-y-3 text-sm">
                    @foreach($cart as $item)
                        <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-gray-500">Qty {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">P {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">P {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span id="shippingFeeLabel" class="font-semibold">P {{ number_format($shippingFee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="flex items-center justify-between text-base">
                        <span class="font-bold text-gray-900">Total Payment</span>
                        <span id="grandTotalLabel" class="font-bold text-primaryStrong">P {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                <button form="checkoutForm" type="submit" class="mt-6 w-full rounded-2xl bg-secondary px-4 py-3 font-bold text-gray-900 hover:bg-secondaryStrong">
                    Place Order
                </button>

                <p class="mt-3 text-center text-xs text-gray-500">By placing this order, you agree to the delivery and payment terms.</p>
            </aside>
        </div>
    </main>

    <script>
        const deliveryMethodInputs = document.querySelectorAll('input[name="delivery_method"]');
        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        const deliveryOptions = document.querySelectorAll('.delivery-option');
        const paymentOptions = document.querySelectorAll('.payment-option');
        const shippingFeeLabel = document.getElementById('shippingFeeLabel');
        const grandTotalLabel = document.getElementById('grandTotalLabel');
        const subtotal = {{ (float) $subtotal }};
        const gcashDetails = document.getElementById('gcashDetails');

        const syncActiveCards = (inputs, cards) => {
            const selectedValue = Array.from(inputs).find((input) => input.checked)?.value;

            cards.forEach((card) => {
                const isActive = card.dataset.optionValue === selectedValue;
                card.classList.toggle('border-primaryStrong', isActive);
                card.classList.toggle('bg-primary/30', isActive);
                card.classList.toggle('ring-2', isActive);
                card.classList.toggle('ring-primaryStrong/30', isActive);
                card.classList.toggle('border-gray-200', !isActive);
                card.classList.toggle('bg-white', !isActive);
            });
        };

        const updateTotals = () => {
            const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked')?.value || 'standard';
            const shippingFee = selectedDelivery === 'express' ? 75 : 35;
            const grandTotal = subtotal + shippingFee;

            shippingFeeLabel.textContent = `P ${shippingFee.toFixed(2)}`;
            grandTotalLabel.textContent = `P ${grandTotal.toFixed(2)}`;
        };

        const updatePaymentHelp = () => {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value || 'gcash';
            gcashDetails.classList.toggle('hidden', selectedPayment !== 'gcash');
        };

        const updateCards = () => {
            syncActiveCards(deliveryMethodInputs, deliveryOptions);
            syncActiveCards(paymentMethodInputs, paymentOptions);
        };

        deliveryMethodInputs.forEach((input) => {
            input.addEventListener('change', () => {
                updateTotals();
                updateCards();
            });
        });

        paymentMethodInputs.forEach((input) => {
            input.addEventListener('change', () => {
                updatePaymentHelp();
                updateCards();
            });
        });

        updateTotals();
        updatePaymentHelp();
        updateCards();
    </script>
</body>
</html>