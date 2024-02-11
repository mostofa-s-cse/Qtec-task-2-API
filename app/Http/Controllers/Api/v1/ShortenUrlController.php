<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShortenUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ShortenUrlController extends Controller
{

    public function index(Request $request)
    {
        try {
            $data = ShortenUrl::all();
            return response()->json( [
                'success'=>true,
                'message'=>'All Data Get Successfully',
                'data'=>$data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */

     public function store( Request $request) {
        $validator = Validator::make( $request->all(), [
            'long_url' => 'required|url'
        ] );
        if ( $validator->fails() ) {
            return response()->json( [
                'success'=>false,
                'message'=> $validator->errors(),
            ], 400);
        }
        $longUrl = $request->input('long_url');
        $shortUrl = $this->generateShortUrl();

        $data = ShortenUrl::create( [
            'long_url' => $longUrl,
            'short_url' => $shortUrl,
            'click_count' => 0,
        ] );

        return response()->json( [
            'success'=>true,
            'message'=>'Successfully Created',
            'data'=>$data
        ], 200);
   }

    private function generateShortUrl()
    {
        return substr(md5(uniqid()), 0, 8);
    }



     public function redirectShortUrl($shortUrl)
    {
        try {
            $shortenedUrl = ShortenUrl::where('short_url', $shortUrl)->firstOrFail();

            $shortenedUrl->increment('click_count');
            return redirect($shortenedUrl->long_url);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'long_url' => 'required',
            'short_url' => 'required',
            'click_count' => 'required',
        ]);

        try {
            DB::table('shorten_urls')->where('id', $id)->update([
                'long_url' => $request->long_url,
                'short_url' => $request->short_url,
                'click_count' => $request->click_count,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success'=>true,
                'message'=>'Updated successfully',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::table('shorten_urls')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success'=>true,
                'message' => 'Deleted Successfully'
            ], 200);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
