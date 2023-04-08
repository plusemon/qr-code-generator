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
            padding-top: 55pt;
            padding-left: 43pt;
        }

        .page {
            /* background-color: rgb(232, 232, 232); */
            height: 982pt;
        }

        .item {
            height: 140pt;
            width: 552pt;
            /* border: 1px solid black; */
            position: relative;
        }

        .qrimg {
            position: absolute;
            top: 96pt;
            left: 170pt;
        }

        .qrcode {
            position: absolute;
            top: 111pt;
            left: 216pt;
            text-align: center;
            /* border: 1px solid red; */
            height: 13pt;
            width: 143pt;
            font-size: 12pt;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($items as $qrcodes)
    <div class="artboard">
        <div class="page">
            @foreach ($qrcodes as $qr)
                <div class="item">
                    <img class="qrimg" src="{{ $qr['img'] }}">
                    <span class="qrcode">{{ $qr['code'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
