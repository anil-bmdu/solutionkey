<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VendorController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'highest_qualification' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'area_of_interest' => 'nullable|string|max:255',
                'whatsapp_number' => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:255',
                'experience' => 'nullable|string|max:255',
                'current_job' => 'nullable|string|max:255',
                'charge_per_minute_for_audio_call' => 'nullable|numeric',
                'charge_per_minute_for_video_call' => 'nullable|numeric',
                'charge_per_minute_for_chat' => 'nullable|numeric',
                'adhar_number' => 'nullable|string|max:255',
                'pancard' => 'nullable|string|max:255',
                'about' => 'nullable|string',
                'profile_picture' => 'nullable|image|max:2048',
                'cover_picture' => 'nullable|image|max:2048',
                'password' => 'required|string|min:8',
            ]);
            $profilePicturePath = null;
            $coverPicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                $profilePicturePath = $profilePicture->getClientOriginalName();
                $profilePicture->move(public_path('vendor_profile_pic'), $profilePicturePath);
                $profilePicturePath = 'vendor_profile_pic' . '/' . $profilePicturePath;
            }
            if ($request->hasFile('cover_picture')) {
                $coverPicture = $request->file('cover_picture');
                $coverPicturePath = $coverPicture->getClientOriginalName();
                $coverPicture->move(public_path('vendor_cover_pic'), $coverPicturePath);
                $coverPicturePath = 'vendor_cover_pic' . '/' . $coverPicturePath;
            }
            $randomDigits = mt_rand(10000, 99999);
            $vendorid = 'VEND' . $randomDigits;
            $vendor = new Vendor();
            $vendor->fill($request->all());
            $vendor->password = Hash::make($request->input('password'));
            $vendor->profile_picture = $profilePicturePath;
            $vendor->cover_picture = $coverPicturePath;
            $vendor->vendor_id = $vendorid;
            $vendor->save();
            return response()->json(['message' => 'Vendor registered successfully', 'data' => $vendor], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to register vendor', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $vendor = Vendor::findOrFail($request->id);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'highest_qualification' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'area_of_interest' => 'nullable|string|max:255',
                'whatsapp_number' => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:255',
                'experience' => 'nullable|string|max:255',
                'current_job' => 'nullable|string|max:255',
                'charge_per_minute_for_audio_call' => 'nullable|numeric',
                'charge_per_minute_for_video_call' => 'nullable|numeric',
                'charge_per_minute_for_chat' => 'nullable|numeric',
                'adhar_number' => 'nullable|string|max:255',
                'pancard' => 'nullable|string|max:255',
                'about' => 'nullable|string',
                'status' => 'nullable|string|max:255',
                'profile_picture' => 'nullable|image|max:2048',
                'cover_picture' => 'nullable|image|max:2048',
            ]);
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                $profilePicturePath = $profilePicture->getClientOriginalName();
                $profilePicture->move(public_path('vendor_profile_pic'), $profilePicturePath);
                $validatedData['profile_picture'] = 'vendor_profile_pic/' . $profilePicturePath;
            }
            if ($request->hasFile('cover_picture')) {
                $coverPicture = $request->file('cover_picture');
                $coverPicturePath = $coverPicture->getClientOriginalName();
                $coverPicture->move(public_path('vendor_cover_pic'), $coverPicturePath);
                $validatedData['cover_picture'] = 'vendor_cover_pic/' . $coverPicturePath;
            }
            $vendor->update($validatedData);
            return response()->json(['message' => 'Vendor details updated successfully', 'data' => $vendor], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update vendor details', 'error' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('vendor')->attempt($credentials)) {
            $vendor = Auth::guard('vendor')->user();
            $token = $vendor->createToken('VendorAppToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user('vendor')->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8',
                'confirm_password' => 'required|string|same:new_password',
            ]);
            $vendor = auth('vendor')->user();
            if (!Hash::check($request->current_password, $vendor->password)) {
                return response()->json(['message' => 'The provided current password is incorrect'], 422);
            }
            $vendor->password = Hash::make($request->new_password);
            $vendor->save();
            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to change password', 'error' => $e->getMessage()], 500);
        }
    }

}
