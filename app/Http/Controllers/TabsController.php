<?php

namespace App\Http\Controllers;

use App\Http\Requests\TabsPatchRequest;
use App\Http\Requests\TabsPostRequest;
use App\Http\Services\TabServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TabsController extends Controller
{

    private TabServices $tabServices;
 
    public function __construct(TabServices $tabServices)
    {
        $this -> tabServices = $tabServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user_id = $request -> user() -> id;
        return $this -> tabServices -> index($user_id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TabsPostRequest $request): JsonResponse
    {
        $user_id = $request -> user() -> id;
        $validatedRequest = $request -> validated();

        return $this -> tabServices -> store($user_id, $validatedRequest);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request): JsonResponse
    {
        $user_id = $request -> user() -> id;
        return $this -> tabServices -> show($user_id, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TabsPatchRequest $request, string $id): JsonResponse
    {
        $user_id = $request -> user() -> id;
        $validatedRequest = $request -> validated();

        return $this -> tabServices -> update($user_id, $validatedRequest, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request): JsonResponse
    {
        $user_id = $request -> user() -> id;
        return $this -> tabServices -> removeNode($user_id, $id);
    }

   /* Drop data from "tabs" table and generate testing data */
    // public function refactorTabs(): JsonResponse {
    //     return $this -> tabServices -> refactorTabs_test();
    // }
}
