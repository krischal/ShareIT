<?php

namespace App\Http\Controllers;

use App\SharedData;
use Illuminate\Http\Request;

/**
 * Class SharedDataController
 * @package App\Http\Controllers
 */
class SharedDataController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shareddata');
    }

    /**
     * Returns shared data as json response
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareddatas(Request $request)
    {
        return response()->json(SharedData::orderBy('created_at', 'DESC')->get());
    }

    /**
     * Creates and stores shareditem to database.
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /**
         * Valiate the name
         */
        $this->validate($request, [
            'description' => [ 'required']
        ]);

        $sharedData = new SharedData();
        $sharedData->description = $request->description;
        $sharedData->save();

        return $sharedData;
    }

    public function destroy(Request $request, SharedData $sharedDataItem)
    {
        $sharedDataItem->delete();
        return response()->json(null, 204);
    }
}
