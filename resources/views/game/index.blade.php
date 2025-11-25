@extends('layouts.app')

@section('title', 'Papan Stage & Level – QRIS Journey')

@section('content')
    <section class="section">
        <h2 class="animate-on-scroll">Papan Petualangan QRIS</h2>

        @php
            // HARUS sama dengan LEVELS_PER_STAGE di GameController
            $levelsPerStage = 3;

            // maxUnlocked sekarang adalah index linear: 1,2,3,4,...
            $stageUnlocked = intdiv($maxUnlocked - 1, $levelsPerStage) + 1;
            $levelUnlocked = ($maxUnlocked - 1) % $levelsPerStage + 1;
        @endphp

        <p style="text-align:center; color: var(--gray); margin-bottom: 1.5rem;">
            Koin kamu: <strong>{{ $coins }}</strong><br>
        </p>

        <div class="board-wrapper">
            {{-- papan level --}}
            <div class="game-board">
                @foreach ($stages as $stageNumber => $stageData)
                    @foreach ($stageData['levels'] as $levelNumber => $info)
                        @php
                            // index linear level: 1..9 (kalau 3 stage × 3 level)
                            $index     = ($stageNumber - 1) * $levelsPerStage + $levelNumber;
                            $unlocked  = $index <= $maxUnlocked;
                            $isCurrent = $index === $currentCode;
                            $completed = in_array("{$stageNumber}-{$levelNumber}", $completedLevels, true);

                            $typeLabel = match ($info['type']) {
                                'multiple_choice' => 'Pilihan ganda',
                                'true_false'      => 'Benar / Salah',
                                'order'           => 'Urutkan langkah',
                                default           => ucfirst($info['type']),
                            };

                            $points1 = $info['points'][1] ?? 0;
                            $points2 = $info['points'][2] ?? 0;
                        @endphp

                        <button
                            type="button"
                            class="board-cell
                                {{ $unlocked ? 'cell-unlocked' : 'cell-locked' }}
                                {{ $isCurrent ? 'cell-current' : '' }}"
                            @if ($unlocked)
                                onclick="window.location='{{ route('game.level.show', [
                                    'stage' => $stageNumber,
                                    'level' => $levelNumber,
                                ]) }}'"
                            @endif
                        >
                            {{-- kode level di pojok kanan atas --}}
                            <span class="cell-number">{{ $stageNumber }}-{{ $levelNumber }}</span>

                            {{-- token karakter di level aktif --}}
                            @if ($isCurrent)
                                <div class="player-token">
                                    <span class="player-face">😀</span>
                                </div>
                            @endif

                            <span class="cell-title">{{ $info['title'] }}</span>
                            <span class="cell-type">{{ $typeLabel }}</span>
                            <span class="cell-points">{{ $points1 }} / {{ $points2 }} poin</span>

                            @if (! $unlocked)
                                <span class="cell-locked-label">Terkunci</span>
                            @elseif ($completed)
                                <span class="cell-locked-label" style="color: var(--success);">
                                    Selesai
                                </span>
                            @endif
                        </button>
                    @endforeach
                @endforeach

                {{-- dekorasi jalur di belakang kotak level --}}
                <div class="board-path"></div>
            </div>

            {{-- legenda --}}
            <div class="board-legend">
                <h3>Cara Main</h3>
                <ul>
                    <li>Ikon 😀 menunjukkan posisi level kamu sekarang.</li>
                    <li>Petak berwarna terang = level sudah terbuka dan bisa diklik.</li>
                    <li>Petak abu / garis putus-putus = level terkunci.</li>
                    <li>Setiap level bisa dicoba beberapa kali, tetapi koin hanya bertambah saat pertama kali berhasil.</li>
                    <li>Skor mengikuti kolom poin percobaan 1 & 2 di desain game QRIS Journey.</li>
                </ul>
            </div>
        </div>
    </section>
@endsection
