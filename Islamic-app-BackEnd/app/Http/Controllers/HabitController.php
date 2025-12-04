<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Models\Habit;
use Illuminate\Http\JsonResponse;

class HabitController extends Controller
{
    public function store(StoreHabitRequest $request): JsonResponse
    {
        $habit = Habit::create($request->validated());

        return response()->json([
            'message' => 'Habit created successfully',
            'data'    => $habit,
        ], 201);
    }

    // (اختياري) لو بدك باقي الدوال كلها JSON:
    public function index(): JsonResponse
    {
        return response()->json(Habit::all());
    }

    public function show(Habit $habit): JsonResponse
    {
        return response()->json($habit);
    }

    public function update(StoreHabitRequest $request, Habit $habit): JsonResponse
    {
        $habit->update($request->validated());

        return response()->json([
            'message' => 'Habit updated successfully',
            'data'    => $habit,
        ]);
    }

    public function destroy(Habit $habit): JsonResponse
    {
        $habit->delete();

        return response()->json([
            'message' => 'Habit deleted successfully',
        ], 200);
    }
}
