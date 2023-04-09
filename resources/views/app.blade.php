<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none
        }

        .wrapper {
            display: flex;
            justify-content: space-between;
            align-content: center;
            flex-direction: row;
            align-items: flex-start;
        }

        .a4 {
            margin: 50px 0 0 100px;
            height: 297mm;
            width: 210mm;
            border: 1px solid lightgreen;
            text-align: center;
            padding-top: 10px;
        }

        .box {
            display: inline-block;
            margin: 10px;
        }

        .buttons {
            position: fixed;
            right: 5rem;
            top: 5rem;
        }
    </style>
    @stack('head')
</head>

<body>

    @yield('main')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    @stack('js')
</body>

</html>
