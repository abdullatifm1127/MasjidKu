<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Services\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalSholatController extends Controller
{
    private const MONTHS = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',    '04' => 'April',
        '05' => 'Mei',     '06' => 'Juni',     '07' => 'Juli',     '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    public function index(Request $request, PrayerTimeService $prayers)
    {
        $mosque = Mosque::where('user_id', $request->user()->id)->first();

        if (!$mosque) {
            return redirect()->route('daftar.masjid')->with('error', 'Anda belum mendaftarkan masjid.');
        }

        $timezone = $prayers->timezoneFor($mosque);
        $now      = Carbon::now($timezone);

        [$bulan, $tahun] = $prayers->resolvePeriod($request->query('bulan'), $request->query('tahun'), $timezone);

        $current = Carbon::create($tahun, (int) $bulan, 1, 0, 0, 0, $timezone);

        return view('auth.adminmasjid.jadwalSholat', [
            'mosque'          => $mosque,
            'jadwalShalat'    => $prayers->forMosque($mosque),
            'monthlySchedule' => $prayers->monthlyForMosque($mosque, $tahun, $bulan),

            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'months'          => self::MONTHS,
            'yearOptions'     => range($now->year - 1, $now->year + 2),

            'timezone'        => $timezone,
            'timezoneLabel'   => $prayers->timezoneLabel($timezone),
            'today'           => $now->toDateString(),

            // Data bantu kalender, supaya view tidak perlu memanggil Carbon.
            'startOffset'     => $current->dayOfWeek, // 0 = Ahad
            'isCurrentPeriod' => $bulan === $now->format('m') && $tahun === $now->year,
            'prevUrl'         => $this->periodUrl($current->copy()->subMonth(), $now),
            'nextUrl'         => $this->periodUrl($current->copy()->addMonth(), $now),
            'currentUrl'      => route('admin.jadwal-sholat'),
        ]);
    }

    public function update(Request $request)
    {
        // Logika update jadwal shalat jika diperlukan di masa mendatang...
        return back()->with('success', 'Pengaturan jadwal berhasil diperbarui.');
    }

    /**
     * URL bulan sebelum/sesudahnya; null bila di luar rentang yang diizinkan (tahun lalu s.d. 2 tahun ke depan).
     */
    private function periodUrl(Carbon $target, Carbon $now): ?string
    {
        if ($target->year < $now->year - 1 || $target->year > $now->year + 2) {
            return null;
        }

        return route('admin.jadwal-sholat', ['bulan' => $target->format('m'), 'tahun' => $target->year]);
    }
}