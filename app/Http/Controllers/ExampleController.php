<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payments;

class ExampleController extends Controller
{
   
      /**
     * @OA\Get(
     *     path="/admin/bluestar-payment-portal/test",
     *     tags={"Test API"},
     *     @OA\Response(
     *         response="200",
     *         description="Testing it works",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    
    public function getThings(Request $request)
    {
        
        // ...

        return 'Api Testing it works';
    }

   
    public function getSampleData(Request $request)
    {
        $payment = Payments::where('payment_id', $request->payment_id)->first();
        return $payment;
    }


    public function getSampleSave()
    {
        $payment = Payments::create([
            'payment_id' => 1,
            'user_id' => 5, // since its a consultants service
            'status' => 1,
        ]);
        return $payment;
    }
    //
}
