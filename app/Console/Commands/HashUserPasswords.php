<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Hash tất cả mật khẩu người dùng trong database';

    public function handle()
    {
        $this->info('Đang hash mật khẩu người dùng...');

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if (!empty($user->MK)) {
                $passwordInfo = password_get_info($user->MK_hash ?? '');

                if ($passwordInfo['algo'] === null || $passwordInfo['algo'] === 0) {
                    $user->MK_hash = Hash::make($user->MK);
                    $user->save();
                    $this->info("✓ Đã hash password cho: {$user->Email}");
                    $count++;
                } else {
                    $this->line("- Password của {$user->Email} đã được hash rồi");
                }
            }
        }

        $this->info("\n✓ Hoàn thành! Đã hash {$count} passwords.");
        return 0;
    }
}

