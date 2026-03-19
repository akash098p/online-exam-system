<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;

class OptionController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'option_text'=>'required|string',
            'is_correct'=>'nullable|boolean'
        ]);

        $opt = $question->options()->create([
            'option_text' => $request->option_text,
            'is_correct' => $request->has('is_correct'),
        ]);

        return response()->json($opt);
    }

    public function update(Request $request, Option $option)
    {
        $request->validate([
            'option_text'=>'required|string',
            'is_correct'=>'nullable|boolean'
        ]);

        $option->update([
            'option_text' => $request->option_text,
            'is_correct' => $request->has('is_correct'),
        ]);

        // If this option is marked correct, ensure others for same question are false
        if ($option->is_correct) {
            $option->question->options()->where('id','!=',$option->id)->update(['is_correct'=>false]);
        }

        return response()->json(['status'=>'ok','option'=>$option]);
    }

    public function destroy(Option $option)
    {
        $option->delete();
        return response()->json(['status'=>'deleted']);
    }

    public function reorder(Request $request, Question $question)
    {
        // request order: [optionId, optionId...]
        $order = $request->input('order',[]);
        foreach($order as $idx => $optId){
            \DB::table('options')->where('id',$optId)->update(['order_index'=>$idx]);
        }
        return response()->json(['status'=>'ok']);
    }
}
