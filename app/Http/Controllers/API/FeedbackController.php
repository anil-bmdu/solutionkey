<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function feedbackDetail(Request $request)
    {
        $user_id = $request->user()->user_id;
        if ($request->has('type')) {
            if ($request->type == 2) {
                $feedback = Complaint::create([
                    'user_id' => $user_id,
                    'subject' => $request->subject,
                    'message' => $request->message,
                ]);
                if ($feedback) {
                    return response()->json(['message' => 'Your Complaint has been submitted successfully', 'status' => true], 200);
                } else {
                    return response()->json(['message' => 'failed', 'status' => false], 200);
                }
            }
            if ($request->type == 1) {
                $feedback = Feedback::create([
                    'user_id' => $user_id,
                    'subject' => $request->subject,
                    'message' => $request->message,
                ]);
                if ($feedback) {
                    return response()->json(['message' => 'Your feedback has been submitted successfully', 'status' => true], 200);
                } else {
                    return response()->json(['message' => 'failed', 'status' => false], 200);
                }
            }
        }
    }
    public function addfeedback(Request $request)
    {   
        // dd($request->all());
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);
        $feedback = Feedback::create([
            'customer_id' => $request->id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        if ($feedback) {
            return response()->json(['message' => 'Your Feedback has been submitted successfully', 'status' => true], 200);
        } else {
            return response()->json(['message' => 'failed', 'status' => false], 200);
        }
    }
    public function addcomplaint(Request $request)
    {   
        // dd($request->all());
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);
        $complaint = Complaint::create([
            'customer_id' => $request->id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        if ($complaint) {
            return response()->json(['message' => 'Your Complaint has been submitted successfully', 'status' => true], 200);
        } else {
            return response()->json(['message' => 'failed', 'status' => false], 200);
        }
    }
}
