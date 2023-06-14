<head>
    <title>{{ $pdf_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        .artboard {
            margin: 0;
            width: {{ $width }}pt;
            height: {{ $height }}pt;
            /* border: 1px solid red; */
        }

        .page {
            margin-top: 44.5pt;
            margin-left: 32.5pt;
            /* border: 1px solid green; */
        }

        .item {
            height: 140.5pt;
            width: 562pt;
            position: relative;
            /* border: 1px solid black; */
        }

        .qrimg {
            position: absolute;
            top: 95.4pt;
            left: 170pt;
        }

        .qrcode {
            position: absolute;
            top: 112.2pt;
            left: 216pt;
            text-align: center;
            /* border: 1px solid red; */
            height: 16pt;
            width: 150pt;
            font-size: 10pt;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(7) as $chunk)
    <div class="artboard">
        <div class="page">
            @foreach ($chunk as $code)
                <div class="item">
                    <img class="qrimg" src="{{ url("qrcodes/$code.svg") }}">
                    <span class="qrcode">{{ $code }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
