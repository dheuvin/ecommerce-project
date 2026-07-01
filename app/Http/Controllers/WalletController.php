<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => Auth::id(),
            ]);
        $transaction = WalletTransaction::where('user_id', Auth::id())->get();

        return view('wallet.index', compact('wallet', 'transaction'));

    }

    public function addMoneyForm()
    {
        return view('wallet.add-money');
    }

    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $userId = Auth::id();

        // Wallet create or get
        $wallet = Wallet::firstOrCreate([
            'user_id' => $userId,
        ]);

        // Update balance
        $wallet->balance += $request->amount;
        $wallet->save();

        // Save transaction
        WalletTransaction::create([
            'user_id' => $userId,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('wallet.index')->with('success', 'Money added successfully!');
    }

    public function refund($userId, $orderId, $amount)
    {
        // validation
        if ($amount <= 0) {
            return response()->json([
                'message' => 'Invalid amount',
            ], 400);
        }

        // get or create wallet
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0]
        );

        // update balance
        $wallet->balance += $amount;
        $wallet->save();

        // transaction
        WalletTransaction::create([
            'user_id' => $userId,
            'type' => 'credit',
            'amount' => $amount,
            'description' => "Refund for Order #$orderId",
            'order_id' => $orderId,
        ]);

        return response()->json([
            'message' => 'Refund added successfully',
        ]);
    }

    public function debitWallet($amount, $description = 'Wallet Debit')
    {
        $userId = Auth::id();

        // Get or create wallet
        $wallet = Wallet::firstOrCreate([
            'user_id' => $userId,
        ]);

        // Check balance
        if ($wallet->balance < $amount) {
            return false; // insufficient balance
        }

        // Deduct balance
        $wallet->balance -= $amount;
        $wallet->save();

        // Save transaction
        WalletTransaction::create([
            'user_id' => $userId,
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
        ]);

        return true; // success
    }

    public function balance()
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );

        return response()->json([
            'balance' => $wallet->balance,
        ]);
    }

    public function transactions()
    {
        return WalletTransaction::where('user_id', auth()->id())
            ->latest()
            ->get();
    }
}
