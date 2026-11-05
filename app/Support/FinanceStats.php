<?php

namespace App\Support;

use App\Models\Mosque;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ringkasan keuangan PLATFORM untuk halaman Manajemen Masjid (Super Admin).
 *
 * Hanya menghitung pendapatan platform dari langganan masjid (tabel subscriptions).
 * Donasi milik masjid TIDAK dihitung di sini.
 *
 * Jika tabel/kolom tidak ditemukan, halaman tidak error: kartu menampilkan "-"
 * beserta alasannya.
 */
class FinanceStats
{
    // ===== SESUAIKAN DENGAN DATABASE ANDA =====
    private const SUB_TABLE           = 'subscriptions';
    private const SUB_PAID_STATUS     = ['paid', 'approved'];
    private const SUB_PENDING_STATUS  = ['pending'];
    private const SUB_AMOUNT_COLUMNS  = ['amount', 'gross_amount', 'total', 'price'];
    private const SUB_DATE_COLUMNS    = ['paid_at', 'created_at'];            // dipakai berurutan (COALESCE)
    private const SUB_PACKAGE_COLUMNS = ['package_type', 'package', 'paket', 'plan'];
    private const SUB_METHOD_COLUMNS  = ['payment_method', 'payment_type', 'method', 'metode'];
    private const SUB_REF_COLUMNS     = ['order_id', 'invoice', 'invoice_number', 'reference', 'transaction_id'];
    private const SUB_EXPIRED_COLUMN  = 'expired_date';   // sudah dipakai di Blade Anda
    // ==========================================

    public static function build($masjids = null): array
    {
        return [
            'berbayar' => Mosque::whereNotNull('package_type')
                ->where('package_type', '!=', 'free')
                ->where(function ($q) {
                    $q->whereNull('subscription_expires_at')
                      ->orWhere('subscription_expires_at', '>', now());
                })
                ->count(),
            'gratis'   => Mosque::where(function ($q) {
                    $q->whereNull('package_type')->orWhere('package_type', 'free');
                })->count(),
            'sub'      => array_merge([
                'ok' => false, 'reason' => null,
                'total' => 0.0, 'bulan_ini' => 0.0, 'bulan_lalu' => 0.0,
                'count' => 0, 'pending' => 0,
                'per_mosque' => [], 'per_mosque_count' => [], 'per_paket' => [],
            ], self::langganan()),
        ];
    }

    public static function paidStatuses(): array    { return self::SUB_PAID_STATUS; }
    public static function pendingStatuses(): array { return self::SUB_PENDING_STATUS; }

    /**
     * Daftar rincian transaksi langganan (semua status), terbaru dulu.
     * Dipakai untuk tabel "Rincian Pembayaran Langganan".
     */
    public static function transaksi(int $limit = 500): array
    {
        $out = [
            'ok' => false, 'reason' => null, 'rows' => [], 'total' => 0, 'truncated' => false,
            'has' => ['paket' => false, 'method' => false, 'ref' => false, 'berlaku' => false],
        ];

        try {
            $t  = self::SUB_TABLE;
            $mt = (new Mosque)->getTable();

            if (!Schema::hasTable($t)) {
                $out['reason'] = "Tabel {$t} tidak ditemukan";
                return $out;
            }
            if (!Schema::hasColumn($t, 'mosque_id') || !Schema::hasColumn($t, 'status')) {
                $out['reason'] = "Kolom mosque_id/status tidak ada di {$t}";
                return $out;
            }
            $amount = self::firstColumn($t, self::SUB_AMOUNT_COLUMNS);
            if (!$amount) {
                $out['reason'] = 'Kolom nominal tidak ditemukan di ' . $t;
                return $out;
            }

            $grammar = DB::getQueryGrammar();
            $pkg     = self::firstColumn($t, self::SUB_PACKAGE_COLUMNS);
            $method  = self::firstColumn($t, self::SUB_METHOD_COLUMNS);
            $ref     = self::firstColumn($t, self::SUB_REF_COLUMNS);
            $berlaku = Schema::hasColumn($t, self::SUB_EXPIRED_COLUMN) ? self::SUB_EXPIRED_COLUMN : null;

            $q = DB::table("{$t} as s")
                ->leftJoin("{$mt} as m", 'm.id', '=', 's.mosque_id')
                ->select('s.id as id', 's.mosque_id as mosque_id', 's.status as status',
                         "s.{$amount} as amount", 'm.mosque_name as mosque_name', 'm.city as city');

            if ($pkg)     { $q->addSelect("s.{$pkg} as paket"); }
            if ($method)  { $q->addSelect("s.{$method} as metode"); }
            if ($ref)     { $q->addSelect("s.{$ref} as ref"); }
            if ($berlaku) { $q->addSelect("s.{$berlaku} as berlaku"); }

            $dateCols = array_values(array_filter(
                self::SUB_DATE_COLUMNS,
                fn ($c) => Schema::hasColumn($t, $c)
            ));
            if ($dateCols) {
                $expr = 'COALESCE(' . implode(', ', array_map(fn ($c) => $grammar->wrap("s.{$c}"), $dateCols)) . ')';
                $q->addSelect(DB::raw("{$expr} as tgl"))->orderByRaw("{$expr} DESC");
            }
            $q->orderByDesc('s.id');

            $out['total']     = DB::table($t)->count();
            $out['truncated'] = $out['total'] > $limit;

            $out['rows'] = $q->limit($limit)->get()->map(fn ($r) => [
                'id'          => $r->id,
                'mosque_id'   => $r->mosque_id,
                'mosque_name' => $r->mosque_name ?: '(masjid sudah dihapus)',
                'city'        => $r->city ?? '',
                'status'      => (string) $r->status,
                'amount'      => (float) $r->amount,
                'paket'       => $r->paket ?? null,
                'metode'      => $r->metode ?? null,
                'ref'         => $r->ref ?? null,
                'berlaku'     => $r->berlaku ?? null,
                'tgl'         => $r->tgl ?? null,
            ])->all();

            $out['has'] = [
                'paket' => (bool) $pkg, 'method' => (bool) $method,
                'ref'   => (bool) $ref, 'berlaku' => (bool) $berlaku,
            ];
            $out['ok'] = true;

            return $out;
        } catch (\Throwable $e) {
            report($e);
            $out['reason'] = 'Gagal membaca rincian transaksi';
            return $out;
        }
    }

    private static function langganan(): array
    {
        try {
            $t = self::SUB_TABLE;

            if (!Schema::hasTable($t)) {
                return ['reason' => "Tabel {$t} tidak ditemukan"];
            }
            if (!Schema::hasColumn($t, 'mosque_id') || !Schema::hasColumn($t, 'status')) {
                return ['reason' => "Kolom mosque_id/status tidak ada di {$t}"];
            }

            $amount = self::firstColumn($t, self::SUB_AMOUNT_COLUMNS);
            if (!$amount) {
                return ['reason' => 'Kolom nominal tidak ditemukan di ' . $t
                    . ' (dicoba: ' . implode(', ', self::SUB_AMOUNT_COLUMNS) . ')'];
            }

            $grammar = DB::getQueryGrammar();
            $amt     = $grammar->wrap($amount);
            $paid    = fn () => DB::table($t)->whereIn('status', self::SUB_PAID_STATUS);

            $result = [
                'ok'               => true,
                'total'            => (float) $paid()->sum($amount),
                'count'            => $paid()->count(),
                'pending'          => DB::table($t)->whereIn('status', self::SUB_PENDING_STATUS)->count(),
                'per_mosque'       => $paid()->selectRaw("mosque_id, SUM({$amt}) as t")
                    ->groupBy('mosque_id')->pluck('t', 'mosque_id')
                    ->map(fn ($v) => (float) $v)->all(),
                'per_mosque_count' => $paid()->selectRaw('mosque_id, COUNT(*) as c')
                    ->groupBy('mosque_id')->pluck('c', 'mosque_id')
                    ->map(fn ($v) => (int) $v)->all(),
            ];

            // Tanggal bayar: COALESCE(paid_at, created_at) agar baris tanpa paid_at tetap terhitung
            $dateCols = array_values(array_filter(
                self::SUB_DATE_COLUMNS,
                fn ($c) => Schema::hasColumn($t, $c)
            ));
            if ($dateCols) {
                $dateExpr = 'COALESCE(' . implode(', ', array_map([$grammar, 'wrap'], $dateCols)) . ')';

                $result['bulan_ini'] = (float) $paid()
                    ->whereRaw("{$dateExpr} >= ? AND {$dateExpr} <= ?", [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum($amount);

                $result['bulan_lalu'] = (float) $paid()
                    ->whereRaw("{$dateExpr} >= ? AND {$dateExpr} <= ?", [
                        now()->subMonthNoOverflow()->startOfMonth(),
                        now()->subMonthNoOverflow()->endOfMonth(),
                    ])
                    ->sum($amount);
            }

            // Rincian per paket (jika kolom paket ada di tabel subscriptions)
            $pkg = self::firstColumn($t, self::SUB_PACKAGE_COLUMNS);
            if ($pkg) {
                $p = $grammar->wrap($pkg);
                $result['per_paket'] = $paid()
                    ->selectRaw("{$p} as paket, SUM({$amt}) as t, COUNT(*) as c")
                    ->groupBy($pkg)
                    ->orderByDesc('t')
                    ->get()
                    ->map(fn ($r) => [
                        'paket' => (string) $r->paket,
                        'total' => (float) $r->t,
                        'count' => (int) $r->c,
                    ])->all();
            }

            return $result;
        } catch (\Throwable $e) {
            report($e);
            return ['reason' => 'Gagal membaca data langganan'];
        }
    }

    private static function firstColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }
        return null;
    }
}