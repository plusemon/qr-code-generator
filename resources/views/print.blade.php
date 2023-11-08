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

        .items {
            margin-top: 12pt;
            margin-left: 35pt;
            /* background: rgb(230, 230, 230); */
            /* border: 1px solid {{ $isActiveBorder ? 'red' : 'transparent' }}; */
        }

        .item {
            display: inline-block;
            width: 153pt;
            height: 43pt;
            /* margin: 10px; */
            position: relative;
            border: 1px solid {{ $isActiveBorder ? 'green' : 'transparent' }};
        }

        .qrimg {
            position: absolute;
            top: 12pt;
            left: 12pt;
            border: 1px solid {{ $isActiveBorder ? 'green' : 'transparent' }};
        }

        .qrcode {
            position: absolute;
            top: 17pt;
            left: 41pt;
            text-align: center;
            /* height: 16pt; */
            /* width: 50pt; */
            font-size: 9.5pt;
            /* line-height: 14pt; */
            border: 1px solid {{ $isActiveBorder ? 'green' : 'transparent' }};
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@foreach ($qrcodes->chunk(45) as $chunk)
    <table class="items">
        @foreach ($chunk->chunk(3) as $item)
            <tr>
                @foreach ($item as $code)
                    <td>
                        <div class="item">
                            <img class="qrimg" src="{{ url('qrcodes/' . $code->first() . '.svg') }}">
                            <span class="qrcode">{{ $code->last() }}</span>
                        </div>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
