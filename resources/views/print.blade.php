<head>
    <title>{{ $pdf_name }}</title>

    @php
        $isActiveBorder = $border;
    @endphp
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: content-box;
        }

        .page {
            margin: 20px;
            padding: 1px;
            background: rgb(230, 230, 230);
            /* border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }}; */
        }

        .item {
            height: 130pt;
            width: 300pt;
            margin: 10px;
            position: relative;
            border: 1px solid {{ $isActiveBorder ? 'blue' : 'transparent' }};
        }

        .qrimg {
            position: absolute;
            top: 30%;
            left: 45%;
            border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }};
        }

        .qrcode {
            position: absolute;
            top: 55%;
            left: 41%;
            text-align: center;
            height: 16pt;
            width: 50pt;
            font-size: 10pt;
            line-height: 14pt;
            border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }};
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(7) as $chunk)
    <div class="page">
        @foreach ($chunk as $code)
            <div class="item">
                <img class="qrimg" src="{{ url("qrcodes/$code.svg") }}">
                <span class="qrcode">{{ $code }}</span>
            </div>
        @endforeach
    </div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
