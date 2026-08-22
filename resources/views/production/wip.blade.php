@extends('layouts.app')

@section('title', 'WIP Tracking 7 Mesin Bubut')
@section('page-title', 'Tracking Barang dalam Proses (WIP)')
@section('page-subtitle', 'Monitoring Durasi dan Stasiun Kerja 7 Unit Mesin Bubut UD Cahaya Onix')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="text-sm font-bold text-slate-800 mb-3">Daftar Batch Sedang Dikerjakan di Lantai Produksi</h4>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">No. SPK</th>
                        <th class="p-3">Produk</th>
                        <th class="p-3">Target / Selesai</th>
                        <th class="p-3">Stasiun Kerja</th>
                        <th class="p-3">Progres</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workOrders as $wo)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3 font-mono font-bold text-blue-600">{{ $wo->spk_number }}</td>
                        <td class="p-3 font-semibold text-slate-800">{{ $wo->product->name ?? '-' }}</td>
                        <td class="p-3">{{ $wo->target_quantity }} Unit</td>
                        <td class="p-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                Mesin Bubut 1-4
                            </span>
                        </td>
                        <td class="p-3 w-48">
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full" style="width: {{ $wo->progress_percentage }}%"></div>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 block">{{ $wo->progress_percentage }}% selesai</span>
                        </td>
                        <td class="p-3">
                            <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                {{ str_replace('_', ' ', $wo->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">Tidak ada batch pengerjaan aktif saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
