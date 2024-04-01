<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show()
    {
        $customer = Customer::all();
        return view('Customer.customer', compact('customer'));
    }
    public function filter(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);
        $start = $request->start;
        $end = $request->end;
        $customer = Customer::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('Customer.customer', compact('customer', 'start', 'end'));
    }
    public function destroy(Request $request, $id)
    {
        // Customer::destroy($id);
        // return response()->json(['message' => 'Status updated successfully']);
        // Assuming 'Service' is your model representing services
        $user = Customer::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function changeAccountStatus(Request $request)
    {
        $customerId = $request->input('customer_id');
        $newStatus = $request->input('new_status');
        $remark = $request->input('remark');
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        }
        $customer->account_status = $newStatus;
        if ($newStatus == 0 && !empty($remark)) {
            $customer->deactivation_remark = $remark;
            $customer->deactivated_at = Carbon::now();
        }else {
            $customer->deactivated_at = null;
            $customer->deactivation_remark = null;
        }
        $customer->save();
        return response()->json(['success' => true, 'message' => 'Account status updated successfully']);
    }
    public function family(){
        return view('admin.all_family');
    }
}
