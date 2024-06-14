<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>P6</title>
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


      <?php wp_head(); ?>
   </head>
   <body>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <nav class="navbar navbar-expand-lg navbar-light px_header ">
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                  </button>
                  <?php 
                 if(has_custom_logo()){
                the_custom_logo();
                 }
                 ?>
                  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                     <?php 
                     $walker = new Menu_With_Description; 
                     wp_nav_menu(array(
                           'theme_location' => 'top-menu',
                           'menu' => 'navbarTogglerDemo03',
                           'menu_class' => 'navbar-nav ms-auto mt-2 mt-lg-0',
                           'walker' => $walker,
                        
                     ));
                     ?>
                     <?php ?>
                     

                   
                  </div>
               </nav>
            </div>
         </div>

      
         <?php wp_footer(); ?>
         <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>