@extends('app')

@section('main')
<div id="loading">
    <h1>Loading.. Please wait</h1>
</div>


<div class="align-items-center d-flex" style="height: 100vh">
    <form action="{{ route('print') }}" class="container" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- <div class="col-md-4">
                <label for="">Size (px)</label>
                <input type="number" name="size" class="form-control" placeholder="230px">
                @error('size')
                <div style="color: red">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="">Items per page</label>
                <input type="number" name="per_page" class="form-control" placeholder="9">
                @error('per_page')
                <div style="color: red">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="">Margin (px)</label>
                <input type="number" name="margin" class="form-control" placeholder="10px">
                @error('margin')
                <div style="color: red">{{ $message }}</div>
                @enderror
            </div> --}}

            <div class="col-md-12">
                <label for="">Upload file (csv, xlxs etc) <a download href="{{ url('/sample-qr-codes.xlsx') }}">Download sample-qr-codes.xls</a></label>
                <input type="file" name="file" class="form-control">
                @error('file')
                <div style="color: red">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12 my-3 text-center">
                <button type="submit" onclick="showLoader()" class="btn btn-primary btn-block">Generate QR Code</button>
            </div>
        </div>
    </form>
</div>

@endsection