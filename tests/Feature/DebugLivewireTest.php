<?php
namespace Tests\Feature;
use Tests\TestCase;
use Livewire\Livewire;
class DebugLivewireTest extends TestCase {
    public function test_debug() {
        try {
            $component = Livewire::test(\App\Livewire\OperatorRegistration::class);
            var_dump("SUCCESS");
        } catch (\Exception $e) {
            echo "EXCEPTION!\n";
            echo $e->getMessage() . "\n";
            // The HTML is what caused the snapshot crash. Let's see it if possible.
            // Often, Livewire throws before returning. We can just render the blade view manually to see the error.
        }
        
        try {
            $html = view('livewire.operator-registration', ['packagingReferences' => collect([])])->render();
            echo "VIEW RENDERED SUCCESSFULLY\n";
        } catch (\Exception $e) {
            echo "VIEW ERROR: " . $e->getMessage() . "\n";
        }
    }
}
