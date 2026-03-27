<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $residents = Resident::all();
            return response()->json($residents, 200, options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json(["uzenet" => "Nem sikerült a lakók listázása"], 500, options:JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'apartment' => 'required|string',
            'email' => 'required|email|unique:residents,email',
            'phone' => "sometimes|string",
        ],[
            'required' => ':attribute mező kitöltése kotelezo',
            'string' => ':attribute mezonek szövegnek kell lennie',
            'email' => ':attribute mezonek email tipusunak kell lennie',
            'unique' => ':attribute emezonek egyedinek kell lennie', 
        ],[
            'name' => 'név',
            'apartment' => 'lakásszám',
            'phone' => 'telefon'
        ]);

        try {
            Resident::create($validated);
            return response()->json(['uzenet' => 'Sikeres rögzítés'],201,options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json(['uzenet' => 'Sikerteln rögzítés'],500,options:JSON_UNESCAPED_UNICODE);
        }

        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $f = Resident::find($id)->with("parcels")->get();
        } catch (\Throwable $th) {
            return response()->json("Valami hiba merült fel a resident keresese kozben",500,options:JSON_UNESCAPED_UNICODE);
        }
        if(!$f)
        {
            return response()->json("Nincs ilyen idval rendelkezo resident",404,options:JSON_UNESCAPED_UNICODE);
        }
        return response()->json($f,200,options:JSON_UNESCAPED_UNICODE);
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
            $resident = Resident::find($id);
            if (!$resident) {
                return response()->json(['uzenet'=>'nem letezik ilyen lakos']);
            }
            $csomagok = Parcel::where("resident_id",$resident->id)->get();
            Log::info($csomagok);
            if ($csomagok) {
                foreach ($csomagok as $csomag) {
                    if ($csomag->picked_up == 0) {
                        return response()->json(['uzenet' => 'meg van nem atvett csomag'],404);
                    }
            } 
            }  
        } catch (\Throwable $th) {
            return response()->json(["uzenet" => "sikertelen torles"],500);
        }
        try {
            $resident->delete();
            return response()->json(['uzenet'=>'sikeres torles']);
        } catch (\Throwable $th) {
            return response()->json(['uzenet'=>'sikertelen torles'],500);
        }
    }
}
