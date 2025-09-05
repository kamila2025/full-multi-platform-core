<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userService
{
    /**
     * 創建員工
     */
    public function createUser(array $attributes): User
    {
      try {
          DB::beginTransaction();

          $exists = User::where('email', $attributes['email'])->exists();

          if ($exists) throw new \Exception('員工信箱已存在');

          $user = User::create([
            'name'      => $attributes['name'],
            'email'     => $attributes['email'],
            'password'  => Hash::make($attributes['password']),
          ]);

          DB::commit();

          return $user;
      } catch (\Throwable $e) {
          DB::rollBack();

          throw $e;
      }
    }

    /**
     * 更新員工
     */
    public function updateUser($id, array $attributes): User
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $updateData = [
                'name'  => $attributes['name'],
                'email' => $attributes['email'],
            ];

            if (isset($attributes['password']) && !empty($attributes['password'])) {
                $updateData['password'] = Hash::make($attributes['password']);
            }

            $user->update($updateData);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * 刪除員工
     */
    public function deleteUser($id): void
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
