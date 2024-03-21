<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerImage;

class BannerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = BannerImage::orderBy('created_at' , 'desc')->get();
        return view('banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,bmp,png|max:2048'
        ]);

        try{

            $banner = $request->all();

            if($request->hasFile('image')) {
                $banner['image'] = $request->image->store('banners');
                
            }
            $bannerimage = new BannerImage;
            $bannerimage->image = $banner['image'];
            $bannerimage->status = $request->status;
            $bannerimage->save();
            // BannerImage::create($banner);
            return redirect()->route('banner.index')->with('flash_success','Banners Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Banner Not Found');
        }
    }

    public function show($id)
    {
        try {
            return BannerImage::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function edit($id)
    {
        try {
            $banner = BannerImage::findOrFail($id);
            return view('banners.edit',compact('banner'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'mimes:jpg,jpeg,bmp,png|max:2048'
        ]);

        try {
            $Banner= BannerImage::where('id',$id)->first();
            
            if($request->hasFile('image')) {
                $Banner->image = $request->image->store('documents');
            }

            $Banner->status = $request->status;
            $Banner->save();
            return redirect()->route('banner.index')->with('flash_success', 'Banner Updated Successfully');    
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Banner Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            BannerImage::find($id)->delete();
            return back()->with('flash_success', 'Banner deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_success', 'Banner Not Found');
        }
    }
}
