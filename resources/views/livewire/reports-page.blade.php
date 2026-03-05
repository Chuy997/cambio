<div class="min-h-screen bg-slate-100 font-sans">

    {{-- ─── HEADER ────────────────────────────────────────────── --}}
    <header class="bg-slate-900 text-white px-5 py-4 flex items-center justify-between shadow-lg">
        <div>
            <h1 class="text-lg font-extrabold tracking-widest uppercase">📊 Reportes & Estadísticas</h1>
            <p class="text-slate-400 text-xs mt-0.5">Historial completo de requerimientos de indirectos</p>
        </div>
        <a href="{{ route('registro') }}"
            class="text-xs font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a Registro
        </a>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-5">

        {{-- ─── STATS CARDS ─────────────────────────────────────── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-slate-900">{{ $totalRequests }}</p>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Total Solicitudes</p>
            </div>
            <div class="bg-amber-50 rounded-2xl border border-amber-200 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-amber-700">{{ $totalPending }}</p>
                <p class="text-xs font-bold uppercase tracking-widest text-amber-500 mt-1">Pendientes</p>
            </div>
            <div class="bg-emerald-50 rounded-2xl border border-emerald-200 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-emerald-700">{{ $totalCompleted }}</p>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-500 mt-1">Completados</p>
            </div>
            <div class="bg-blue-50 rounded-2xl border border-blue-200 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-blue-700">{{ $totalBoxes }}</p>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-500 mt-1">BOX Procesados</p>
            </div>
        </div>

        {{-- ─── FILTERS ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Filtrar:</span>

                {{-- Status filter --}}
                <div class="flex gap-2">
                    @foreach(['all' => '📋 Todos', 'pendiente' => '⏳ Pendientes', 'completado' => '✅ Completados'] as $val => $label)
                        <button type="button" wire:click="$set('filterStatus', '{{ $val }}')"
                            class="px-3 py-1.5 rounded-full text-xs font-bold border-2 transition-all
                                {{ $filterStatus === $val ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-300 hover:border-slate-500' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- Date filter --}}
                <div class="flex items-center gap-2 ml-auto">
                    <label class="text-xs font-bold text-slate-400">Fecha:</label>
                    <input type="date" wire:model.live="filterDate"
                        class="border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:border-blue-400 focus:bg-white transition-colors">
                    @if($filterDate)
                        <button type="button" wire:click="$set('filterDate', '')" class="text-xs text-slate-400 hover:text-red-500 font-bold">✕</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── REQUESTS TABLE ────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">Historial de Solicitudes</h2>
                <span class="text-xs text-slate-400 font-bold">{{ $requests->total() }} solicitudes encontradas</span>
            </div>

            @if($requests->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-4xl mb-2">📭</p>
                    <p class="text-slate-500 font-semibold">No hay solicitudes con ese filtro</p>
                </div>
            @else
                {{-- Desktop table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-400">
                                <th class="px-5 py-3">#</th>
                                <th class="px-5 py-3">Estado</th>
                                <th class="px-5 py-3">Fecha</th>
                                <th class="px-5 py-3">BOX(es) → Indirecto</th>
                                <th class="px-5 py-3 text-right">Items</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($requests as $req)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-black text-slate-500 text-sm">#{{ $req->id }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($req->status === 'pendiente')
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">⏳ Pendiente</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">✅ Completado</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-700">{{ $req->created_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ $req->created_at->format('H:i:s') }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($req->items as $item)
                                                <div class="flex items-center gap-1 bg-slate-100 rounded-lg px-2 py-1">
                                                    <span class="font-mono font-black text-xs text-slate-700">{{ $item->box_name }}</span>
                                                    <span class="text-slate-400 text-xs">→</span>
                                                    <span class="font-mono font-bold text-xs text-blue-700">{{ $item->indirecto_code }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <span class="text-sm font-black text-slate-400">{{ $req->items->count() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach($requests as $req)
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-slate-400 text-xs">#{{ $req->id }}</span>
                                    @if($req->status === 'pendiente')
                                        <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">⏳ Pendiente</span>
                                    @else
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">✅ Completado</span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-400">{{ $req->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($req->items as $item)
                                    <div class="flex items-center gap-1 bg-slate-100 rounded-lg px-2 py-1">
                                        <span class="font-mono font-black text-xs text-slate-700">{{ $item->box_name }}</span>
                                        <span class="text-slate-400 text-xs">→</span>
                                        <span class="font-mono font-bold text-xs text-blue-700">{{ $item->indirecto_code }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
