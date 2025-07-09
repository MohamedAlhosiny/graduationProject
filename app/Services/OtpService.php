<?php

// app/Services/OtpService.php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendOtpNotification;

class OtpService
{
    public function sendOtp(User $user): string
    {
        $otp = (string) random_int(10000, 99999);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(30),
        ]);

        // send via notification
        $user->notify(new SendOtpNotification($otp));

        return $otp;
    }

    public function validateOtp(User $user, string $code): bool
    {
        return $user->otp_code === $code
            && $user->otp_expires_at
            && Carbon::now()->lt($user->otp_expires_at);
    }

    public function clearOtp(User $user): void
    {
        $user->update([
            'otp_code'       => null,
            'otp_expires_at' => null,]);
}
}
