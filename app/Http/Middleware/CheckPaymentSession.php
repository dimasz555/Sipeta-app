<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPaymentSession
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session sudah expired
        if (session()->has('current_order_id')) {
            $sessionTime = session('payment_session_time', 0);
            if (time() - $sessionTime > 900) { 
                $pembelianId = session('pembelian_id');
                session()->forget(['current_order_id', 'snap_token', 'cicilan_id', 'payment_session_time']);
                return redirect()->route('pembayaran.kavling.detail', ['id' => $pembelianId])
                    ->with('error', 'Sesi Pembayaran Telah Berakhir. Silakan Coba Lagi.');
            }
        } else {
            session(['payment_session_time' => time()]);
        }

        return $next($request);
    }
}
