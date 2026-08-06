<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    @php
        $actionLinks = [
            ['label' => 'Approve Orders', 'route' => 'admin.orders.index'],
            ['label' => 'Sales Reports', 'route' => 'admin.reports.sales'],
            ['label' => 'Manage Users', 'route' => 'admin.users.index'],
            ['label' => 'Management History', 'url' => route('admin.dashboard').'#management-history'],
        ];
    @endphp

    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Manage Users</h1>
                <p class="text-sm text-gray-700">Update account roles for system access.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Dashboard</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-primary-strong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($actionLinks as $action)
                    @php
                        $isActive = isset($action['route']) && request()->routeIs($action['route']);
                    @endphp
                    <a
                        href="{{ $action['url'] ?? route($action['route']) }}"
                        class="rounded-lg px-4 py-3 text-center font-semibold transition {{ $isActive ? 'bg-primary text-gray-900 ring-1 ring-primary-strong/60 shadow-sm' : 'bg-secondary text-gray-900 hover:bg-secondary-strong' }}"
                    >
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">User Access Controls</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Manage Users</h2>
                <p class="mt-1 text-sm text-gray-600">Assign the correct role and permission path for every account.</p>
            </div>

            <div class="p-5">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Current Role</th>
                                <th class="px-4 py-3">Change Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold capitalize text-gray-700">{{ $user->user_type }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex flex-wrap items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="user_type" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm focus:border-primary-strong focus:outline-none">
                                                <option value="admin" {{ $user->user_type === 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="seller" {{ $user->user_type === 'seller' ? 'selected' : '' }}>Seller</option>
                                                <option value="customer" {{ $user->user_type === 'customer' ? 'selected' : '' }}>Customer</option>
                                            </select>
                                            <button type="submit" class="rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-gray-900 hover:bg-primary-strong">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>
