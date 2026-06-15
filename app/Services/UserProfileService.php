<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\LearningRecordRepositoryInterface;
use App\Repositories\Interfaces\QuizSessionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileService
{
    public function __construct(
        private UserRepositoryInterface           $userRepository,
        private LearningRecordRepositoryInterface $recordRepository,
        private QuizSessionRepositoryInterface    $sessionRepository
    ) {}

    public function updateProfile(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    public function updateAvatar(User $user, UploadedFile $file): User
    {
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $ext  = $file->getClientOriginalExtension();
        $path = $file->storeAs(
            'avatars',
            "user_{$user->id}_" . time() . ".{$ext}",
            'public'
        );

        return $this->userRepository->updateAvatar($user, Storage::url($path));
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            abort(422, '現在のパスワードが正しくありません');
        }

        $this->userRepository->update($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function resetHistory(int $userId): void
    {
        $this->recordRepository->deleteByUser($userId);
        $this->sessionRepository->deleteByUser($userId);
    }

    public function destroy(User $user, string $password): void
    {
        if (!Hash::check($password, $user->password)) {
            abort(422, 'パスワードが正しくありません');
        }

        if ($user->avatar) {
            $path = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($path);
        }

        $this->userRepository->delete($user);
    }
}
