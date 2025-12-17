<table class="w-full text-sm border">
    <thead>
        <tr>
            <th class="border px-2 py-1">Nama</th>
            <th class="border px-2 py-1">Email</th>
            <th class="border px-2 py-1">Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($employees as $user)
            <tr>
                <td class="border px-2 py-1">{{ $user->name }}</td>
                <td class="border px-2 py-1">{{ $user->email }}</td>

                {{-- ✅ STATUS VERIFIKASI (DI SINI TEMPATNYA) --}}
                <td class="border px-2 py-1">
                    @if (is_null($user->email_verified_at))
                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                            Belum Verifikasi
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                            Aktif
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
