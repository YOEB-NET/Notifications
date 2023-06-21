<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

    @if (!empty($subject))
        <h1 style="text-align:center">{{$subject ?? ""}}</h1>
    @endif

    @if (!empty($image))
        <p style="text-align:center"><img alt="" src="{{$image ?? ""}}" style="height:128px; width:128px" /></p>
    @endif
    <br>

    @if (!empty($brief))
        <p style="text-align:center">{{$brief ?? ""}}</p>
    @endif

</body>
</html>