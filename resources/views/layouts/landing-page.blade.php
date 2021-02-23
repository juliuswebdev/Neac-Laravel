<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{asset('storage/photos/icon.png')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/fonts.css')}}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NEAC Medical Exams Application Center</title>

  </head>
  <body>

     <main>
         <header>
             <nav class="navbar navbar-expand-md navbar-light bg-light bg-white py-0">
                 <div class="container">
                     <a class="navbar-brand my-auto" href="/">
                         <img src="{{asset('storage/photos/logo.png')}}" class="img-fluid d-inline" style="width: 140px;"/>
                     </a>
                 </div>
             </nav>
         </header>

         <section style="
                background: url('{{asset('storage/photos/bg.png')}}') center;
                background-size: cover;
                height: 373px;
             ">
         </section>

         <section style="margin-bottom: 120px;">
          @yield('content')			 
         </section>
         

         <footer style="background: #333;">
             <div class="text-center py-4 text-muted" style="background: #222;">
                <p class="text-white m-0">Developed by Blue Inspires Ph</p>
             </div>
         </footer>
         
     </main>



     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
     <script src="https://kit.fontawesome.com/cdd5289b2e.js" crossorigin="anonymous"></script>
    
  </body>
</html>