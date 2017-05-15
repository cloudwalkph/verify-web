<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBrandsRequest;

class BrandsController extends Controller
{
    public function index()
    {
        return view('management.admin.brands.create');
    }

    public function store(CreateBrandsRequest $request)
    {
        $input = $request->all();

        $brand = Brand::create($input);

        return redirect()->back()->with('status', 'Successfully added new brand');
    }

    public function edit($id)
    {
        $brand = Brand::where('id', $id)->first();

        return view('management.admin.brands.update', compact('brand'));
    }

    public function update(CreateBrandsRequest $request, $id)
    {
        $input = $request->all();

        $brand = Brand::find($id)->update($input);

        return redirect()->back()->with('status', 'Successfully updated brand details');
    }

}