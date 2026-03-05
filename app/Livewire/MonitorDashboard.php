<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChangeRequest;
use Livewire\Attributes\On;

class MonitorDashboard extends Component
{
    public $requests = [];

    public function mount()
    {
        $this->loadRequests();
    }

    #[On('echo:change-requests,ChangeRequestCreated')]
    public function loadRequests()
    {
        $this->requests = ChangeRequest::with('items')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
    }

    public function confirmItem(int $itemId): void
    {
        $item = \App\Models\ChangeRequestItem::find($itemId);
        if (! $item) return;

        $item->update(['status' => 'completado']);

        // If ALL items in the parent request are now completed, mark request as completado
        $request = $item->changeRequest()->with('items')->first();
        if ($request && $request->items->every(fn($i) => $i->status === 'completado')) {
            $request->update(['status' => 'completado']);
        }

        $this->loadRequests();
    }

    public function confirmChange($requestId)
    {
        $request = ChangeRequest::find($requestId);
        if ($request) {
            // Mark all items and the request itself as completado
            $request->items()->update(['status' => 'completado']);
            $request->update(['status' => 'completado']);
            $this->loadRequests();
        }
    }

    public function render()
    {
        return view('livewire.monitor-dashboard');
    }
}
