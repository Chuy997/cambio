<div class="px-4 sm:px-6 lg:px-8 max-w-[1200px] mx-auto min-h-screen pb-12">
    
    {{-- Header Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-900 via-indigo-900 to-violet-900 p-8 sm:p-10 mb-8 shadow-2xl shadow-indigo-200/50 mt-4">
        {{-- Decorative background elements --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-white tracking-widest uppercase">Módulo de Administración</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-md">Catálogo de Referencias</h1>
                <p class="mt-3 text-indigo-200 max-w-xl text-sm sm:text-base leading-relaxed">
                    Visualiza y gestiona el inventario de empaques indirectos del sistema. Los cambios aquí afectarán la disponibilidad en el panel del Operador instantáneamente.
                </p>
            </div>
            
            <div class="mt-6 sm:mt-0 sm:ml-16 sm:flex-none">
                <button wire:click="create" type="button" class="group relative inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-extrabold text-indigo-900 shadow-xl shadow-black/10 hover:bg-slate-50 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl overflow-hidden focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-2xl opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative flex items-center gap-2">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nuevo Indirecto</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Alerts Container --}}
    <div class="space-y-4 mb-8">
        @if (session()->has('message'))
            <div class="animate-[slideDown_0.3s_ease-out] rounded-2xl bg-gradient-to-r from-emerald-50 to-white p-4 shadow-sm border border-emerald-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-600 text-lg">✓</span>
                    </div>
                    <p class="text-sm font-bold text-emerald-800">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="animate-[slideDown_0.3s_ease-out] rounded-2xl bg-gradient-to-r from-rose-50 to-white p-4 shadow-sm border border-rose-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                        <span class="text-rose-600 text-lg">⚠️</span>
                    </div>
                    <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Form Section (Modal-like Floating Card) --}}
    @if($isFormOpen)
        <div class="bg-white p-8 mb-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden animate-[fadeIn_0.4s_ease-out]">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500"></div>
            
            <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-xl font-black tracking-tight text-slate-800">
                    {{ $editId ? 'Editar Referencia Existente' : 'Registrar Nuevo Indirecto' }}
                </h3>
            </div>

            <form wire:submit="save" class="space-y-8">
                <div class="grid grid-cols-1 gap-y-8 gap-x-8 sm:grid-cols-3">
                    
                    {{-- Code --}}
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 group-focus-within:text-indigo-500 transition-colors">Código de Empaque</label>
                        <div class="relative">
                            <input type="text" wire:model="code" required class="block w-full rounded-2xl border-2 border-slate-200 bg-slate-50/50 focus:bg-white text-base lg:text-lg font-mono font-bold uppercase placeholder-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-300" placeholder="EJ: MLP102">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                            </div>
                        </div>
                        @error('code') <p class="mt-2 text-xs font-bold text-rose-500 animate-pulse">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 group-focus-within:text-indigo-500 transition-colors">Categoría</label>
                        <div class="relative">
                            <input type="text" wire:model="type" required class="block w-full rounded-2xl border-2 border-slate-200 bg-slate-50/50 focus:bg-white text-base lg:text-lg font-bold text-slate-700 uppercase placeholder-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-300" placeholder="EJ: GAYLOR o CAJA">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>
                        @error('type') <p class="mt-2 text-xs font-bold text-rose-500 animate-pulse">{{ $message }}</p> @enderror
                    </div>

                    {{-- Dimensions --}}
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 group-focus-within:text-indigo-500 transition-colors">Dimensiones <span class="text-slate-300 font-medium normal-case tracking-normal">(Opcional)</span></label>
                        <div class="relative">
                            <input type="text" wire:model="dimensions" class="block w-full rounded-2xl border-2 border-slate-200 bg-slate-50/50 focus:bg-white text-base lg:text-lg font-bold text-slate-700 placeholder-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-300" placeholder="L x W x H">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                            </div>
                        </div>
                        @error('dimensions') <p class="mt-2 text-xs font-bold text-rose-500 animate-pulse">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-slate-100">
                    <button type="button" wire:click="closeForm" class="rounded-2xl bg-white border-2 border-slate-200 px-6 py-3 text-sm font-extrabold text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 active:scale-95 transition-all duration-300 border border-transparent focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                        Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Data Table Section --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden mt-6">
        
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center border border-indigo-100/50">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Directorio Activo</h3>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5 uppercase tracking-wider">{{ $indirectos->count() }} Referencias Totales</p>
                </div>
            </div>
            
            <div class="w-full sm:w-96 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 text-slate-400 group-focus-within:text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search" class="block w-full rounded-2xl border-2 border-slate-100 bg-slate-50/50 py-3.5 pl-11 pr-4 text-sm font-medium text-slate-700 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 outline-none" placeholder="Buscar por código, tipo o dimensión...">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="py-4 pl-8 pr-4 text-xs font-black text-slate-400 uppercase tracking-widest">Código</th>
                        <th class="py-4 px-4 text-xs font-black text-slate-400 uppercase tracking-widest">Categoría</th>
                        <th class="py-4 px-4 text-xs font-black text-slate-400 uppercase tracking-widest">Dimensiones</th>
                        <th class="py-4 pl-4 pr-8 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($indirectos as $ref)
                        <tr class="group hover:bg-indigo-50/30 transition-colors duration-200">
                            {{-- Code --}}
                            <td class="py-4 pl-8 pr-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="font-mono font-black text-sm text-indigo-900 bg-indigo-50/50 group-hover:bg-indigo-100 border border-indigo-100 px-2.5 py-1 rounded-lg transition-colors shadow-sm">
                                        {{ $ref->code }}
                                    </span>
                                </div>
                            </td>
                            
                            {{-- Type --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold uppercase tracking-wider text-slate-600 bg-slate-100 group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-slate-200 transition-all">
                                    {{ $ref->type }}
                                </span>
                            </td>
                            
                            {{-- Dimensions --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-slate-500 group-hover:text-slate-700 transition-colors">
                                    {{ $ref->dimensions ?: '—' }}
                                </span>
                            </td>
                            
                            {{-- Actions --}}
                            <td class="py-4 pl-4 pr-8 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $ref->id }})" class="p-2 rounded-xl text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors tooltip" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button onclick="confirm('¿Estás verdaderamente seguro de destruir la referencia {{ $ref->code }}? Esto no se puede deshacer.') || event.stopImmediatePropagation()" wire:click="delete({{ $ref->id }})" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition-colors tooltip" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-4">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-700 mb-1">Directorio Vacío</h3>
                                <p class="text-sm text-slate-500">No se encontraron referencias que coincidan con la búsqueda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
