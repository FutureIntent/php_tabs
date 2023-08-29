<?php

namespace App\Http\Services;

use App\Models\Tabs;
use Exception;
use Illuminate\Http\JsonResponse;

class TabServices
{
   function index($user_id): JsonResponse {

      $tabs = Tabs::where('user_id', $user_id) -> get();

      return response() -> json($tabs, 200);
   }

   function store($user_id, $validatedRequest): JsonResponse {
      $newTab = new Tabs();

      $newTab -> header = $validatedRequest['header'];
      $newTab -> content = $validatedRequest['content'];
      $newTab -> parent_id = $validatedRequest['parent_id'];
      $newTab -> user_id = $user_id;
      $status = $newTab -> save();

      return response() -> json([
          'created' => $status
      ], 201);
   }

   function show($user_id, $id): JsonResponse {
      $tab = Tabs::where([
         ['user_id', $user_id],
         ['id', $id]
      ]) -> get();

      return response() -> json([
          'tab' => $tab
      ], 201);
   }

   function update($user_id, $validatedRequest, $id): JsonResponse {
      $tabToUpdate = Tabs::where([
         ['user_id', $user_id],
         ['id', $id]
      ]) -> first();

      if(!$tabToUpdate) throw new Exception('Wrong id', 400);

      foreach($validatedRequest as $key => $value) {
          $tabToUpdate -> {$key} = $value;
      }

      $status = $tabToUpdate -> save();

      return response() -> json([
          'updated' => $status
      ], 200);
   }

   /* Drop data from "tabs" table and generate testing data */
   function refactorTabs_test(): JsonResponse {
      Tabs::truncate();

      $tab_1 = new Tabs(['id' => 1, 'header' => 'zzz', 'content' => 'zxcvb', 'parent_id' => null, 'user_id' => 1]);
      $tab_2 = new Tabs(['id' => 2, 'header' => 'xxx', 'content' => 'xcvbn', 'parent_id' => 1, 'user_id' => 1]);
      $tab_3 = new Tabs(['id' => 3, 'header' => 'ccc', 'content' => 'cvbnm', 'parent_id' => 1, 'user_id' => 1]);
      $tab_4 = new Tabs(['id' => 4, 'header' => 'vvv', 'content' => 'vbnmn', 'parent_id' => 2, 'user_id' => 1]);
      $tab_5 = new Tabs(['id' => 5, 'header' => 'bbb', 'content' => 'bnmnb', 'parent_id' => 3, 'user_id' => 1]);
      $tab_6 = new Tabs(['id' => 6, 'header' => 'nnn', 'content' => 'nmnbv', 'parent_id' => 3, 'user_id' => 1]);
      $tab_7 = new Tabs(['id' => 7, 'header' => 'mmm', 'content' => 'mnbvc', 'parent_id' => 5, 'user_id' => 1]);

      $tab_1 -> save();
      $tab_2 -> save();
      $tab_3 -> save();
      $tab_4 -> save();
      $tab_5 -> save();
      $tab_6 -> save();
      $tab_7 -> save();

      return response() -> json(['status' => 'refactored'], 201);
   }

   function removeNode($user_id, $id): JsonResponse {
      $stack = array();
      $list = array();

      $tab = Tabs::where([
         ['user_id', $user_id],
         ['id', $id]
      ]) -> get() -> toArray();

      if(count($tab) === 0) throw new Exception('Wrong id', 400);

      $stack = array_merge($stack, $tab);

      while(count($stack) !== 0) {
         $current = array_pop($stack);
         $tabs = Tabs::where('parent_id', $current['id']) -> get() -> toArray();
         $stack = array_merge($stack, $tabs);

         array_push($list, $current);
      }

      $list_id = array_map(function ($tab) {
         return $tab['id'];
      }, $list);

      $status = Tabs::whereIn('id', $list_id) -> delete();

      return response() -> json(['# of removed records:' => $status], 200);
   }
}
