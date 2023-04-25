<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group</title>
</head>
<body>
    @if(count($groups))
    @foreach ($groups as $item)
        <a href="{{route('chat', $item->id)}}"><li>{{$item->group_name}}</li></a>
    @endforeach
    @endif
</body>
</html>