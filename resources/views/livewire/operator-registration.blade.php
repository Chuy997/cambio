{{-- ================================================================ --}}
{{-- REGISTRO DE INDIRECTOS — Card-per-BOX with per-card indirecto   --}}
{{-- ================================================================ --}}
<div class="min-h-screen bg-slate-100 font-sans">

    {{-- ─── HEADER ─────────────────────────────────────────────── --}}
    <header class="bg-slate-900 text-white px-5 py-4 sticky top-0 z-40 shadow-lg flex items-center justify-between">
        <div>
            <h1 class="text-lg font-extrabold tracking-widest uppercase">📦 Cambio de Indirectos</h1>
            <p class="text-slate-400 text-xs mt-0.5">Escanea BOX → asigna indirecto en cada BOX → envía</p>
        </div>
        @if(count($rows) > 0)
            <span class="text-xs font-black text-white bg-blue-600 rounded-full w-8 h-8 flex items-center justify-center shadow">{{ count($rows) }}</span>
        @endif
    </header>

    <div class="max-w-3xl mx-auto px-4 py-5 space-y-4">

        {{-- ─── ALERTS ────────────────────────────────────────── --}}
        @if(session()->has('message'))
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
                <span class="text-xl">✅</span>
                <p class="text-emerald-800 font-bold text-sm">{{ session('message') }}</p>
            </div>
        @endif

        @error('submit')
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-50 border border-red-200 shadow-sm">
                <span class="text-xl">⚠️</span>
                <p class="text-red-700 font-bold text-sm">{{ $message }}</p>
            </div>
        @enderror

        {{-- ─── SCAN INPUT CARD ────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">
            <label class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 block">Escanear BOX</label>
            @error('boxInput')
                <p class="mb-2 text-xs font-bold text-red-500">⚠️ {{ $message }}</p>
            @enderror
            <div class="flex gap-2">
                <input
                    type="text"
                    wire:model="boxInput"
                    wire:keydown.enter.prevent="addBox"
                    class="flex-1 bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-2xl font-mono font-bold uppercase tracking-widest text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                    placeholder="ESCANEAR BOX"
                    autofocus
                    autocomplete="off"
                >
                <button
                    type="button"
                    wire:click="addBox"
                    class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-extrabold px-5 py-3 rounded-xl text-sm transition-all shadow-sm whitespace-nowrap">
                    + Agregar
                </button>
            </div>
        </div>

        {{-- ─── BOX CARDS ──────────────────────────────────────── --}}
        @if(count($rows) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($rows as $idx => $row)
                    <div wire:key="card-{{ $idx }}"
                        class="bg-white rounded-2xl border-2 transition-colors shadow-sm overflow-visible
                            {{ $focusedRow === $idx ? 'border-blue-400' : 'border-slate-200' }}">

                        {{-- Card header --}}
                        <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-slate-400 bg-slate-100 rounded-lg w-6 h-6 flex items-center justify-center">{{ $idx + 1 }}</span>
                                <span class="font-mono font-black text-xl text-slate-800 tracking-wider">{{ $row['box_name'] }}</span>
                            </div>
                            <button
                                type="button"
                                wire:click="removeRow({{ $idx }})"
                                class="text-slate-300 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Indirecto input --}}
                        <div class="px-4 pt-3 pb-4 relative">
                            @error("rows.{$idx}.indirecto_code")
                                <p class="mb-1 text-xs font-bold text-red-500">⚠️ Requerido</p>
                            @enderror

                            <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1.5">Código Indirecto</label>
                            <input
                                type="text"
                                wire:model.live.debounce.200ms="rows.{{ $idx }}.indirecto_code"
                                wire:focus="$set('focusedRow', {{ $idx }})"
                                class="w-full bg-slate-50 border-2 rounded-xl px-4 py-2.5 text-lg font-mono font-bold uppercase tracking-widest text-slate-800 placeholder-slate-300 focus:outline-none focus:bg-white transition-colors
                                    {{ $row['indirecto_code'] ? 'border-emerald-400 bg-emerald-50 text-emerald-800' : 'border-slate-200 focus:border-blue-400' }}"
                                placeholder="TECLEAR O SELECCIONAR"
                                autocomplete="off"
                            >

                            {{-- Assigned badge --}}
                            @if($row['indirecto_code'])
                                <div class="mt-2 flex items-center gap-1.5">
                                    <span class="text-emerald-500">✓</span>
                                    <span class="text-xs font-bold text-emerald-700">{{ strtoupper($row['indirecto_code']) }}</span>
                                </div>
                            @endif

                            {{-- Live dropdown suggestions (only for focused card) --}}
                            @if($focusedRow === $idx && $suggestions->count() > 0)
                                <div class="absolute z-50 left-4 right-4 mt-1 bg-white rounded-xl border-2 border-blue-300 shadow-2xl overflow-hidden">
                                    @foreach($suggestions as $sug)
                                        <button
                                            type="button"
                                            wire:click="pickReference('{{ $sug->code }}')"
                                            class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-blue-50 active:bg-blue-100 transition-colors border-b border-slate-100 last:border-0 text-left">
                                            <span class="font-mono font-black text-base text-blue-700">{{ $sug->code }}</span>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">{{ $sug->type }}</span>
                                                @if($sug->dimensions)
                                                    <span class="text-xs text-slate-400 hidden sm:inline">{{ $sug->dimensions }}</span>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── Submit ─────────────────────────────────────── --}}
            <form wire:submit="submit">
                <div class="flex items-center justify-between bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">
                    <div class="text-sm text-slate-500">
                        <span class="font-bold text-slate-800">{{ count($rows) }}</span> BOX(es) —
                        <span class="font-bold {{ collect($rows)->where('indirecto_code', '')->count() > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ collect($rows)->where('indirecto_code', '!=', '')->count() }} asignados
                        </span>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-base px-7 py-3 rounded-xl shadow-md transition-all">
                        <span wire:loading.remove wire:target="submit">
                            <svg class="w-5 h-5 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Enviar
                        </span>
                        <span wire:loading wire:target="submit">⏳</span>
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12 text-slate-400">
                <p class="text-4xl mb-2">📷</p>
                <p class="font-semibold text-slate-500">Escanea el primer BOX para comenzar</p>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════ --}}
        {{-- REQUERIMIENTOS PENDIENTES (live)                          --}}
        {{-- ════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" wire:poll.5s>

            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-base">🕐</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">Requerimientos</h2>
                    <p class="text-slate-400 text-xs">Se actualiza automáticamente cada 5 seg.</p>
                </div>
                @php
                    $pendingCount = $pendingRequests->where('status','pendiente')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="bg-amber-500 text-white text-xs font-black px-2.5 py-1 rounded-full">{{ $pendingCount }} pen.</span>
                @endif
            </div>

            @if($pendingRequests->isEmpty())
                <div class="py-8 text-center">
                    <p class="text-2xl mb-1">✅</p>
                    <p class="text-slate-400 text-sm font-semibold">Sin requerimientos</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($pendingRequests as $req)
                        @php $isPending = $req->status === 'pendiente'; @endphp
                        <div class="px-5 py-3 flex items-start gap-3 {{ $isPending ? 'bg-amber-50' : 'bg-emerald-50' }}">

                            {{-- Status indicator --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($isPending)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-200 px-2 py-0.5 rounded-full">⏳ Pendiente</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-200 px-2 py-0.5 rounded-full">✅ Completado</span>
                                @endif
                            </div>

                            {{-- Items list --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs text-slate-400 font-bold">Solicitud #{{ $req->id }}</span>
                                    <span class="text-xs text-slate-300">·</span>
                                    <span class="text-xs text-slate-400">{{ $req->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($req->items as $item)
                                        @php $itemDone = ($item->status ?? 'pendiente') === 'completado'; @endphp
                                        <div class="flex items-center gap-1 rounded-lg px-2 py-1 border
                                            {{ $itemDone ? 'bg-emerald-50 border-emerald-300' : 'bg-white border-amber-200' }}">
                                            @if($itemDone)
                                                <span class="text-emerald-500 text-xs">✓</span>
                                            @else
                                                <span class="text-amber-400 text-xs">⏳</span>
                                            @endif
                                            <span class="font-mono font-black text-xs {{ $itemDone ? 'text-emerald-700' : 'text-slate-700' }}">{{ $item->box_name }}</span>
                                            <span class="text-slate-300 text-xs">→</span>
                                            <span class="font-mono font-bold text-xs text-blue-700">{{ $item->indirecto_code }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ════════════════════════════════════════════════════════ --}}
        {{-- CATÁLOGO                                                  --}}
        {{-- ════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50">
                <div class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">Catálogo de Referencias</h2>
                    <p class="text-slate-400 text-xs">Toca una fila para asignar el código a la tarjeta activa</p>
                </div>
            </div>

            <div class="p-4 space-y-4">
                {{-- Pills --}}
                @php $icons = ['CAJA'=>'📦','GAYLOR'=>'🗃️','TARIMA'=>'🪵','CRATE'=>'🧰']; @endphp
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="setCategory(null)"
                        class="px-3 py-1.5 rounded-full text-xs font-bold border-2 transition-all
                            {{ $activeCategory === null ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-300 hover:border-slate-500' }}">
                        🔍 Todos
                    </button>
                    @foreach($categories as $cat)
                        <button type="button" wire:click="setCategory('{{ $cat }}')"
                            class="px-3 py-1.5 rounded-full text-xs font-bold border-2 transition-all
                                {{ $activeCategory === $cat ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-500 border-slate-300 hover:border-violet-400 hover:text-violet-700' }}">
                            {{ $icons[$cat] ?? '📋' }} {{ Str::title($cat) }}
                        </button>
                    @endforeach
                </div>

                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="search"
                        wire:model.live.debounce.250ms="searchReference"
                        placeholder="Buscar por código, tipo o dimensión..."
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white focus:border-violet-400 focus:outline-none transition-colors
                            {{ $activeCategory ? 'opacity-40 cursor-not-allowed' : '' }}"
                        @if($activeCategory) disabled @endif
                    >
                </div>

                {{-- Count + clear --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-400">
                        <span class="font-bold text-slate-700">{{ $packagingReferences->count() }}</span> ref.
                        @if($activeCategory) — <span class="font-bold text-violet-600">{{ $activeCategory }}</span>@endif
                    </span>
                    @if($activeCategory || trim($searchReference))
                        <button type="button" wire:click="setCategory(null)" class="text-xs text-violet-600 font-bold hover:underline">✕ Limpiar</button>
                    @endif
                </div>

                {{-- Table 2 columns --}}
                @if($packagingReferences->count() > 0)
                    @php
                        $half   = (int) ceil($packagingReferences->count() / 2);
                        $chunks = $packagingReferences->chunk($half ?: 1);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($chunks as $chunk)
                            <div class="rounded-xl border border-slate-200 overflow-hidden">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-slate-50 text-xs uppercase font-bold text-slate-400 border-b border-slate-200">
                                            <th class="px-3 py-2">Código</th>
                                            <th class="px-3 py-2">Tipo</th>
                                            <th class="px-3 py-2 text-right">Dim.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($chunk as $ref)
                                            <tr wire:click="pickReference('{{ $ref->code }}')"
                                                class="cursor-pointer hover:bg-violet-50 active:bg-violet-100 transition-colors group">
                                                <td class="px-3 py-2">
                                                    <span class="font-mono font-black text-sm text-violet-700 bg-violet-50 group-hover:bg-violet-100 px-1.5 py-0.5 rounded transition-colors">{{ $ref->code }}</span>
                                                </td>
                                                <td class="px-3 py-2 text-xs font-semibold text-slate-600">{{ $ref->type ?? '–' }}</td>
                                                <td class="px-3 py-2 text-right text-xs text-slate-400">{{ $ref->dimensions ?? '–' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center">
                        <p class="text-3xl mb-1">🔍</p>
                        <p class="text-slate-400 text-sm font-semibold">Sin resultados</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
