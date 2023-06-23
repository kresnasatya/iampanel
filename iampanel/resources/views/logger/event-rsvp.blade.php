<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Event RSVP from Temp Signature</title>
 </head>
 <body>
     <p>{{ $data['id'] }} {{ $data['user'] }} {{ $data['response'] }}</p>
     <p>URL: {{ $data['url'] }}</p>
     <p>Query string: {{ $data['queryString'] }}</p>
     <p>Signature: <span style="color: red;">{{ $data['signature'] }}</span></p>
     <p>Signature from query string: <span style="color: red;">{{ $data['_signature'] }}</span></p>
 </body>
 </html>
