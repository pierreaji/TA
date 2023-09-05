<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'items' => Item::all(),
            'category' => Category::all(),
            'distributor' => Distributor::all(),
        ];
        return view('master.items.index', $data);
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
            if ($request->sale_price <= $request->distributor_price) {

                self::failed("Failed to $text Item! Stockist Price can't be less than Distributor Price");

                return redirect()->back();
            }
            $item = Item::find($request->id);
            if (!$item) {
                $item = new Item;
            }
            $item->id_category = $request->category;
            $item->id_distributor = $request->distributor;
            $item->name = $request->name;
            $item->type = $request->type;
            $item->sale_price = str_replace('.', '', $request->sale_price);
            $item->distributor_price = str_replace('.', '', $request->distributor_price);
            $item->save();

            DB::commit();

            self::success("Item {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item! " . $e->getMessage());

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
            $item = Item::find($id);
            $item->delete();

            DB::commit();

            self::success("Item {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item! " . $e->getMessage());

            return redirect()->back();
        }
    }
}
