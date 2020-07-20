<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NewContact;
use Illuminate\Support\Facades\Mail;
use App\Lead;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function contatti(){
        return view('contatti');
    }

    public function contattiStore(Request $request) {

        $dati = $request->all();
        $nuovo_lead = new Lead();
        $nuovo_lead->fill($dati);
        $nuovo_lead->save();
        Mail::to('admin@boolpress.com')->send(new NewContact($nuovo_lead));
        return redirect()->route('home');
    }
}
