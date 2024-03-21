<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\UserDocument;
use App\Models\KycDocument;

class DocumentResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::orderBy('created_at' , 'desc')->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create');
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
            'name' => 'required|max:20',
            'image' => 'required|mimes:jpg,jpeg,bmp,png|max:2048'
        ]);

        try{

            $document = $request->all();

            if($request->hasFile('image')) {
                $document['image'] = $request->image->store('documents');
                
            }

            Document::create($document);
            return redirect()->route('document.index')->with('flash_success','Document Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Document::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $document = Document::findOrFail($id);
            return view('documents.edit',compact('document'));
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
            'name' => 'required|max:20',
            'type' => 'required',
        ]);

        try {
            $Doc= Document::where('id',$id)->first();
            
            $Doc->name = $request->name;

            if($request->hasFile('image')) {
                $Doc->image = $request->image->store('documents');
            }

            $Doc->status = $request->status;
            $Doc->type = $request->type;
            $Doc->save();
            return redirect()->route('document.index')->with('flash_success', 'Document Updated Successfully');    
        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
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
            Document::find($id)->delete();
            UserDocument::where('document_id', $id)->delete();
            return back()->with('flash_error', 'Document deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }
}
