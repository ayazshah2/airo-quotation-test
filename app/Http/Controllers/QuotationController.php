<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Quotation;
use Carbon\Carbon;

class QuotationController extends Controller
{
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|string',
            'currency_id' => 'required|in:EUR,GBP,USD',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ages = explode(',', $request->age);
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $tripLength = $start->diffInDays($end) + 1;

        $fixedRate = 3;
        $total = 0;

        foreach ($ages as $age) {
            $age = (int) trim($age);
            $load = $this->getAgeLoad($age);
            $total += $fixedRate * $load * $tripLength;
        }

        $quotation = Quotation::create([
            'ages' => json_encode($ages),
            'currency_id' => $request->currency_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total' => $total,
        ]);

        return response()->json([
            'total' => number_format($total, 2, '.', ''),
            'currency_id' => $quotation->currency_id,
            'quotation_id' => $quotation->id,
        ]);
    }

    private function getAgeLoad($age)
    {
        return match (true) {
            $age >= 18 && $age <= 30 => 0.6,
            $age >= 31 && $age <= 40 => 0.7,
            $age >= 41 && $age <= 50 => 0.8,
            $age >= 51 && $age <= 60 => 0.9,
            $age >= 61 && $age <= 70 => 1.0,
            default => 0,
        };
    }
}
