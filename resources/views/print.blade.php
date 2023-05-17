<head>
    <title>{{ $pdf_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .page {
            padding-top: 16pt;
            padding-left: 30pt;
            height: 504pt;
            width: 792pt;
            position: absolute;
            left: 0;
            top: 30;
            /* border: 1px solid green; */
        }

        .item {
            width: 234pt;
            height: 151pt;
            position: relative;
            border: 1px dotted red;
            display: inline-block;
            margin-right: 7pt; 
            margin-bottom: 10pt; 
        }

        .qrcode {
            position: absolute;
            top: 67pt;
            left: 97pt;
            font-size: 9pt;
            color: red;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(9) as $chunk)
    <div class="page">
        @foreach ($chunk as $code)
            <div class="item">
                <span class="qrcode">{{ $code }}</span>
            </div>
        @endforeach
    </div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
