<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                <span class="material-symbols-outlined text-[24px]">bar_chart</span>
            </div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Statistik Workspace</h1>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tugas -->
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-medium">Total Tugas</span>
                    <div class="w-10 h-10 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[20px]">assignment</span>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $totalTasks }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Jumlah seluruh tugas</span>
                </div>
            </div>

            <!-- Tugas Selesai -->
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-medium">Tugas Selesai</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $doneCount }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Sudah diselesaikan</span>
                </div>
            </div>

            <!-- Belum Selesai -->
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-medium">Belum Selesai</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $todoCount }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Tugas tertunda</span>
                </div>
            </div>

            <!-- Tingkat Penyelesaian -->
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col justify-between gap-4">
                <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-medium">Tingkat Penyelesaian</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-symbols-outlined text-[20px]">trending_up</span>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $completionRate }}%</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Persentase selesai</span>
                </div>
            </div>
        </div>

        <!-- Detail Section: Distribusi Status & Produktivitas Mingguan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Distribusi Status -->
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
                <div>
                    <h3 class="font-title-lg text-title-lg text-on-surface font-semibold">Distribusi Status</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Perbandingan tugas berdasarkan status</p>
                </div>

                <div class="flex flex-col gap-4 mt-2">
                    @php
                        $statuses = [
                            ['label' => 'To Do (Belum Dimulai)', 'count' => $statusCounts['todo'], 'color' => 'bg-slate-400', 'text' => 'text-slate-600 dark:text-slate-300'],
                            ['label' => 'In Progress (Sedang Dikerjakan)', 'count' => $statusCounts['in_progress'], 'color' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400'],
                            ['label' => 'Done (Selesai)', 'count' => $statusCounts['done'], 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                        ];
                    @endphp

                    @foreach ($statuses as $stat)
                        @php
                            $pct = $totalTasks > 0 ? round(($stat['count'] / $totalTasks) * 100) : 0;
                        @endphp
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between font-label-md text-label-md">
                                <span class="{{ $stat['text'] }} font-medium">{{ $stat['label'] }}</span>
                                <span class="font-bold text-on-surface">{{ $stat['count'] }} <span class="text-outline font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="w-full h-2.5 rounded-full bg-surface-container overflow-hidden">
                                <div class="h-full {{ $stat['color'] }} rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Produktivitas Mingguan -->
            <div class="lg:col-span-2 rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
                <div>
                    <h3 class="font-title-lg text-title-lg text-on-surface font-semibold">Produktivitas Mingguan</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Jumlah tugas yang diselesaikan setiap minggu</p>
                </div>

                <div class="flex items-end gap-3 h-52 pt-6 px-2 overflow-x-auto pb-2">
                    @php
                        $maxWeekly = max(array_merge([1], array_column($weeklyProductivity, 'count')));
                    @endphp
                    @foreach ($weeklyProductivity as $weekData)
                        @php
                            $heightPercent = max(12, round(($weekData['count'] / max(1, $maxWeekly)) * 100));
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 min-w-[50px] h-full justify-end group">
                            <span class="font-label-sm text-label-sm font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity">{{ $weekData['count'] }}</span>
                            <div class="w-full bg-primary-container/30 group-hover:bg-primary-container rounded-t-lg transition-all duration-300 relative flex items-end justify-center pb-2" style="height: {{ $heightPercent }}%;">
                                <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg" style="height: 100%;"></div>
                                <span class="relative z-10 text-on-primary font-bold text-xs {{ $weekData['count'] == 0 ? 'text-on-surface-variant opacity-60' : '' }}">{{ $weekData['count'] }}</span>
                            </div>
                            <span class="font-label-xs text-label-xs text-outline text-center truncate w-full mt-1">{{ $weekData['week'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
