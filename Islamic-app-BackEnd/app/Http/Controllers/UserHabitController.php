<?php

namespace App\Http\Controllers;

use App\Enums\HabitStatus;
use App\Http\Requests\StoreUserHabitRequest;
use App\Http\Requests\UpdateUserHabitStatusRequest;
use App\Models\Habit;
use App\Models\UserHabit;
use Illuminate\Http\JsonResponse;

class UserHabitController extends Controller
{
    /**
     * عرض عادات مستخدم معيّن
     */
    public function index(int $userId): JsonResponse
    {
        $habits = UserHabit::where('user_id', $userId)
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($habits);
    }

    /**
     * إضافة عادة للمستخدم:
     * - يرسل user_id
     * - habit_id من القوالب أو name لعادة جديدة
     * - يمكن إرسال duration_days و start_date أو تركها افتراضية
     */
    public function store(StoreUserHabitRequest $request): JsonResponse
    {
        $data = $request->validated();

        // لو أرسل habit_id نستخدم بيانات القالب
        if (! empty($data['habit_id'])) {
            /** @var Habit $habit */
            $habit = Habit::findOrFail($data['habit_id']);

            // اسم العادة من القالب لو لم يُرسل
            if (empty($data['name'])) {
                $data['name'] = $habit->name;
            }

            // مدة العادة من القالب لو لم تُرسل
            if (empty($data['duration_days'])) {
                $data['duration_days'] = $habit->default_duration_days;
            }
        }

        // لو لم يرسل start_date نخليها اليوم
        if (empty($data['start_date'])) {
            $data['start_date'] = now()->toDateString();
        }

        $userHabit = UserHabit::create($data);

        return response()->json($userHabit, 201);
    }

    /**
     * تحديث حالة العادة (مثلاً من in_progress إلى completed)
     * المستخدم يرسل: user_id + status
     */
    public function updateStatus(UpdateUserHabitStatusRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        $userHabit = UserHabit::where('id', $id)
            ->where('user_id', $data['user_id'])
            ->firstOrFail();

        if ($data['status'] === HabitStatus::COMPLETED->value) {
            $userHabit->completed_at = now();
        } elseif ($data['status'] === HabitStatus::IN_PROGRESS->value) {
            $userHabit->completed_at = null;
        }

        $userHabit->save();

        return response()->json($userHabit);
    }

    /**
     * حذف عادة مستخدم
     */
    public function destroy(int $userId, int $id): JsonResponse
    {
        $userHabit = UserHabit::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $userHabit->delete();

        return response()->json(null, 204);
    }
}
