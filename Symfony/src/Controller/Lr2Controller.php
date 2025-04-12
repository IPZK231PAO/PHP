<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    private static $items = []; 

    public function index()
    {
        return response()->json(['data' => self::$items], Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $item = $this->findItemById($id);
        
        if (!$item) {
            return response()->json(
                ['error' => "Item not found with id $id"], 
                Response::HTTP_NOT_FOUND
            );
        }
        
        return response()->json(['data' => $item], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'value' => 'required|numeric'
        ]);

        $newItem = [
            'id' => uniqid(),
            'title' => $validatedData['title'],
            'category' => $validatedData['category'],
            'value' => $validatedData['value']
        ];
        
        self::$items[] = $newItem;
        
        return response()->json(['data' => $newItem], Response::HTTP_CREATED);
    }

    public function update(Request $request, string $id)
    {
        $item = $this->findItemById($id);
        
        if (!$item) {
            return response()->json(
                ['error' => "Item not found with id $id"], 
                Response::HTTP_NOT_FOUND
            );
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'value' => 'sometimes|numeric'
        ]);

        foreach ($validatedData as $key => $value) {
            $item[$key] = $value;
        }

        foreach (self::$items as &$storedItem) {
            if ($storedItem['id'] === $id) {
                $storedItem = $item;
                break;
            }
        }

        return response()->json(['data' => $item], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $found = false;
        
        foreach (self::$items as $key => $item) {
            if ($item['id'] === $id) {
                unset(self::$items[$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(
                ['error' => "Item not found with id $id"], 
                Response::HTTP_NOT_FOUND
            );
        }

        self::$items = array_values(self::$items);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function findItemById(string $id): ?array
    {
        foreach (self::$items as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }
}