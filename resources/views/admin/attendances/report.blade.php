  @php
  use Illuminate\Support\Carbon;

  $showUserDetail = true;
  $isPerDayFilter = $filterType === 'day';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.1">
  <title>
    Absensi |
    @if ($filterType === 'day')
      {{ Carbon::parse($day)->translatedFormat('d F Y') }}
    @elseif ($filterType === 'week')
      {{ Carbon::parse($start)->format('d/m/Y') }} - {{ Carbon::parse($end)->format('d/m/Y') }}
    @elseif ($filterType === 'month')
      {{ Carbon::parse($month)->translatedFormat('F Y') }}
    @else
      Semua Periode
    @endif
  </title>

  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    #table {
      width: 100%;
      border-collapse: collapse;
    }
    #table th, #table td {
      border: 1px solid #aaa;
      padding: 6px;
    }
    #table th {
      background: #f2f2f2;
    }
    #table tr:nth-child(even) {
      background: #fafafa;
    }
  </style>
</head>

<body>
  <h2>Data Absensi</h2>

  {{-- HEADER INFO --}}
  <div style="display: table; width: 100%; margin-bottom: 15px;">
    <div style="display: table-cell;">
      <table>
        @if ($division)
          <tr>
            <td>Divisi</td><td>:</td>
            <td>{{ App\Models\Division::find($division)->name ?? '-' }}</td>
          </tr>
        @endif
        @if ($jobTitle)
          <tr>
            <td>Jabatan</td><td>:</td>
            <td>{{ App\Models\JobTitle::find($jobTitle)->name ?? '-' }}</td>
          </tr>
        @endif
      </table>
    </div>

    <div style="display: table-cell; text-align: right;">
      <strong>
        @if ($filterType === 'day')
          Tanggal:
          {{ Carbon::parse($day)->translatedFormat('d F Y') }}
        @elseif ($filterType === 'week')
          Minggu:
          {{ Carbon::parse($start)->format('d/m/Y') }} -
          {{ Carbon::parse($end)->format('d/m/Y') }}
        @elseif ($filterType === 'month')
          Bulan:
          {{ Carbon::parse($month)->translatedFormat('F Y') }}
        @else
          Periode: Semua Periode
        @endif
      </strong>
    </div>
  </div>

  {{-- TABLE --}}
  <table id="table">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIP</th>
        <th>Divisi</th>
        <th>Jabatan</th>

        @foreach ($dates as $date)
          <th style="font-size: 10px; width: 26px;">
            {{ $date->format('d/m') }}
          </th>
        @endforeach

        @if (!$isPerDayFilter)
          @foreach (['H', 'T', 'I', 'S', 'A'] as $s)
            <th width="20">{{ $s }}</th>
          @endforeach
        @endif
      </tr>
    </thead>

    <tbody>
      @forelse ($employees as $employee)
        @php
          $present = $late = $excused = $sick = $absent = 0;
        @endphp
        <tr style="font-size: 12px;">
          <td align="center">{{ $loop->iteration }}</td>
          <td>{{ $employee->name }}</td>
          <td>{{ $employee->nip }}</td>
          <td>{{ $employee->division?->name ?? '-' }}</td>
          <td>{{ $employee->jobTitle?->name ?? '-' }}</td>

          @foreach ($dates as $date)
            @php
              $att = $employee->attendances
                ->firstWhere('date', $date->format('Y-m-d'));

              $status = $att['status'] ?? 'absent';

              match ($status) {
                'present' => $present++,
                'late' => $late++,
                'excused' => $excused++,
                'sick' => $sick++,
                default => $absent++,
              };

              $map = [
                'present' => 'H',
                'late' => 'T',
                'excused' => 'I',
                'sick' => 'S',
                'absent' => 'A',
              ];
            @endphp
            <td align="center">
              {{ $isPerDayFilter ? ucfirst($status) : $map[$status] }}
            </td>
          @endforeach

          @if (!$isPerDayFilter)
            <td align="center"><strong>{{ $present }}</strong></td>
            <td align="center"><strong>{{ $late }}</strong></td>
            <td align="center"><strong>{{ $excused }}</strong></td>
            <td align="center"><strong>{{ $sick }}</strong></td>
            <td align="center"><strong>{{ $absent }}</strong></td>
          @endif
        </tr>
      @empty
        <tr>
          <td colspan="100%" align="center">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
