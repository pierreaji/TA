<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            // 'users' => User::where('role', '!=', 'Admin')->get(),
            'distributor' => Distributor::all(),
        ];
        return view('master.distributor.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $distributor = Distributor::find($request->id);
            if (!$distributor) {
                $distributor = new Distributor();
            }
            $distributor->name = $request->name;
            $distributor->phone = $request->phone;
            $distributor->address = $request->address;
            $distributor->save();

            DB::commit();

            self::success("Distributor {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Distributor! " . $e->getMessage());

            return redirect()->back();
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

        $text = 'delete';
        DB::beginTransaction();
        try {
            $distributor = Distributor::find($id);
            $distributor->delete();

            DB::commit();

            self::success("Distributor {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Distributor! " . $e->getMessage());

            return redirect()->back();
        }
    }
}
