<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;

class AgendaController extends Controller
{
    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        $agenda = auth()->user()->agendas()->create($request->validated());

        $month = $agenda->date->format('Y-m');

        return redirect()->route('calendar.index', ['month' => $month])->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function update(UpdateAgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        $this->authorize('update', $agenda);

        $agenda->update($request->validated());

        $month = $agenda->date->format('Y-m');

        return redirect()->route('calendar.index', ['month' => $month])->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        $this->authorize('delete', $agenda);

        $month = $agenda->date->format('Y-m');

        $agenda->delete();

        return redirect()->route('calendar.index', ['month' => $month])->with('success', 'Agenda berhasil dihapus.');
    }
}
