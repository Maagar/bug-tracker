<!DOCTYPE html>
<html>

<head>
    <title>Nowe zgłoszenie</title>
</head>

<body style="font-family: Arial, sans-serif">
    <h2>Cześć Administratorze!</h2>
    <p> Ktoś włąśnie zgłosił nowy błąd w systemie.</p>

    <div style="border: 1px solid #ccc; padding 15: px; backgroun-color: #f9f9f9;">
        <h3>{{ $ticket->title }}</h3>
        <p>{{ $ticket->description }}</p>
        <p><strong>Status:</strong>{{ $ticket->status }}</p>
    </div>

    <p>Powodzenia w naprawianiu!</p>
</body>

</html>
