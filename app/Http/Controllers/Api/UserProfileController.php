<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningRecord;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'status' => 'sometimes|string|in:学習中,学習完了,休止中',
        ]);

        $user->update($validated);

        return response()->json($user->fresh());
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $user = $request->user();

        // 旧アバターを削除
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $ext  = $request->file('avatar')->getClientOriginalExtension();
        $path = $request->file('avatar')->storeAs(
            'avatars',
            "user_{$user->id}_" . time() . ".{$ext}",
            'public'
        );

        $user->update(['avatar' => Storage::url($path)]);

        return response()->json($user->fresh());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json(['message' => '現在のパスワードが正しくありません'], 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'パスワードを変更しました']);
    }

    public function resetHistory(Request $request)
    {
        $userId = $request->user()->id;

        LearningRecord::where('user_id', $userId)->delete();
        QuizSession::where('user_id', $userId)->delete();

        return response()->json(['message' => '学習履歴をリセットしました']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'パスワードが正しくありません'], 422);
        }

        // アバター画像を削除
        if ($user->avatar) {
            $path = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($path);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => '退会しました']);
    }
}
