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
            padding-top: 43pt;
            padding-left: 31pt;
            border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }};
        }

        .item {
            height: 139.5pt;
            width: 562pt;
            position: relative;
            border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }};
            border-bottom: none;
        }

        .page .item:last-child {
            border-bottom: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }};
        }

        .qrimg {
            position: absolute;
            top: 95.4pt;
            left: 170pt;
        }

        .qrcode {
            position: absolute;
            top: 111pt;
            left: 214pt;
            text-align: center;
            height: 16pt;
            width: 150pt;
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
