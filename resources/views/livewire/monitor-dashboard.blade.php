<div class="min-h-screen bg-gray-50 font-sans p-6 text-gray-900">
    <div class="max-w-7xl mx-auto">

        {{-- Clipboard helper (works on HTTP, no HTTPS required) --}}
        <script>
            function copiarTexto(text, btn) {
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text);
                    } else {
                        var ta = document.createElement('textarea');
                        ta.value = text;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.focus(); ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                    var orig = btn.innerHTML;
                    btn.innerHTML = '\u2705 Copiado';
                    setTimeout(function(){ btn.innerHTML = orig; }, 1500);
                } catch(e) { alert('No se pudo copiar: ' + e); }
            }
        </script>

        <div class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-4xl font-bold tracking-tight">Monitor de Indirectos</h1>
                <p class="text-lg text-gray-500 mt-2">Visor en tiempo real de requerimientos de empaque.</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span>En vivo</span>
            </div>
        </div>

        <div class="grid gap-6">
            @forelse ($requests as $request)
                <div class="bg-white rounded-xl border {{ $request->status === 'pendiente' ? 'border-yellow-400 shadow-sm' : 'border-gray-200' }} overflow-hidden" wire:key="req-{{ $request->id }}">
                    <!-- Header -->
                    <div class="px-6 py-4 flex justify-between items-center {{ $request->status === 'pendiente' ? 'bg-yellow-50' : 'bg-gray-50' }}">
                        <div class="flex items-center space-x-4">
                            <span class="text-2xl font-bold">#{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-sm text-gray-500">{{ $request->created_at->format('H:i:s - d/m/Y') }}</span>
                            
                            @if($request->status === 'pendiente')
                                <span class="px-4 py-1 bg-yellow-400 text-yellow-900 text-sm font-bold rounded-full uppercase tracking-wide">
                                    Pendiente
                                </span>
                            @else
                                <span class="px-4 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full uppercase tracking-wide">
                                    Completado
                                </span>
                            @endif
                        </div>

                        @if($request->status === 'pendiente')
                            <div class="flex items-center gap-3">
                                {{-- Copy all BOX codes (HTTP-compatible) --}}
                                @php $boxList = $request->items->pluck('box_name')->join(' '); @endphp
                                <button
                                    onclick="copiarTexto('{{ $boxList }}', this)"
                                    class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-blue-400 text-gray-700 hover:text-blue-700 font-bold rounded-lg text-sm transition-colors">
                                    📋 Copiar BOX
                                </button>
                                <button wire:click="confirmChange({{ $request->id }})" 
                                    class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg shadow transition-colors">
                                    CONFIRMAR CAMBIO
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Items -->
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-b border-gray-100 text-gray-500 uppercase text-xs tracking-wider">
                                    <th class="px-6 py-3 font-medium">Box</th>
                                    <th class="px-6 py-3 font-medium">Código Indirecto</th>
                                    <th class="px-6 py-3 font-medium text-right">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($request->items as $item)
                                    @php $itemDone = ($item->status ?? 'pendiente') === 'completado'; @endphp
                                    <tr class="{{ $itemDone ? 'bg-green-50' : 'hover:bg-gray-50' }} transition-colors">
                                        <td class="px-6 py-3 font-mono font-bold text-xl {{ $itemDone ? 'text-green-700 line-through decoration-green-400' : '' }}">{{ $item->box_name }}</td>
                                        <td class="px-6 py-3 font-mono text-xl {{ $itemDone ? 'text-green-700' : '' }}">{{ $item->indirecto_code }}</td>
                                        <td class="px-6 py-3 text-right">
                                            @if(! $itemDone && $request->status === 'pendiente')
                                                <button
                                                    wire:click="confirmItem({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="px-4 py-1.5 bg-green-500 hover:bg-green-600 active:bg-green-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">
                                                    ✓ Confirmar
                                                </button>
                                            @else
                                                <span class="text-green-600 font-bold text-sm">✅ Listo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-xl font-medium text-gray-900">Sin Solicitudes</h3>
                    <p class="mt-1 text-gray-500">Actualmente no hay solicitudes de cambio de indirectos en cola.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
