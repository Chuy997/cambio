<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Polling;
use App\Models\ChangeRequest;
use App\Events\ChangeRequestCreated;
use App\Services\SystemAlertService;
use App\Models\PackagingReference;
use Illuminate\Support\Facades\DB;

class OperatorRegistration extends Component
{
    // ——— State ———————————————————————————————————————————————————
    /** Array of ['box_name' => string, 'indirecto_code' => string] */
    public array $rows = [];

    /** Temporary BOX scan input */
    public string $boxInput = '';

    /** Which card's indirecto field currently has focus */
    public ?int $focusedRow = null;

    // ——— Catalog filters ——————————————————————————————————————————
    public string $searchReference = '';
    public ?string $activeCategory = null;

    // ——— Box scanning ————————————————————————————————————————————

    public function addBox(): void
    {
        $code = strtoupper(trim($this->boxInput));

        if ($code === '') {
            return;
        }

        // Check duplicates
        $existing = array_column($this->rows, 'box_name');
        if (in_array($code, $existing, true)) {
            $this->addError('boxInput', "El BOX «{$code}» ya fue agregado.");
            return;
        }

        $this->rows[] = ['box_name' => $code, 'indirecto_code' => ''];
        $this->boxInput = '';
        $this->focusedRow = count($this->rows) - 1;
        $this->resetErrorBag('boxInput');
    }

    public function removeRow(int $index): void
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);

        if ($this->focusedRow === $index) {
            $this->focusedRow = null;
        }
    }

    // ——— Pick reference from catalog ——————————————————————————————

    public function pickReference(string $code): void
    {
        $targetRow = $this->focusedRow ?? (count($this->rows) > 0 ? count($this->rows) - 1 : null);
        if ($targetRow !== null && isset($this->rows[$targetRow])) {
            $this->rows[$targetRow]['indirecto_code'] = $code;
        }
        // Close the dropdown after selection
        $this->focusedRow = null;
    }

    public function setCategory(?string $category): void
    {
        $this->activeCategory = ($this->activeCategory === $category) ? null : $category;
        $this->searchReference = '';
    }

    // ——— Pending requests (real-time) ———————————————————————————

    #[On('echo:change-requests,ChangeRequestCreated')]
    public function refreshPending(): void
    {
        // Just re-render — the render() method always reads fresh from DB
    }

    // ——— Submit ——————————————————————————————————————————————————

    public function submit(SystemAlertService $alertService): void
    {
        if (empty($this->rows)) {
            $this->addError('boxInput', 'Agrega al menos un BOX antes de enviar.');
            return;
        }

        $this->validate([
            'rows.*.box_name'       => 'required|string',
            'rows.*.indirecto_code' => 'required|string',
        ], [
            'rows.*.indirecto_code.required' => 'Cada BOX debe tener un código de indirecto asignado.',
        ]);

        DB::beginTransaction();
        try {
            $changeRequest = ChangeRequest::create(['status' => 'pendiente']);

            foreach ($this->rows as $row) {
                $changeRequest->items()->create([
                    'box_name'       => $row['box_name'],
                    'indirecto_code' => strtoupper(trim($row['indirecto_code'])),
                ]);
            }

            DB::commit();

            broadcast(new ChangeRequestCreated($changeRequest));
            $alertService->sendWindowsAlert(
                "Cambio de indirecto — {$changeRequest->items->count()} BOX(es). Solicitud #{$changeRequest->id}"
            );

            $this->reset(['rows', 'boxInput', 'focusedRow']);
            session()->flash('message', "Solicitud #{$changeRequest->id} enviada con " . $changeRequest->items->count() . " BOX(es).");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('submit', 'Error al guardar: ' . $e->getMessage());
        }
    }

    // ——— Render ——————————————————————————————————————————————————

    public function render()
    {
        // Catalog filter
        $query = PackagingReference::query();
        if ($this->activeCategory !== null) {
            $query->where('type', $this->activeCategory);
        } elseif (trim($this->searchReference) !== '') {
            $term = '%' . trim($this->searchReference) . '%';
            $query->where(fn ($q) =>
                $q->where('code', 'like', $term)
                  ->orWhere('type', 'like', $term)
                  ->orWhere('dimensions', 'like', $term)
            );
        }
        $packagingReferences = $query->orderBy('code')->get();

        // Per-card autocomplete suggestions
        $suggestions = collect();
        if ($this->focusedRow !== null && isset($this->rows[$this->focusedRow])) {
            $q = trim($this->rows[$this->focusedRow]['indirecto_code'] ?? '');
            if (strlen($q) >= 1) {
                $suggestions = PackagingReference::where('code', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orderBy('code')
                    ->limit(8)
                    ->get();
            }
        }

        $categories = PackagingReference::distinct()->pluck('type')
            ->filter()->sort()->values();

        // Only show pending requests on the operator page
        $pendingRequests = ChangeRequest::with('items')
            ->where('status', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('livewire.operator-registration', compact(
            'packagingReferences',
            'categories',
            'suggestions',
            'pendingRequests'
        ));
    }
}
