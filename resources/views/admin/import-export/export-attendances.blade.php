<table>
    <thead>
        {{-- 1. JUDUL DINAMIS --}}
        <tr>
            {{-- Mengambil Tanggal Awal (first) dan Akhir (last) dari array $dates --}}
            @php
                $startDate = reset($dates); // Ambil elemen pertama
                $endDate   = end($dates);   // Ambil elemen terakhir
            @endphp
            
            <th colspan="{{ 5 + count($dates) + 5 }}" style="font-weight: bold; font-size: 14px; text-align: center; height: 30px; vertical-align: middle;">
                LAPORAN DATA ABSENSI PERIODE {{ $startDate->format('d F Y') }} s.d {{ $endDate->format('d F Y') }}
            </th>
        </tr>
        <tr></tr>

        {{-- 2. HEADER TABEL --}}
        <tr>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; width: 5px;">No</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; width: 25px;">Nama</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; width: 15px;">NIP</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; width: 15px;">Divisi</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; width: 15px;">Jabatan</th>

            {{-- Loop Header Tanggal --}}
            @foreach($dates as $date)
                {{-- $date sudah berupa object Carbon, langsung format --}}
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f0f0f0; width: 5px;">
                    {{ $date->format('d') }}<br>{{ $date->format('M') }}
                </th>
            @endforeach

            {{-- Header Rekap --}}
            <th colspan="5" style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #e0e0e0;">Total</th>
        </tr>
        <tr>
            {{-- Baris kedua header untuk total --}}
            {{-- Spacer untuk tanggal (kosong karena di atas sudah rowspan) --}}
            @foreach($dates as $date)
                <th style="display: none;"></th> 
            @endforeach

            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #d1e7dd; width: 5px;">H</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #fff3cd; width: 5px;">T</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #cfe2ff; width: 5px;">I</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #e2e3e5; width: 5px;">S</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8d7da; width: 5px;">A</th>
        </tr>
    </thead>
    <tbody>
        {{-- 3. ISI DATA --}}
        @foreach($employees as $index => $emp)
            
            {{-- OPTIMASI: Mapping Absensi berdasarkan Tanggal agar tidak looping query --}}
            @php
                // Mengubah Collection Absensi menjadi Array Key-Value dengan Key = Tanggal (Y-m-d)
                // Pastikan $emp->attendances sudah diload di Controller/Export Class
                $attendanceMap = $emp->attendances->keyBy(function($item) {
                    return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                });

                $h=0; $t=0; $i=0; $s=0; $a=0; 
            @endphp

            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000000;">{{ $emp->name }}</td>
                {{-- Gunakan operator nullsafe (?) atau string kosong --}}
                <td style="border: 1px solid #000000; text-align: left;">{{ $emp->nip ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $emp->division?->name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $emp->job_title?->name ?? '-' }}</td>

                {{-- Loop Tanggal --}}
                @foreach($dates as $date)
                    @php
                        $dateKey = $date->format('Y-m-d');
                        // Ambil data dari Map (Cepat, O(1))
                        $absen = $attendanceMap[$dateKey] ?? null; 
                        
                        // Tentukan Status (Sesuaikan string status dengan database Anda)
                        $status = $absen ? strtolower($absen->status) : null; 
                        
                        $bg = '#ffffff';
                        $kode = '-'; // Default jika hari kerja tapi belum absen (bisa diubah jadi A)

                        // Sesuaikan Value Database di sini (Hadir, Late, dll)
                        if ($status == 'hadir' || $status == 'present') { 
                            $bg='#d1e7dd'; $kode='H'; $h++; 
                        }
                        elseif ($status == 'telat' || $status == 'late') { 
                            $bg='#fff3cd'; $kode='T'; $t++; 
                        }
                        elseif ($status == 'izin' || $status == 'excused' || $status == 'permit') { 
                            $bg='#cfe2ff'; $kode='I'; $i++; 
                        }
                        elseif ($status == 'sakit' || $status == 'sick') { 
                            $bg='#e2e3e5'; $kode='S'; $s++; 
                        }
                        elseif ($status == 'alpha' || $status == 'absent') { 
                            $bg='#f8d7da'; $kode='A'; $a++; 
                        }
                        else {
                            // Jika tanggal sudah lewat dan tidak ada data -> Alpha
                            if($date->isPast() && !$date->isToday()) {
                                $bg='#f8d7da'; $kode='A'; $a++;
                            }
                        }
                    @endphp

                    <td style="border: 1px solid #000000; text-align: center; background-color: {{ $bg }}; font-weight: bold;">
                        {{ $kode }}
                    </td>
                @endforeach

                {{-- Tampilkan Total --}}
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #d1e7dd;">{{ $h }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #fff3cd;">{{ $t }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #cfe2ff;">{{ $i }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #e2e3e5;">{{ $s }}</td>
                <td style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #f8d7da;">{{ $a }}</td>
            </tr>
        @endforeach
    </tbody>
</table>