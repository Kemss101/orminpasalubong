@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/30 via-white to-secondary/25 text-gray-800">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-white/70 bg-white/80 px-6 py-4 shadow-sm backdrop-blur">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-secondary-strong">Ormin's Pasalubong Center</p>
                <h1 class="text-2xl font-extrabold text-gray-900">GCash Payment</h1>
                <p class="mt-1 text-sm text-gray-600">Complete your payment, then verify it to confirm your order.</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                <span class="rounded-full bg-primary px-3 py-1 text-primary-strong">1. Review</span>
                <span class="rounded-full bg-secondary px-3 py-1 text-secondary-strong">2. Pay</span>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-700">3. Verify</span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="space-y-6 lg:col-span-2">
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-xl">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-primary/35 to-secondary/25 px-6 py-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Order ID #{{ $order->id }}</p>
                                <p class="text-xs text-gray-500">Payment status: {{ ucfirst($order->payment_status) }}</p>
                            </div>
                            <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-primary-strong shadow-sm">
                                ₱{{ number_format($order->total_amount + ($order->shipping_fee ?? 0), 2) }} total due
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="space-y-5">
                            @if($order->payment_status === 'completed')
                                <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                                    <p class="font-bold">✓ Payment already completed</p>
                                    <p class="text-sm">You can return to your dashboard to track your order.</p>
                                </div>
                            @else
                                <form id="paymentForm" class="space-y-5" data-auto-open="{{ isset($gcashUrl) && $gcashUrl ? '1' : '0' }}">
                                    @csrf

                                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                        <div class="mb-4 flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-bold uppercase tracking-wide text-primary-strong">Step 1</p>
                                                <h2 class="text-lg font-bold text-gray-900">Enter your GCash details</h2>
                                            </div>
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-gray-600 shadow-sm">Secure payment</span>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <label for="gcash_number" class="mb-2 block text-sm font-semibold text-gray-700">GCash Mobile Number</label>
                                                <input type="tel" id="gcash_number" name="gcash_number" required placeholder="09XXXXXXXXX" pattern="[0-9]{11}" maxlength="11" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-primaryStrong focus:outline-none focus:ring-2 focus:ring-primary/40">
                                                <p class="mt-1 text-xs text-gray-500">11-digit GCash number (09XXXXXXXXX)</p>
                                            </div>

                                            <div>
                                                <label for="amount" class="mb-2 block text-sm font-semibold text-gray-700">Amount (PHP)</label>
                                                <input type="number" id="amount" name="amount" required readonly value="{{ $order->total_amount + ($order->shipping_fee ?? 0) }}" step="0.01" class="w-full rounded-2xl border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-700 shadow-sm">
                                                <p class="mt-1 text-xs text-gray-500">Includes order amount and shipping fee.</p>
                                            </div>
                                        </div>

                                        <button type="button" id="continueGcashBtn" class="mt-5 w-full rounded-2xl bg-secondary px-4 py-3 font-bold text-gray-900 shadow-sm transition hover:bg-secondaryStrong">
                                            Continue to GCash Payment
                                        </button>
                                        <button type="button" id="openGcashBtnStep1" class="mt-3 w-full rounded-2xl border border-primaryStrong/40 bg-white px-4 py-3 text-sm font-bold text-primaryStrong shadow-sm transition hover:bg-primary/10">
                                            Open GCash App
                                        </button>
                                    </div>
                                </form>

                                <div id="handoffCard" class="hidden rounded-2xl border border-secondary-strong/30 bg-secondary/20 p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold uppercase tracking-wide text-secondary-strong">GCash Step</p>
                                            <h2 class="text-lg font-bold text-gray-900">Open your GCash app now</h2>
                                            <p class="mt-1 text-sm text-gray-600">Send the exact amount, then return here to verify your payment.</p>
                                        </div>
                                        <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-gray-700 shadow-sm">Pending payment</span>
                                    </div>

                                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Reference from GCash</p>
                                            <p class="mt-2 text-sm text-gray-600">Get this from your GCash receipt after payment.</p>
                                        </div>
                                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Amount to Send</p>
                                            <p id="gcashAmountLabel" class="mt-2 text-lg font-extrabold text-primaryStrong">-</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 rounded-2xl bg-white p-4 shadow-sm">
                                        <p class="text-sm font-semibold text-gray-900">Next:</p>
                                        <ol class="mt-2 space-y-1 text-sm text-gray-600 list-decimal pl-5">
                                            <li>Send payment in GCash using the exact amount above.</li>
                                            <li>Wait for the transaction to complete.</li>
                                            <li>Enter the receipt/reference number from your GCash app in the verification form.</li>
                                        </ol>
                                        <button type="button" id="openGcashBtn" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-primaryStrong px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                                            Open GCash App
                                        </button>
                                        <p class="mt-2 text-xs text-gray-500">If the app does not open automatically, click the button above.</p>
                                    </div>
                                </div>

                                <div id="verificationForm" class="hidden space-y-4 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                                    <div>
                                        <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Step 2</p>
                                        <h2 class="text-lg font-bold text-gray-900">Verify your payment</h2>
                                        <p class="mt-1 text-sm text-gray-600">After paying in GCash, enter the receipt/reference number below.</p>
                                    </div>

                                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                                        <p class="text-sm font-bold text-gray-900">Payment Status: Pending</p>
                                        <p class="mt-1 text-xs text-gray-500">Please complete the payment in your GCash app and confirm here.</p>
                                    </div>

                                    <div>
                                        <label for="gcash_receipt_number" class="mb-2 block text-sm font-semibold text-gray-700">GCash Receipt Number</label>
                                        <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                                            <input type="text" id="gcash_receipt_number" name="gcash_receipt_number" required placeholder="GCash Reference Number" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-primaryStrong focus:outline-none focus:ring-2 focus:ring-primary/40">
                                            <button type="button" id="verifyBtn" class="rounded-2xl bg-primaryStrong px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">Submit Reference</button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Copy the reference number from your GCash app, then submit it here.</p>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="button" id="cancelBtn" class="rounded-2xl bg-gray-200 px-4 py-3 font-bold text-gray-800 transition hover:bg-gray-300">Cancel</button>
                                    </div>
                                </div>

                                <div id="successMessage" class="hidden rounded-2xl border border-green-200 bg-green-50 p-5 text-green-800">
                                    <p class="font-bold">✓ Payment Verified Successfully!</p>
                                    <p class="mt-1 text-sm">Redirecting to your dashboard...</p>
                                </div>
                            @endif
                        </div>

                        <aside class="space-y-4 rounded-3xl bg-gray-50 p-5">
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary-strong">Order Summary</p>
                                <div class="mt-3 space-y-2 text-sm">
                                    <div class="flex items-center justify-between"><span class="text-gray-600">Subtotal</span><span class="font-semibold">₱{{ number_format($order->total_amount, 2) }}</span></div>
                                    <div class="flex items-center justify-between"><span class="text-gray-600">Shipping</span><span class="font-semibold">₱{{ number_format($order->shipping_fee ?? 0, 2) }}</span></div>
                                    <hr class="my-2 border-gray-200">
                                    <div class="flex items-center justify-between text-base"><span class="font-bold text-gray-900">Total Due</span><span class="font-bold text-primaryStrong">₱{{ number_format($order->total_amount + ($order->shipping_fee ?? 0), 2) }}</span></div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <p class="text-sm font-bold text-gray-900">Items in Order</p>
                                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                                    @foreach($order->items as $item)
                                        <li class="flex items-start justify-between gap-3 border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                                            <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                                            <span class="font-semibold text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                <p class="text-sm font-bold text-amber-900">Payment Tips</p>
                                <ul class="mt-2 space-y-1 text-xs text-amber-900/80">
                                    <li>• Send the exact amount shown above</li>
                                    <li>• Save the GCash receipt/reference number</li>
                                    <li>• Payment is verified before delivery starts</li>
                                    <li>• Keep your mobile number active for contact</li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <aside class="h-fit rounded-3xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur lg:sticky lg:top-6">
                <div class="rounded-2xl bg-gradient-to-br from-primary/40 to-secondary/25 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-700">Payment Flow</p>
                    <div class="mt-3 space-y-3 text-sm">
                        <div class="flex items-center gap-3"><span class="grid h-8 w-8 place-items-center rounded-full bg-white font-bold text-primaryStrong shadow-sm">1</span><span>Review your order total</span></div>
                        <div class="flex items-center gap-3"><span class="grid h-8 w-8 place-items-center rounded-full bg-white font-bold text-primaryStrong shadow-sm">2</span><span>Send payment through GCash</span></div>
                        <div class="flex items-center gap-3"><span class="grid h-8 w-8 place-items-center rounded-full bg-white font-bold text-primaryStrong shadow-sm">3</span><span>Enter the receipt number</span></div>
                        <div class="flex items-center gap-3"><span class="grid h-8 w-8 place-items-center rounded-full bg-white font-bold text-primaryStrong shadow-sm">4</span><span>Wait for verification</span></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
const paymentForm = document.getElementById('paymentForm');
const continueGcashBtn = document.getElementById('continueGcashBtn');
const handoffCard = document.getElementById('handoffCard');
const gcashAmountLabel = document.getElementById('gcashAmountLabel');
const openGcashBtn = document.getElementById('openGcashBtn');
const openGcashBtnStep1 = document.getElementById('openGcashBtnStep1');
const verificationForm = document.getElementById('verificationForm');
const successMessage = document.getElementById('successMessage');
const verifyBtn = document.getElementById('verifyBtn');
const cancelBtn = document.getElementById('cancelBtn');
const autoOpenGcash = paymentForm?.dataset.autoOpen === '1';
const prebuiltGcashUrl = @json($gcashUrl ?? null);
const prebuiltAmount = @json($transaction?->amount ?? null);
const prebuiltTransactionId = @json($transaction?->id ?? null);
let currentGcashUrl = prebuiltGcashUrl || null;

const updateOpenButtons = () => {
    [openGcashBtn, openGcashBtnStep1].forEach((button) => {
        if (!button) {
            return;
        }

        const disabled = !currentGcashUrl;
        button.disabled = disabled;
        button.classList.toggle('opacity-60', disabled);
        button.classList.toggle('cursor-not-allowed', disabled);
    });
};

const showHandoff = (amountValue, gcashUrl) => {
    currentGcashUrl = gcashUrl || null;

    updateOpenButtons();

    if (gcashAmountLabel) {
        if (amountValue !== null && amountValue !== undefined && amountValue !== '') {
            gcashAmountLabel.textContent = `₱${Number(amountValue).toFixed(2)}`;
        } else {
            gcashAmountLabel.textContent = '-';
        }
    }

    if (paymentForm) {
        paymentForm.classList.add('hidden');
    }

    handoffCard.classList.remove('hidden');
    verificationForm.classList.remove('hidden');
    verificationForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

const launchGcashApp = (url) => {
    if (!url) {
        return;
    }

    const tempLink = document.createElement('a');
    tempLink.href = url;
    tempLink.style.display = 'none';
    document.body.appendChild(tempLink);
    tempLink.click();

    setTimeout(() => {
        window.location.href = url;
    }, 120);

    setTimeout(() => {
        window.location.replace(url);
    }, 400);

    setTimeout(() => {
        tempLink.remove();
    }, 800);
};

if (autoOpenGcash && prebuiltGcashUrl) {
    setTimeout(() => {
        if (prebuiltTransactionId) {
            window.transactionId = prebuiltTransactionId;
        }
        showHandoff(prebuiltAmount, prebuiltGcashUrl);
        launchGcashApp(prebuiltGcashUrl);
    }, 300);
}

updateOpenButtons();

const initiateGcashPayment = async () => {
    const formData = new FormData(paymentForm);
    
    try {
        const response = await fetch('{{ route("payment.gcash", $order) }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            },
            body: formData
        });
        const contentType = response.headers.get('content-type') || '';
        const responseText = await response.text();
        const data = contentType.includes('application/json') ? JSON.parse(responseText) : { error: responseText };
        
        if (response.ok) {
            // Store transaction ID
            window.transactionId = data.transaction_id;
            showHandoff(data.amount, data.gcash_url);

            launchGcashApp(data.gcash_url);
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        alert('Error processing payment: ' + error.message);
    }
};

if (paymentForm) {
    paymentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        initiateGcashPayment();
    });
}

const handleOpenGcash = () => {
    if (!currentGcashUrl) {
        alert('GCash link is not ready yet. Please click Continue to GCash Payment first.');
        return;
    }

    launchGcashApp(currentGcashUrl);
};

if (openGcashBtn) {
    openGcashBtn.addEventListener('click', handleOpenGcash);
}

if (openGcashBtnStep1) {
    openGcashBtnStep1.addEventListener('click', handleOpenGcash);
}

if (continueGcashBtn) {
    continueGcashBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (prebuiltGcashUrl) {
            if (prebuiltTransactionId) {
                window.transactionId = prebuiltTransactionId;
            }
            showHandoff(prebuiltAmount || document.getElementById('amount')?.value, prebuiltGcashUrl);
            launchGcashApp(prebuiltGcashUrl);
            return;
        }

        initiateGcashPayment();
    });
}

if (verifyBtn) {
verifyBtn.addEventListener('click', async () => {
    const receipt = document.getElementById('gcash_receipt_number').value;
    
    if (!receipt || !window.transactionId) {
        alert('Please enter the GCash receipt number');
        return;
    }
    
    try {
        const response = await fetch(`/payment/verify/${window.transactionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            },
            body: JSON.stringify({
                gcash_receipt_number: receipt
            })
        });
        const contentType = response.headers.get('content-type') || '';
        const responseText = await response.text();
        const data = contentType.includes('application/json') ? JSON.parse(responseText) : { error: responseText };
        
        if (response.ok) {
            verificationForm.classList.add('hidden');
            handoffCard.classList.add('hidden');
            successMessage.classList.remove('hidden');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        alert('Error verifying payment: ' + error.message);
    }
});
}

if (cancelBtn) {
cancelBtn.addEventListener('click', () => {
    handoffCard.classList.add('hidden');
    verificationForm.classList.add('hidden');
    paymentForm.classList.remove('hidden');
    window.transactionId = null;
    currentGcashUrl = null;
    updateOpenButtons();
});
}
</script>
@endsection
