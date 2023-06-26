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

        .container {
            padding-top: 31.2pt;
            margin-top: 48pt;
            margin-left: 32pt;
            width: 100%;
            border: 1px solid {{ $isActiveBorder ? 'lime' : 'transparent' }};

        }

        .item {
            /* initial 0 by top value  */
            top: 0;
            left: 0;
            height: 143.3pt;
            width: 143pt;
            margin: 5.2pt;
            margin-right: 3pt;
            display: inline-block;
            position: relative;
            border: 1px solid {{ $isActiveBorder ? 'lime' : 'transparent' }};
        }

        .page .item:last-child {
            margin-bottom: 0;
            border-bottom: 1px solid {{ $isActiveBorder ? 'lime' : 'transparent' }};
        }

        .code {
            position: absolute;
            top: 66pt;
            left: 10pt;
            height: 20pt;
            width: 122pt;
            text-align: center;
            font-size: 9pt;
            line-height: 12pt;
            border: 1px solid {{ $isActiveBorder ? 'lime' : 'transparent' }};
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(12) as $chunk)
    <div class="artboard">
        <div class="container">
            @foreach ($chunk as $code)
                <div class="item">
                    <span class="code">{{ $code }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
