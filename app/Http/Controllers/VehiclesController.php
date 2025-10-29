<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $department = $request->department ?? 'All';
        if($department == 'All')
        {
            $vehicles = Vehicles::all();
        }
        else
        {
            $vehicles = Vehicles::where('department_id', $department)->get();
        }

        $departments = accounts::active()->get();

        return view('vehicles.index', compact('department', 'vehicles', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            ['r_no' => 'required|unique:vehicles,r_no',]
        );

        Vehicles::Create($request->all());

        return Redirect()->back()->with('success', 'Vehicle Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicles $vehicles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicles $vehicles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            ['r_no' => 'required|unique:vehicles,r_no,' . $id,]
        );

        Vehicles::find($id)->update($request->all());

        return Redirect()->back()->with('success', 'Vehicle Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicles $vehicles)
    {
        //
    }
}
