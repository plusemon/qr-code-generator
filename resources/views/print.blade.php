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
            padding-top: 2.5pt;
            padding-left: 32.3pt;
        }

        .item {
            height: 155pt;
            width: 552pt;
            position: relative;
            margin-bottom: 7pt;
            border: 1px solid
                {{ $isActiveBorder ? 'red' : 'transparent' }}
            ;
            border-bottom: none;
        }

        .qrimg {
            position: absolute;
            top: 90.5pt;
            left: 79pt;
            border: 1px solid
                {{ $isActiveBorder ? 'red' : 'transparent' }}
            ;
            border-bottom: none;
        }

        .qrcode {
            position: absolute;
            top: 121.96pt;
            left: 195.40pt;
            text-align: center;
            height: 17pt;
            width: 148pt;
            font-size: 9pt;
            line-height: 14pt;
            border: 1px solid
                {{ $isActiveBorder ? 'red' : 'transparent' }}
            ;
            border-bottom: none;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(5) as $chunk)
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