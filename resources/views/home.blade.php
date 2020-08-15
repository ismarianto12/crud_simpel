@extends('template')
@section('title','Selamat datang di adminstrator panel')
@section('contents')

<div class="card">
    <div class="card-header white">
        <h6> Data Barang terbaru </h6>
    </div>
    <div class="card-body p-0">
        <div class="lightSlider" data-item="6" data-item-xl="4" data-item-md="2" data-item-sm="1" data-pause="7000" data-pager="false" data-auto="true"
             data-loop="true">

             @php $no = 1; @endphp
         @foreach($data as $dt)   
         @php $warna = ($no%2) ? 'primary' : 'default'; @endphp


         <img src="{{ asset('public/foto/'.$dt->foto) }}" class="img-responsive" style="height: 200px;width:200px">
         <div class="p-5 bg-{{ $warna }} text-white">
                 <h5 class="font-weight-normal s-14">{{ $dt->barangnm }}</h5>
                
                 <div>   
                    <span class="text-{{ $warna }}">
            <i class="icon icon-arrow_downward"></i>  Harga Satuan : Rp .{{ number_format((int)$dt->hargajual,0,0,'.') }}</span>
                </div>
            </div>
            @php $no++; @endphp
            @endforeach 
            {{--  <div class="p-5">
                <h5 class="font-weight-normal s-14">Iron</h5>
                <span class="s-48 font-weight-lighter light-green-text">675</span>
                <div> Carbon
                    <span class="text-light-green">
            <i class="icon icon-arrow_downward"></i> 67%</span>
                </div>
            </div>  --}}
            {{--  <div class="p-5 light">
                <h5 class="font-weight-normal s-14">Helium</h5>
                <span class="s-48 font-weight-lighter text-primary">300</span>
                <div> Hydrogen
                    <span class="text-primary">
            <i class="icon icon-arrow_downward"></i> 67%</span>
                </div>
            </div>
            <div class="p-5">
                <h5 class="font-weight-normal s-14">Carbon</h5>
                <span class="s-48 font-weight-lighter amber-text">700</span>
                <div> Helium
                    <span class="amber-text">
            <i class="icon icon-arrow_downward"></i> 67%</span>
                </div>
            </div>
            <div class="p-5 light">
                <h5 class="font-weight-normal s-14">Oxygen</h5>
                <span class="s-48 font-weight-lighter text-indigo">411</span>
                <div> Iron
                    <span class="text-indigo">
            <i class="icon icon-arrow_downward"></i> 89%</span>
                </div>
            </div>
            <div class="p-5">
                <h5 class="font-weight-normal s-14">Helium</h5>
                <span class="s-48 font-weight-lighter pink-text">224</span>
                <div> Sodium
                    <span class="pink-text">
            <i class="icon icon-arrow_downward"></i> 47%</span>
                </div>
            </div>  --}}
        </div>
    </div>
</div>  

    
@endsection