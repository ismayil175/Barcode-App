<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = Market::latest()->paginate(5);

        return view('markets.index', compact('markets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function indexs()
    {
        $markets = Market::all();

        return view('welcome', compact('markets'));
    }

    public function getProductByBarcode($barcode)
    {
        $market = DB::table('markets')
            ->where('barcode', $barcode)
            ->first();

        if ($market) {
            return response()->json([
                'success' => true,
                'data' => $market
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Market not found'
            ]);
        }
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('markets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        Market::create($input);

        return redirect()->route('markets.index')
            ->with('success', 'Market created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function show(Market $market)
    {
        return view('markets.show', compact('market'));
    }

    public function showProducts(Request $request,$id)
    {
        $market = Market::findOrFail($id);
        return view('products.index',['products' => $market->products] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function edit(Market $market)
    {
        return view('markets.edit', compact('market'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Market $market)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required'
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        $market->update($input);

        return redirect()->route('markets.index')
            ->with('success', 'Market updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Market  $market
     
     * @return \Illuminate\Http\Response
     */
    public function destroy(Market $market)
    {
        $market->delete();

        return redirect()->route('markets.index')
            ->with('success', 'Market deleted successfully');
    }
}
