<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    // request reset → generate & notify OTP
    public function requestReset(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $this->otpService->sendOtp($user);

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    // verify OTP
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'otp_code' => 'required|digits:5',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $this->otpService->validateOtp($user, $request->otp_code)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        return response()->json(['message' => 'OTP verified.'], 200);
    }

    //  reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'otp_code'              => 'required|digits:5',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $this->otpService->validateOtp($user, $request->otp_code)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        $this->otpService->clearOtp($user);

        return response()->json(['message' => 'Password reset successfully.'], 200);
}
}

