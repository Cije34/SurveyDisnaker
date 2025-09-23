<table class="schedule-table">
  <thead>
  <tr>
    <th>Kegiatan</th>
    <th>Tanggal</th>
    <th>Waktu</th>
    <th>Lokasi</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($rows as $i => $r)
    <tr class="{{ (isset($highlightIndex) && $highlightIndex === $i) ? 'row-focus' : '' }} {{ $i % 2 === 1 ? 'row-alt' : '' }}">
      <td>{{ $r['kegiatan'] }}</td>
      <td>{{ $r['tanggal'] }}</td>
      <td>{{ $r['waktu'] }}</td>
      <td>{{ $r['lokasi'] }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
