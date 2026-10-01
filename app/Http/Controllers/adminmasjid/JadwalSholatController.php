<?php

namespace App\Http\Controllers\adminmasjid;

use App\Http\Controllers\Controller;
use App\Models\EidPrayer;
use App\Models\Mosque;
use App\Models\PrayerImam;
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

    private const PRAYERS = [
        'subuh' => 'Subuh', 'dzuhur' => 'Dzuhur', 'ashar' => 'Ashar',
        'maghrib' => 'Maghrib', 'isya' => 'Isya',
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

            // Imam per shalat & jadwal Idul Fitri
            'imams'           => PrayerImam::where('mosque_id', $mosque->id)->pluck('imam_name', 'prayer')->all(),
            'prayerLabels'    => self::PRAYERS,
            'eidPrayers'      => EidPrayer::where('mosque_id', $mosque->id)
                                    ->orderByDesc('event_date')->orderBy('prayer_time')->get(),

            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'months'          => self::MONTHS,
            'yearOptions'     => range($now->year - 1, $now->year + 2),

            'timezone'        => $timezone,
            'timezoneLabel'   => $prayers->timezoneLabel($timezone),
            'today'           => $now->toDateString(),

            'startOffset'     => $current->dayOfWeek, // 0 = Ahad
            'isCurrentPeriod' => $bulan === $now->format('m') && $tahun === $now->year,
            'prevUrl'         => $this->periodUrl($current->copy()->subMonth(), $now),
            'nextUrl'         => $this->periodUrl($current->copy()->addMonth(), $now),
            'currentUrl'      => route('admin.jadwal-sholat'),
        ]);
    }

    /**
     * Simpan imam untuk setiap shalat (nama dikosongkan = imam dihapus).
     */
    public function update(Request $request)
    {
        $mosque = $this->currentMosque($request);

        $data = $request->validate([
            'imam'   => ['nullable', 'array'],
            'imam.*' => ['nullable', 'string', 'max:100'],
        ]);

        foreach (array_keys(self::PRAYERS) as $key) {
            $name = trim((string) ($data['imam'][$key] ?? ''));

            if ($name === '') {
                PrayerImam::where('mosque_id', $mosque->id)->where('prayer', $key)->delete();
                continue;
            }

            PrayerImam::updateOrCreate(
                ['mosque_id' => $mosque->id, 'prayer' => $key],
                ['imam_name' => $name]
            );
        }

        return back()->with('success', 'Imam setiap shalat berhasil disimpan.');
    }

    public function storeEid(Request $request)
    {
        $mosque = $this->currentMosque($request);

        EidPrayer::create($this->validateEid($request) + ['mosque_id' => $mosque->id]);

        return back()->with('success', 'Jadwal shalat Idul Fitri berhasil ditambahkan.');
    }

    public function updateEid(Request $request, EidPrayer $eid)
    {
        $this->authorizeEid($request, $eid);

        $eid->update($this->validateEid($request));

        return back()->with('success', 'Jadwal shalat Idul Fitri berhasil diperbarui.');
    }

    public function destroyEid(Request $request, EidPrayer $eid)
    {
        $this->authorizeEid($request, $eid);

        $eid->delete();

        return back()->with('success', 'Jadwal shalat Idul Fitri berhasil dihapus.');
    }

    private function validateEid(Request $request): array
    {
        return $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'event_date'  => ['required', 'date'],
            'prayer_time' => ['required', 'date_format:H:i'],
            'location'    => ['nullable', 'string', 'max:150'],
            'imam_name'   => ['nullable', 'string', 'max:100'],
            'khatib_name' => ['nullable', 'string', 'max:100'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function currentMosque(Request $request): Mosque
    {
        return Mosque::where('user_id', $request->user()->id)->firstOrFail();
    }

    /** Pastikan jadwal Idul Fitri ini milik masjid admin yang sedang login. */
    private function authorizeEid(Request $request, EidPrayer $eid): void
    {
        abort_unless((int) $eid->mosque_id === (int) $this->currentMosque($request)->id, 403);
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