<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Market;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [];

        $markets = Market::query()->get()->groupBy('domain');

        foreach ($markets as $domain => $collection) {
            $originalName = fake()->name();

            $removeWords = [
                'mr', 'mrs', 'ms', 'miss', 'dr', 'prof', 'sir', 'madam',
                'jr', 'sr', 'ii', 'iii', 'iv', 'v',
            ];

            $name = strtolower(trim($originalName));
            $name = preg_replace('/[^a-z0-9\s\-]/', ' ', $name);
            $name = preg_replace('/\s+/', ' ', $name);

            $parts = explode(' ', $name);
            $filtered = array_diff($parts, $removeWords);

            $username = implode('.', $filtered);

            $domain = strtolower(trim($domain));
            $domain = preg_replace('/^https?:\/\//', '', $domain);
            $domain = preg_replace('/^www\./', '', $domain);
            $domain = preg_replace('/\/.*/', '', $domain);

            $username = preg_replace('/[^a-z0-9.]/', '', $username);
            $username = preg_replace('/\.{2,}/', '.', $username);
            $username = trim($username, '.');

            $users[] = [
                'data' => [
                    'name' => $originalName,
                    'email' => "{$username}@{$domain}",
                ],
                'ids' => $collection->pluck('id')->values()->toArray(),
            ];
        }

        $adminData = [
            'role' => UserRole::ADMIN->value,
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => $password,
        ];

        DB::beginTransaction();

        try {
            if (! User::query()->where('email', $adminData['email'])->exists()) {
                User::create($adminData);
            }

            foreach ($users as $user) {
                if (! User::query()->where('email', $user['data']['email'])->exists()) {
                    /** @var User $newUser */
                    $newUser = User::create([
                        ...$user['data'],
                        'password' => $password,
                    ]);

                    $newUser->markets()->sync($user['ids']);
                }
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
