<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index()
    {
        $title = trans('messages.cards');
        $cards = Card::where('user_id', auth()->user()->id)->get();
        return view('cards.index', compact('cards', 'title'));
    }

    public function create()
    {
        $title = trans('messages.cards');
        return view('cards.create', compact('title'));
    }

    public function store(Request $request)
    {        
        Card::create([
            'user_id' => auth()->user()->id,
            'type' => $request->type,
            'number' => $request->number,
            'cvc' => $request->cvc,
            'expiry_date' => $request->expiry_date,
        ]);

        $this->helper->one_time_message('success', trans('messages.success.card_created_successfully'));

        return redirect('/cards');
    }

    public function edit($id)
    {
        $title = trans('messages.cards');
        $card = Card::find($id);
        return view('cards.edit', compact('card', 'title'));
    }

    public function update(Request $request, $id)
    {
        $card = Card::find($id);

        $card->update([
            'type' => $request->type,
            'number' => $request->number,
            'cvc' => $request->cvc,
            'expiry_date' => $request->expiry_date,
        ]);

        $this->helper->one_time_message('success', trans('messages.success.card_updated_successfully'));

        return redirect('/cards');
    }

    public function destroy($id)
    {
        Card::find($id)->destroy();
        $this->helper->one_time_message('success', trans('messages.success.card_deleted_successfully'));
        return redirect('cards');
    }
}
