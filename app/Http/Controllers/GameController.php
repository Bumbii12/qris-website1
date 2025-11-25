<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Konfigurasi stage & level:
     * Stage 1–3, masing-masing 3 level.
     */
    private array $stages = [
        1 => [
            'name' => 'Dasar QRIS',
            'levels' => [
                1 => [
                    'type' => 'multiple_choice',
                    'title' => 'Pengertian QRIS',
                    'question' => 'Apa pengertian QRIS yang paling tepat?',
                    'options' => [
                        'A' => 'Standar nasional kode QR untuk pembayaran di Indonesia.',
                        'B' => 'Salah satu aplikasi dompet digital.',
                        'C' => 'Mesin EDC khusus pembayaran QR.',
                        'D' => 'Jenis kartu debit baru.',
                    ],
                    'correct' => 'A',
                    'points'  => [1 => 100, 2 => 50],
                ],
                2 => [
                    'type' => 'true_false',
                    'title' => 'Manfaat QRIS',
                    'question' => 'Tentukan benar/salah pernyataan tentang manfaat QRIS bagi pembeli.',
                    'statements' => [
                        'A' => [
                            'text'   => 'QRIS memudahkan pembayaran tanpa uang tunai.',
                            'answer' => true,
                        ],
                        'B' => [
                            'text'   => 'QRIS hanya bisa digunakan di kota besar.',
                            'answer' => false,
                        ],
                    ],
                    'points' => [1 => 120, 2 => 60],
                ],
                3 => [
                    'type' => 'order',
                    'title' => 'Langkah Dasar Membayar QRIS',
                    'question' => 'Urutkan langkah membayar dengan QRIS.',
                    'steps' => [
                        1 => 'Buka aplikasi pembayaran di HP.',
                        2 => 'Scan kode QRIS di kasir.',
                        3 => 'Masukkan nominal belanja.',
                        4 => 'Cek nama merchant dan konfirmasi.',
                    ],
                    'points' => [1 => 150, 2 => 75],
                ],
            ],
        ],
        2 => [
            'name' => 'Keamanan & Etika',
            'levels' => [
                1 => [
                    'type' => 'multiple_choice',
                    'title' => 'QRIS yang Aman',
                    'question' => 'Contoh penggunaan QRIS yang aman adalah...',
                    'options' => [
                        'A' => 'Scan QR yang dikirim orang asing via chat.',
                        'B' => 'Scan QRIS resmi yang ditempel di kasir dengan nama merchant jelas.',
                        'C' => 'Scan QR dari foto di status orang tanpa tahu sumbernya.',
                        'D' => 'Scan QR yang ditempel di tiang listrik tanpa identitas.',
                    ],
                    'correct' => 'B',
                    'points'  => [1 => 160, 2 => 80],
                ],
                2 => [
                    'type' => 'true_false',
                    'title' => 'Kewaspadaan Pengguna',
                    'question' => 'Tentukan benar/salah sikap pengguna QRIS berikut.',
                    'statements' => [
                        'A' => [
                            'text'   => 'Selalu cek nama merchant sebelum konfirmasi pembayaran.',
                            'answer' => true,
                        ],
                        'B' => [
                            'text'   => 'Aman-aman saja scan QR dari broadcast tanpa cek sumber.',
                            'answer' => false,
                        ],
                    ],
                    'points' => [1 => 170, 2 => 85],
                ],
                3 => [
                    'type' => 'order',
                    'title' => 'Menghindari Penipuan',
                    'question' => 'Urutkan langkah saat menemukan QR yang mencurigakan.',
                    'steps' => [
                        1 => 'Jangan langsung scan dan bayar.',
                        2 => 'Tanya ke kasir/petugas apakah QR tersebut resmi.',
                        3 => 'Gunakan hanya QR yang dipastikan resmi oleh kasir.',
                    ],
                    'points' => [1 => 180, 2 => 90],
                ],
            ],
        ],
        3 => [
            'name' => 'Penerapan Sehari-hari',
            'levels' => [
                1 => [
                    'type' => 'multiple_choice',
                    'title' => 'Studi Kasus Warung',
                    'question' => 'Bu Siti memasang QRIS di warungnya. Manfaat utamanya adalah...',
                    'options' => [
                        'A' => 'Harga barang otomatis jadi lebih mahal.',
                        'B' => 'Bisa menerima pembayaran non-tunai dari berbagai aplikasi.',
                        'C' => 'Hanya bisa melayani pelanggan luar kota.',
                        'D' => 'Tidak perlu lagi mencatat penjualan sama sekali.',
                    ],
                    'correct' => 'B',
                    'points'  => [1 => 190, 2 => 95],
                ],
                2 => [
                    'type' => 'true_false',
                    'title' => 'Kebiasaan Baik Pengguna',
                    'question' => 'Tentukan benar/salah kebiasaan pengguna berikut.',
                    'statements' => [
                        'A' => [
                            'text'   => 'Pengecekan ulang nominal sebelum bayar itu penting.',
                            'answer' => true,
                        ],
                        'B' => [
                            'text'   => 'QRIS hanya bermanfaat untuk usaha besar.',
                            'answer' => false,
                        ],
                    ],
                    'points' => [1 => 200, 2 => 100],
                ],
                3 => [
                    'type' => 'order',
                    'title' => 'Simulasi Belanja QRIS',
                    'question' => 'Urutkan langkah saat kamu belanja di kantin dan membayar dengan QRIS.',
                    'steps' => [
                        1 => 'Hitung total belanja bersama penjual.',
                        2 => 'Buka aplikasi pembayaran di HP.',
                        3 => 'Scan QRIS di kantin.',
                        4 => 'Masukkan nominal yang sudah disepakati.',
                        5 => 'Cek nama kantin/merchant dan konfirmasi bayar.',
                    ],
                    'points' => [1 => 210, 2 => 105],
                ],
            ],
        ],
    ];

    /* ========= HELPER KOIN ========= */

    private function getCoins(): int
    {
        return session('coins', 0);
    }

    private function setCoins(int $coins): void
    {
        session(['coins' => $coins]);
    }

    private function addCoins(int $amount): int
    {
        $coins = $this->getCoins() + $amount;
        $this->setCoins($coins);
        return $coins;
    }

    /* ========= HELPER ATTEMPT ========= */

    private function keyAttempt(int $stage, int $level): string
    {
        return "{$stage}-{$level}";
    }

    private function getAttempts(int $stage, int $level): int
    {
        $attempts = session('attempts', []);
        return $attempts[$this->keyAttempt($stage, $level)] ?? 0;
    }

    private function incrementAttempts(int $stage, int $level): int
    {
        $attempts = session('attempts', []);
        $key = $this->keyAttempt($stage, $level);
        $attempts[$key] = ($attempts[$key] ?? 0) + 1;
        session(['attempts' => $attempts]);
        return $attempts[$key];
    }

    private function resetAttempts(int $stage, int $level): void
    {
        $attempts = session('attempts', []);
        unset($attempts[$this->keyAttempt($stage, $level)]);
        session(['attempts' => $attempts]);
    }

    /* ========= PROGRESS (LEVEL TERBUKA, LEVEL AKTIF, LEVEL SELESAI) ========= */

    private function totalLevels(): int
    {
        $total = 0;
        foreach ($this->stages as $stage) {
            $total += count($stage['levels']);
        }
        return $total;
    }

    // kode level, contoh: 1-1 = 11, 1-2 = 12, 2-1 = 21, dst.
    private function levelCode(int $stage, int $level): int
    {
        return $stage * 10 + $level;
    }

    // alias: levelIndex (dipakai di beberapa tempat)
    private function levelIndex(int $stage, int $level): int
    {
        return $this->levelCode($stage, $level);
    }

    // kode level terakhir (misal 3-3 => 33)
    private function getLastLevelCode(): int
    {
        $lastStage = array_key_last($this->stages);
        $lastLevel = array_key_last($this->stages[$lastStage]['levels']);

        return $this->levelCode($lastStage, $lastLevel);
    }

    private function getMaxUnlocked(): int
    {
        // default: Stage 1 Level 1 = 11
        return session('max_unlocked', 11);
    }

    private function setMaxUnlocked(int $code): void
    {
        session(['max_unlocked' => $code]);
    }

    private function getCurrentCode(): int
    {
        return session('current_code', $this->getMaxUnlocked());
    }

    private function setCurrentCode(int $code): void
    {
        session(['current_code' => $code]);
    }

    // daftar level yang sudah selesai (tanpa koin dobel)
    private function getCompletedLevels(): array
    {
        return session('completed_levels', []);
    }

    private function addCompletedLevel(int $stage, int $level): void
    {
        $completed = $this->getCompletedLevels();
        $key = "{$stage}-{$level}";
        if (!in_array($key, $completed, true)) {
            $completed[] = $key;
            session(['completed_levels' => $completed]);
        }
    }

    // biar kompatibel kalau kamu masih panggil markCompleted di tempat lain
    private function markCompleted(int $stage, int $level): void
    {
        $this->addCompletedLevel($stage, $level);
    }

    private function isCompleted(int $stage, int $level): bool
    {
        $completed = $this->getCompletedLevels();
        return in_array("{$stage}-{$level}", $completed, true);
    }

    /**
     * Cari level berikutnya dari (stage, level) sekarang.
     * Return [stageBaru, levelBaru] atau null kalau sudah level terakhir.
     */
    private function getNextStageLevel(int $stage, int $level): ?array
    {
        // level berikutnya di stage yang sama
        if (isset($this->stages[$stage]['levels'][$level + 1])) {
            return [$stage, $level + 1];
        }

        // kalau habis, lompat ke stage berikutnya, level 1
        if (isset($this->stages[$stage + 1]['levels'][1])) {
            return [$stage + 1, 1];
        }

        // sudah level paling akhir
        return null;
    }

    private function getLevelConfigOrFail(int $stage, int $level): array
    {
        if (!isset($this->stages[$stage]['levels'][$level])) {
            abort(404);
        }
        return $this->stages[$stage]['levels'][$level];
    }

    /* ========= HALAMAN UTAMA & PAPAN ========= */

    public function home()
    {
        return view('home', [
            'coins'  => $this->getCoins(),
            'stages' => $this->stages,
        ]);
    }

    public function index()
    {
        return view('game.index', [
            'coins'           => $this->getCoins(),
            'stages'          => $this->stages,
            'maxUnlocked'     => $this->getMaxUnlocked(),
            'currentCode'     => $this->getCurrentCode(),
            'completedLevels' => $this->getCompletedLevels(),
        ]);
    }

    /* ========= HALAMAN LEVEL ========= */

    public function showLevel(int $stage, int $level)
    {
        $index = $this->levelIndex($stage, $level);

        // kalau belum terbuka, balikin ke papan
        if ($index > $this->getMaxUnlocked()) {
            return redirect()->route('game.index')->with([
                'status_type' => 'danger',
                'status_msg'  => 'Level ini belum terbuka. Selesaikan level sebelumnya dulu, ya!',
            ]);
        }

        // set current level
        $this->setCurrentCode($index);

        // cek apakah level ini sudah pernah diselesaikan
        $isCompleted = $this->isCompleted($stage, $level);

        return view('game.level', [
            'coins'       => $this->getCoins(),
            'stage'       => $stage,
            'level'       => $level,
            'config'      => $this->getLevelConfigOrFail($stage, $level),
            'attempt'     => $this->getAttempts($stage, $level),
            'isCompleted' => $isCompleted,
        ]);
    }

    public function submitLevel(Request $request, int $stage, int $level)
    {
        // ==== 0. Kalau level sudah selesai, blok replay via POST ====
        if ($this->isCompleted($stage, $level)) {
            return redirect()
                ->route('game.level.show', [$stage, $level])
                ->with([
                    'status_type' => 'info',
                    'status_msg'  => 'Level ini sudah kamu selesaikan. Lanjut ke level berikutnya, ya! ✅',
                ]);
        }

        $config    = $this->getLevelConfigOrFail($stage, $level);
        $attempt   = $this->incrementAttempts($stage, $level);
        $pointsMap = $config['points'] ?? [];
        $earned    = $pointsMap[$attempt] ?? 0;

        $statusType      = 'warning';
        $statusMsg       = 'Jawaban belum benar, coba lagi.';
        $showExplanation = false;
        $isCorrect       = false;

        // ==== 1. Cek benar / salah ====
        switch ($config['type']) {
            case 'multiple_choice':
                $chosen  = $request->input('answer');
                $correct = $config['correct'] ?? null;

                if ($chosen === $correct) {
                    $isCorrect       = true;
                    $statusType      = 'success';
                    $statusMsg       = "Jawaban benar! Kamu mendapat {$earned} koin.";
                    $showExplanation = true;
                }
                break;

            case 'true_false':
                $allCorrect = true;

                foreach ($config['statements'] as $key => $st) {
                    $userVal  = $request->input("statement_{$key}");
                    $expected = $st['answer'] ? 'true' : 'false';

                    if ($userVal !== $expected) {
                        $allCorrect = false;
                        break;
                    }
                }

                if ($allCorrect) {
                    $isCorrect       = true;
                    $statusType      = 'success';
                    $statusMsg       = "Jawaban benar semua! Kamu mendapat {$earned} koin.";
                    $showExplanation = true;
                }
                break;

            case 'order':
                $correctSteps = $config['steps'];
                $correctOrder = array_keys($correctSteps); // [1,2,3,...]
                $userOrder    = [];

                foreach ($correctSteps as $idx => $_) {
                    $userOrder[] = (int) $request->input("step_{$idx}");
                }

                if ($userOrder === $correctOrder) {
                    $isCorrect       = true;
                    $statusType      = 'success';
                    $statusMsg       = "Urutan sudah tepat! Kamu mendapat {$earned} koin.";
                    $showExplanation = true;
                }
                break;
        }

        // ==== 2. Kalau BENAR ====
        if ($isCorrect) {
            $currentCode = $this->levelCode($stage, $level);

            // cek apakah level ini sudah pernah selesai
            $alreadyCompleted = $this->isCompleted($stage, $level);

            // tambah koin cuma sekali (first time clear)
            if (!$alreadyCompleted && $earned > 0) {
                $this->addCoins($earned);
            }

            // tandai level selesai + reset percobaan
            $this->addCompletedLevel($stage, $level);
            $this->resetAttempts($stage, $level);

            // update maxUnlocked minimal sampai level ini
            $maxUnlocked = $this->getMaxUnlocked();
            if ($currentCode > $maxUnlocked) {
                $this->setMaxUnlocked($currentCode);
            }

            // simpan posisi current
            $this->setCurrentCode($currentCode);

            // cari level berikutnya
            $next = $this->getNextStageLevel($stage, $level);

            // ==== 2a. Kalau ADA next level ====
            if ($next !== null) {
                [$nextStage, $nextLevel] = $next;
                $nextCode = $this->levelCode($nextStage, $nextLevel);

                // kalau next level MASIH TERKUNCI → wajib "bayar" dulu
                if ($nextCode > $this->getMaxUnlocked()) {
                    $unlockCost = $config['points'][1] ?? 0; // misal biaya unlock = poin percobaan 1 level ini

                    session([
                        'pending_unlock' => [
                            'from_stage' => $stage,
                            'from_level' => $level,
                            'to_stage'   => $nextStage,
                            'to_level'   => $nextLevel,
                            'cost'       => $unlockCost,
                        ],
                    ]);

                    return redirect()->route('game.unlock.show', [
                        'fromStage' => $stage,
                        'fromLevel' => $level,
                        'toStage'   => $nextStage,
                        'toLevel'   => $nextLevel,
                    ])->with([
                        'status_type'      => $statusType,
                        'status_msg'       => $statusMsg,
                        'show_explanation' => $showExplanation,
                    ]);
                }

                // kalau next level SUDAH terbuka (misal replay level lama)
                return redirect()->route('game.index')->with([
                    'status_type'      => $statusType,
                    'status_msg'       => $statusMsg,
                    'show_explanation' => $showExplanation,
                ]);
            }

            // ==== 2b. TIDAK ADA next level → ini level terakhir ====
            $lastCode = $this->getLastLevelCode();
            $this->setMaxUnlocked($lastCode);
            $this->setCurrentCode($currentCode);

            return redirect()->route('game.index')->with([
                'status_type'      => $statusType,
                'status_msg'       => $statusMsg . ' Kamu sudah menyelesaikan semua level 🎉',
                'show_explanation' => $showExplanation,
            ]);
        }

        // ==== 3. Kalau SALAH ====
        if ($attempt >= 2) {
            $this->resetAttempts($stage, $level);
            $statusType = 'danger';
            $statusMsg  = 'Jawaban masih salah dan percobaan sudah habis. Ulangi level ini dari awal.';
        }

        return back()->with([
            'status_type'      => $statusType,
            'status_msg'       => $statusMsg,
            'show_explanation' => $showExplanation,
        ])->withInput();
    }

    /* ========= HALAMAN KONFIRMASI PEMBAYARAN QRIS ========= */

    public function showUnlock(int $fromStage, int $fromLevel, int $toStage, int $toLevel)
    {
        $pending = session('pending_unlock');

        // kalau tidak ada pending unlock, balikin ke papan
        if (
            !$pending ||
            (int) $pending['from_stage'] !== $fromStage ||
            (int) $pending['from_level'] !== $fromLevel ||
            (int) $pending['to_stage']   !== $toStage   ||
            (int) $pending['to_level']   !== $toLevel
        ) {
            return redirect()->route('game.index')->with([
                'status_type' => 'info',
                'status_msg'  => 'Tidak ada transaksi pembukaan level yang aktif.',
            ]);
        }

        return view('game.unlock', [
            'coins'     => $this->getCoins(),
            'fromStage' => $fromStage,
            'fromLevel' => $fromLevel,
            'toStage'   => $toStage,
            'toLevel'   => $toLevel,
            'cost'      => $pending['cost'] ?? 0,
        ]);
    }

    public function processUnlock(Request $request, int $fromStage, int $fromLevel, int $toStage, int $toLevel)
    {
        $pending = session('pending_unlock');

        if (!$pending) {
            return redirect()->route('game.index')->with([
                'status_type' => 'info',
                'status_msg'  => 'Transaksi sudah tidak aktif.',
            ]);
        }

        $cost  = (int) ($pending['cost'] ?? 0);
        $coins = $this->getCoins();

        if ($coins < $cost) {
            return back()->with([
                'status_type' => 'danger',
                'status_msg'  => 'Koin kamu belum cukup untuk membuka level berikutnya.',
            ]);
        }

        // potong koin
        $this->setCoins($coins - $cost);

        // unlock level berikutnya
        $nextIndex = $this->levelIndex($toStage, $toLevel);
        if ($nextIndex > $this->getMaxUnlocked()) {
            $this->setMaxUnlocked($nextIndex);
        }
        $this->setCurrentCode($nextIndex);

        // hapus pending unlock
        session()->forget('pending_unlock');

        return redirect()->route('game.level.show', [
            'stage' => $toStage,
            'level' => $toLevel,
        ])->with([
            'status_type' => 'success',
            'status_msg'  => "Pembayaran berhasil! Level Stage {$toStage} – Level {$toLevel} sudah terbuka.",
        ]);
    }
}
