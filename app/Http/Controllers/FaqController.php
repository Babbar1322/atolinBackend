<?php

namespace App\Http\Controllers;

use App\Models\faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, faq $faq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(faq $faq)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function showfaq(faq $faq)
    {
        $faq = faq::where('published', 1)
            ->orderBy('id')
            ->get();


        return response()->json([
            'message' => 'FAQ Content.',
            'data' => $faq,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function showabout(faq $faq)
    {
        $about = '<div>Sending and receiving money online has become a common practice in India today. Digital wallets have been upping their game with unique features and smooth user interfaces. Google had brought Tez to join the league of digital wallets and handle everything payments related in India. It later re-branded the  as Google Pay keeping in view the ever-growing UPI payments market in India. Google Pay has been launched with a slew of amazing features and its expansion plans are all set to transform the payments market in India.
        <br>
        <br>
        <strong>Related:</strong>
        <a href="https://www.tomorrowmakers.com/financial-planning/how-unified-payment-interface-upi-can-change-way-you-bank-expert-article?utm_source=ET&amp;utm_medium=Microsite_Articlebody&amp;utm_campaign=Related" data-type="tilCustomLink">How Unified Payment Interface (UPI) can change the way you bank </a>
        </div>';

        return response()->json([
            'message' => 'About Content.',
            'data' => $about,
        ]);
    }
}
