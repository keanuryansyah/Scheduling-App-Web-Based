<div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
        <thead class="bg-light">
            <tr>
                <th class="ps-4">Tanggal</th>
                <th>Jam</th>
                <th>Job & Klien</th>
                <th>Tipe</th>
                <th>Crew / Editor</th>
                <th>Status</th>
                <th class="text-end pe-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                @include('admin.partials.job_row')
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada data untuk status ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>