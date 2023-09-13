<?php

namespace App\Http\Controllers;

use App\Models\Knjiga;
use App\Models\Korisnik;
use App\Models\Zaduzenje;
use Illuminate\Http\Request;

class KorisnikController extends Controller
{
    public function pregled_korisnika()//Dohvatanje svih korisnika 
{
    return Korisnik::all();
}

public function prikaz_korisnika_po_idu($id)
{
    $user = Korisnik::findOrFail($id);
    return response()->json($user);
}
public function kreiraj_korisnika(Request $request)
{
    // Validacija ulaznih podataka
    $request->validate([
        'Ime' => 'required|string',
        'Prezime' => 'required|string',
    ]);

    // Kreiranje novog korisnika
    $korisnik = Korisnik::create([
        'Ime' => $request->Ime,
        'Prezime' => $request->Prezime,
    ]);

    return response()->json($korisnik, 201);
}
public function ukloni_korisnika($id)
{
    $user = Korisnik::find($id);

    if (!$user) {
        return response()->json(['message' => 'Korisnik nije pronađen.'], 404);
    }

    $user->delete();
    
    return response()->json(['message' => 'Korisnik uspešno obrisan.'], 200);
}
public function zaduziKnjigu($korisnikId, $knjigaId)
{
    $korisnik = Korisnik::findOrFail($korisnikId);
    $knjiga = Knjiga::findOrFail($knjigaId);

    $zaduzenje = new Zaduzenje();
    $zaduzenje->Korisnik_id = $korisnik->id;
    $zaduzenje->Knjige_id = $knjiga->id;
    $zaduzenje->ime = $korisnik->Ime;
    $zaduzenje->prezime = $korisnik->Prezime;
    $zaduzenje->naslov = $knjiga->Naslov;
    $zaduzenje->autor = $knjiga->Autor;
    $zaduzenje->save();

    return response()->json(['message' => 'Knjiga je uspešno zadužena korisniku.']);
}
public function vratiKnjigu($korisnikId, $knjigaId)
{
    $korisnik = Korisnik::findOrFail($korisnikId);
    $knjiga = Knjiga::findOrFail($knjigaId);

    $zaduzenje = Zaduzenje::where('Korisnik_id', $korisnik->id)
                            ->where('Knjige_id', $knjiga->id)
                            ->first();

    if (!$zaduzenje) {
        return response()->json(['message' => 'Korisnik nije zadužio ovu knjigu.'], 404);
    }

    $zaduzenje->delete();

    return response()->json(['message' => 'Knjiga je uspešno vraćena.']);
}


}
