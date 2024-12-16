<?php

namespace App\Http\Controllers;

use App\Mail\MailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request)
    {

        $mailData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        Mail::to($request->email)->send(new MailModel($mailData));

        return redirect()->route('home');
    }
}
