<x-guest-layout>
    <div class="min-h-screen bg-surface-container-lowest text-on-surface">
        <!-- Hero Section -->
        <section class="flex flex-col items-center justify-center pt-20 pb-16 px-4 text-center">
            <h1 class="font-headline-xl text-headline-xl font-bold tracking-tight text-primary mb-6">TasKuy</h1>
            <p class="text-body-xl text-on-surface-variant max-w-2xl mb-10">Student Workspace terbaik untuk mengelola tugas, kalender kuliah, dan jadwal mata kuliah. Belajar jadi lebih teratur dan produktif.</p>
            
            <div class="flex gap-4">
                <a href="{{ route('login') }}" class="h-12 px-8 rounded-full bg-primary text-on-primary font-label-lg font-semibold flex items-center hover:bg-primary-container transition-all">Mulai Sekarang</a>
                <a href="{{ route('register') }}" class="h-12 px-8 rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg font-semibold flex items-center transition-all">Daftar</a>
            </div>
        </section>

        <!-- Features -->
        <section class="max-w-6xl mx-auto px-4 py-16 grid md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-surface-container border border-surface-container-high">
                <span class="material-symbols-outlined text-[40px] text-primary mb-4">task_alt</span>
                <h3 class="font-title-lg font-bold mb-2">Manajemen Tugas</h3>
                <p class="text-body-md text-on-surface-variant">Atur tugas, deadline, dan status selesai dengan mudah dalam satu tampilan.</p>
            </div>
            <div class="p-8 rounded-3xl bg-surface-container border border-surface-container-high">
                <span class="material-symbols-outlined text-[40px] text-primary mb-4">calendar_month</span>
                <h3 class="font-title-lg font-bold mb-2">Kalender Nasional</h3>
                <p class="text-body-md text-on-surface-variant">Pantau deadline tugas dan hari libur nasional dengan kalender yang terintegrasi.</p>
            </div>
            <div class="p-8 rounded-3xl bg-surface-container border border-surface-container-high">
                <span class="material-symbols-outlined text-[40px] text-primary mb-4">auto_stories</span>
                <h3 class="font-title-lg font-bold mb-2">Jadwal Kuliah</h3>
                <p class="text-body-md text-on-surface-variant">Simpan data dosen, SKS, dan waktu kuliah agar tidak terlewat.</p>
            </div>
        </section>
    </div>
</x-guest-layout>
