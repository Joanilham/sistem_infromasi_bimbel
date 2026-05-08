@extends('layouts.cbt')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Hasil Ujian</h2>
    @if($hasil->isEmpty())
        <p class="text-gray-500">Anda belum mengikuti ujian apapun.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">Nama Ujian</th>
                        <th class="p-3 border">Tanggal</th>
                        <th class="p-3 border text-center">Benar</th>
                        <th class="p-3 border text-center">Salah</th>
                        <th class="p-3 border text-center font-bold">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $h)
                    <tr>
                        <td class="p-3 border">{{ $h->ujian->nama_ujian }}</td>
                        <td class="p-3 border">{{ $h->created_at->format('d M Y H:i') }}</td>
                        <td class="p-3 border text-center text-green-600">{{ $h->jumlah_benar }}</td>
                        <td class="p-3 border text-center text-red-600">{{ $h->jumlah_salah }}</td>
                        <td class="p-3 border text-center font-bold text-xl">{{ $h->nilai }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection