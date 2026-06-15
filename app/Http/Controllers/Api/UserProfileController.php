<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function __construct(private UserProfileService $service) {}

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'status' => 'sometimes|string|in:学習中,学習完了,休止中',
        ]);

        return response()->json($this->service->updateProfile($user, $data));
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $user = $this->service->updateAvatar($request->user(), $request->file('avatar'));

        return response()->json($user);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $this->service->changePassword(
            $request->user(),
            $request->current_password,
            $request->password
        );

        return response()->json(['message' => 'パスワードを変更しました']);
    }

    public function resetHistory(Request $request): JsonResponse
    {
        $this->service->resetHistory($request->user()->id);

        return response()->json(['message' => '学習履歴をリセットしました']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['password' => 'required']);

        $this->service->destroy($request->user(), $request->password);

        return response()->json(['message' => '退会しました']);
    }
}
