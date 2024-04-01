<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Listeners\GenerateReferralCode;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\CustomerFamily;
use App\Models\VendorWishlist;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->merge(['dob' => Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')]);
            $validatedData = $request->validate([
                'name' => 'required|string',
                'gender' => 'nullable|in:male,female,other',
                'phone_number' => 'nullable|string',
                'email' => 'required|email|unique:customers,email',
                'marital_status' => 'nullable|string',
                'dob' => 'nullable|date',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'address' => 'nullable|string',
                'profile_pic' => 'nullable|image|max:2048',
                'password' => 'required|string|min:6',
                'pin_no' => 'required|string|min:4',
            ]);
            $validatedData['password'] = Hash::make($validatedData['password']);
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('profile_pics'), $fileName);
                $validatedData['profile_pic'] = 'profile_pics/' . $fileName;
            }
            $randomDigits = mt_rand(10000, 99999);
            $referralCode = 'SOLU' . $randomDigits;
            $CustomerId = 'CUST' . $randomDigits;
            $validatedData['refer_code'] = $referralCode;
            $validatedData['customers_id'] = $CustomerId;
            $customer = Customer::create($validatedData);
            if ($request->has('from_referral_number') && !empty($request->from_referral_number)) {
                if ($this->isValidReferralNumber($request->from_referral_number)) {
                    $referralController = new ReferralController();
                    $referralUser = Customer::where('refer_code', $request->from_referral_number)->first();
                    // dd($referralUser);
                    $referralController->store($referralUser->id, $customer->id);
                } else {
                    return response()->json(['message' => 'Invalid referral number.', 'success' => false], 200);
                }
            }
            return response()->json(['message' => 'Customer registered successfully', 'data' => $customer], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_BAD_REQUEST);
        } catch (QueryException $e) {
            // return response()->json(['error' => 'Failed to register customer.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(Request $request)
    {
        try {
            $customer = Customer::findOrFail($request->id);
            $request->merge(['dob' => Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')]);
            $validatedData = $request->validate([
                'name' => 'string',
                'gender' => 'nullable|in:male,female,other',
                'phone_number' => 'nullable|string',
                'email' => 'email|unique:customers,email,' . $customer->id,
                'marital_status' => 'nullable|string',
                'dob' => 'nullable|date',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'address' => 'nullable|string',
                'profile_pic' => 'nullable|image|max:2048',
                'password' => 'nullable|string|min:6',
                'pin_no' => 'nullable|string|min:4',
            ]);
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('profile_pics'), $fileName);
                $validatedData['profile_pic'] = 'profile_pics/' . $fileName;
            }
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
            $customer->update($validatedData);
            return response()->json(['message' => 'Customer details updated successfully', 'data' => $customer]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_BAD_REQUEST);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');
            if (Auth::guard('customer')->attempt($credentials)) {
                $customer = Customer::where('email', $request->email)->firstOrFail();
                $token = $customer->createToken('CustomerToken')->plainTextToken;
                return response()->json(['token' => $token], 200);
            } else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user('customer')->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    public function document(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'documents_images.*' => 'required|file',
                'document_description' => 'required',
                'customer_id' => 'required|exists:customers,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $description = $request->document_description;
            $documents = [];
            foreach ($request->file('documents_images') as $file) {
                if ($file->isValid()) {
                    $fileName = $file->getClientOriginalName();
                    $file->move(public_path('customer_doc'), $fileName);
                    $document = new CustomerDocument();
                    $document->documents_images = 'customer_doc/' . $fileName;
                    $document->document_description = $description;
                    $document->customer_id = $request->customer_id;
                    $document->save();
                    $documents[] = $document;
                }
            }
            return response()->json(['message' => 'Documents Uploaded successfully', 'data' => $documents]);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function AddFamily(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'required|string|email|max:255|unique:customer_families',
                'address' => 'nullable|string',
                'marital_status' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'password' => 'nullable|string|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $request->merge(['dob' => Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d')]);
            // $customerId = Auth::id();
            $user = CustomerFamily::create([
                // 'customer_id' => $customerId,
                'customer_id' => $request->customer_id,
                'name' => $request->name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'marital_status' => $request->marital_status,
                'city' => $request->city,
                'state' => $request->state,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(['message' => 'Added successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed. Please try again later.'], 500);
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addToWishlist(Request $request)
    {
        try {
            $request->validate([
                'vendor_id' => 'required|exists:vendors,id',
            ]);
            $customerId = Auth::id();
            $wishlistItem = VendorWishlist::create([
                'customer_id' => $customerId,
                'vendor_id' => $request->vendor_id,
            ]);
            return response()->json(['message' => 'Vendor added to wishlist successfully', 'wishlistItem' => $wishlistItem], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add vendor to wishlist'], 500);
        }
    }
    private function isValidReferralNumber($referralNumber)
    {
        $referralUser = Customer::where('refer_code', $referralNumber)->first();

        if ($referralUser) {
            return true;
        } else {
            return false;
        }
    }
}
