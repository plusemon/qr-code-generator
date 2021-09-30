@extends('app')

@section('main')
<div id="loading">
    <h1>Returning to homepage</h1>
</div>

<div class="wrapper">
    <div>
        @foreach ($qrcodes as $sub)
        <div class="a4">
            @foreach ($sub as $item)
            <div class="box">
                {!! QrCode::size($config['size'])->generate($item[1]) !!}
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    <div class="buttons d-print-none">
        <button class="btn btn-primary my-3" onclick="window.print()">Print</button>
        <br>
        <a href="{{ route('home') }}" onclick="showLoader()" class="btn btn-dark">Back</a>
    </div>
</div>
@endsection