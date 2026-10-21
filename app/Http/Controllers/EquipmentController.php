<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $equipment = Equipment::query()
            ->withSum('serviceRecords as total_cost', 'cost')
            ->when(
                array_key_exists($status, Equipment::STATUSES),
                fn ($q) => $q->where('status', $status)
            )
            ->orderBy('inventory_number')
            ->paginate(15)
            ->withQueryString();

        return view('equipment.index', compact('equipment', 'status'));
    }

    public function create()
    {
        return view('equipment.create', ['equipment' => new Equipment(['status' => 'active'])]);
    }

    public function store(EquipmentRequest $request)
    {
        $equipment = Equipment::create($request->validated());

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Zařízení bylo vytvořeno.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('serviceRecords');
        $totalCost = $equipment->serviceRecords->sum('cost');

        return view('equipment.show', compact('equipment', 'totalCost'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Zařízení bylo upraveno.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Zařízení bylo smazáno.');
    }

    /** Volitelné rozšíření: export servisní historie do CSV. */
    public function export(Equipment $equipment): StreamedResponse
    {
        $filename = 'servis_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $equipment->inventory_number) . '.csv';

        return response()->streamDownload(function () use ($equipment) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM kvůli diakritice v Excelu
            fputcsv($out, ['Evidenční číslo', 'Zařízení', 'Datum servisu', 'Popis', 'Cena'], ';');
            foreach ($equipment->serviceRecords as $r) {
                fputcsv($out, [
                    $equipment->inventory_number,
                    $equipment->name,
                    $r->serviced_at->format('Y-m-d'),
                    $r->description,
                    number_format($r->cost, 2, ',', ''),
                ], ';');
            }
            fputcsv($out, ['', '', '', 'Celkem', number_format($equipment->serviceRecords->sum('cost'), 2, ',', '')], ';');
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
