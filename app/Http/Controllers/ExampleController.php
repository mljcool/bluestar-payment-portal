<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
   
      /**
     * @OA\Get(
     *     path="/test",
     *     operationId="/test",
     *     tags={"Test"},
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

        return 'sample';
    }
    //
}
