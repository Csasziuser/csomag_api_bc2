<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcel;

class ParcellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $parcels = Parcel::with("resident")->get();
            return response()->json($parcels, 200, options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json(["uzenet"=>"Nem sikerült lekérni a csomagokat"], 500, options:JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request -> validate([
           "resident_id" => "required|integer|exists:residents,id",
           "sender" => "required|string",
           "tracking_number" => "required|string|unique:parcels,tracking_number",
           "arrived_at" => "required|date",
           "size" => "required|string|in:small,medium,large,extra_large",
           "picked_up" => "sometimes|boolean",
           "picked_up_at" => "sometimes|date",
        ],[
           "required" => "A :attribute mező kitöltése kötelező",
           "string" => "Hiányzó :attribute szöveges érték",
           "integer" => "Hiányzó :attribute szám érték",
           "date" => "Hiányzó :attribute dátum érték",
           "boolean" => "Hiányzó :attribute boolean érték",
           "exists" => "Hiányzó :attribute exits érték",
           "unique" => "Hiányzó :attribute unique érték",
           "in" => "Hiányzó :attribute in érték",
        ],[
            "resident_id" => "Azonosító",
            "sender" => "Küldő",
            "tracking_number" => "Követési azonosító",
            "arrived_at" => "Megérkezési időpont",
            "size" => "Méret",
            "picked_up" => "Felvétel",
            "picked_up_at" => "Felvétel időpontja",
        ]);
        try {
            Parcel::create($validated);
            return response() -> json(["uzenet" => "A csomag rögzítve"], 201, options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response() -> json(["uzenet" => "Nem jo valami"],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $csomag = Parcel::find($id);
            if(!$csomag){
                return response()->json(["uzenet" => "Ezzel az azonosítóval nincs csomag a rendszerben!"],404,options:JSON_UNESCAPED_UNICODE);
            }
            $csomag->delete();
            return response()->json(["uzenet" => "Csomag sikeresen törölve!"],200,options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json(["uzenet" => "Hiba a törlés során!"],500,options:JSON_UNESCAPED_UNICODE);
        }
    }
}
