<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Http\Controllers\Controller;
use App\Models\Publicite;
use Illuminate\View\View;

class PubliciteController extends Controller
{
    public function show(Publicite $publicite): View
    {
        return view('apprenant.publicites.show', compact('publicite'));
    }
}
