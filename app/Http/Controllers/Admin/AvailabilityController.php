<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::with('activeAppointment')
            ->withCount('appointments')
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('admin.availabilities', compact('availabilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ], [
            'date.after_or_equal' => 'La date ne peut pas être dans le passé.',
            'end_time.after' => "L'heure de fin doit être après l'heure de début.",
        ]);

        // Refuser un créneau qui en chevauche un autre le même jour
        $overlap = Availability::whereDate('date', $data['date'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->with('error', 'Ce créneau chevauche un créneau déjà existant ce jour-là.');
        }

        Availability::create($data);

        return back()->with('success', 'Créneau ajouté : vos clients peuvent le réserver.');
    }

    // Ouvrir ou fermer un créneau
    public function toggle(Availability $availability)
    {
        if ($availability->activeAppointment()->exists()) {
            return back()->with('error', "Ce créneau a une demande en cours : traitez-la d'abord.");
        }

        $availability->update(['is_open' => ! $availability->is_open]);

        return back()->with('success', $availability->is_open ? 'Créneau ouvert aux clients.' : 'Créneau fermé : il n\'est plus visible des clients.');
    }

    public function destroy(Availability $availability)
    {
        // On garde l'historique : un créneau qui a déjà reçu des demandes
        // ne se supprime pas, il se ferme.
        if ($availability->appointments()->exists()) {
            return back()->with('error', 'Ce créneau a un historique de demandes : fermez-le plutôt que de le supprimer.');
        }

        $availability->delete();

        return back()->with('success', 'Créneau supprimé.');
    }
}
