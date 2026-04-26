<style>
    .suw-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .suw-card {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        overflow: hidden;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .suw-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.10);
        transform: translateY(-1px);
    }

    /* ── Header ── */
    .suw-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 20px;
        background: linear-gradient(to right, #f9fafb, #ffffff);
        border-bottom: 1px solid #f3f4f6;
    }
    .suw-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .suw-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(217,119,6,0.35);
        letter-spacing: 0.5px;
    }
    .suw-name {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        line-height: 1.3;
    }
    .suw-email {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }
    .suw-replacement-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        background: #fef3c7;
        color: #b45309;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* ── Body columns ── */
    .suw-body {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }
    .suw-col {
        padding: 16px 20px;
        border-right: 1px solid #f3f4f6;
    }
    .suw-col:last-child {
        border-right: none;
    }
    .suw-col-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
        margin-bottom: 10px;
    }

    /* ── Badges ── */
    .suw-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .suw-badge-product {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 6px;
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .suw-badge-replacement {
        font-size: 12px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* ── Address ── */
    .suw-address {
        font-size: 13px;
        line-height: 1.6;
        color: #374151;
    }
    .suw-billing-note {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
        font-size: 11px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 3px 8px;
        border-radius: 5px;
    }

    /* ── Empty / muted ── */
    .suw-muted {
        font-size: 13px;
        color: #d1d5db;
    }
    .suw-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 56px 20px;
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        color: #9ca3af;
        font-size: 14px;
        gap: 10px;
    }

    /* ── Dark mode ── */
    .dark .suw-card {
        background: #1f2937;
        border-color: #374151;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark .suw-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }
    .dark .suw-header {
        background: linear-gradient(to right, #111827, #1f2937);
        border-bottom-color: #374151;
    }
    .dark .suw-name  { color: #f9fafb; }
    .dark .suw-email { color: #9ca3af; }
    .dark .suw-replacement-pill {
        background: rgba(245,158,11,0.15);
        color: #fbbf24;
    }
    .dark .suw-col {
        border-right-color: #374151;
    }
    .dark .suw-col-label { color: #6b7280; }
    .dark .suw-badge-product {
        background: rgba(245,158,11,0.1);
        color: #fbbf24;
        border-color: rgba(245,158,11,0.25);
    }
    .dark .suw-badge-replacement {
        background: rgba(59,130,246,0.1);
        color: #60a5fa;
        border-color: rgba(59,130,246,0.25);
    }
    .dark .suw-address  { color: #d1d5db; }
    .dark .suw-billing-note {
        background: #374151;
        color: #9ca3af;
    }
    .dark .suw-muted { color: #4b5563; }
    .dark .suw-empty {
        border-color: #374151;
        color: #6b7280;
    }
</style>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Subscription Users</x-slot>

        <div class="suw-list">
            @forelse($users as $user)
                @php
                    $initials = collect(explode(' ', $user['name']))
                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                        ->take(2)
                        ->implode('');
                @endphp

                <div class="suw-card">

                    {{-- Header --}}
                    <div class="suw-header">
                        <div class="suw-header-left">
                            <div class="suw-avatar">{{ $initials }}</div>
                            <div>
                                <div class="suw-name">{{ $user['name'] }}</div>
                                <div class="suw-email">{{ $user['email'] }}</div>
                            </div>
                        </div>
                        @if($user['replacements']->isNotEmpty())
                            <span class="suw-replacement-pill">
                                {{ $user['replacements']->count() }} replacement{{ $user['replacements']->count() > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="suw-body">

                        {{-- Products --}}
                        <div class="suw-col">
                            <div class="suw-col-label">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                Products
                            </div>
                            @if($user['products']->isNotEmpty())
                                <div class="suw-badges">
                                    @foreach($user['products'] as $product)
                                        <span class="suw-badge-product">{{ $product }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="suw-muted">No products</span>
                            @endif
                        </div>

                        {{-- Shipping --}}
                        <div class="suw-col">
                            <div class="suw-col-label">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Shipping Address
                            </div>
                            @if($user['shipping'])
                                <div class="suw-address">{{ $user['shipping'] }}</div>
                                @if($user['shipping_is_billing'])
                                    <div class="suw-billing-note">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Using billing address
                                    </div>
                                @endif
                            @else
                                <span class="suw-muted">No address on file</span>
                            @endif
                        </div>

                        {{-- Replacements --}}
                        <div class="suw-col">
                            <div class="suw-col-label">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Replacements
                            </div>
                            @if($user['replacements']->isNotEmpty())
                                <div class="suw-badges">
                                    @foreach($user['replacements'] as $replacement)
                                        <span class="suw-badge-replacement">{{ $replacement }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="suw-muted">None chosen</span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="suw-empty">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    No subscription users found
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
