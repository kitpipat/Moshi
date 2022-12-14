<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>X-editable starter template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- bootstrap -->
    <!-- <link rel="stylesheet" href="<?= base_url();?>application/assets/vendor/bootstrap/css/bootstrap.min.css">  -->
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <!-- <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>  -->
    <script src="<?php echo base_url();?>application/assets/vendor/jquery/jquery.js"></script>

    <!-- <script src="<?php echo base_url();?>application/assets/vendor/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>  
    

    <!-- x-editable (bootstrap version) -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>
  
  </head>

  <body>

    <div class="container">

      <h1>X-editable starter template</h1>

      <div>
        <span>Username:</span>
        <a href="#" id="username" data-type="text" data-placement="right" data-title="Enter username">superuser</a>
      </div>
      
      <div>
        <span>Status:</span>
        <a href="#" id="status"></a>
      </div>

      
    </div>
    <script>
    $(document).ready(function() {
        //toggle `popup` / `inline` mode
        $.fn.editable.defaults.mode = 'popup';     
        
        //make username editable
        $('#username').editable();
        
        //make status editable
        $('#status').editable({
            type: 'select',
            title: 'Select status',
            placement: 'right',
            value: 2,
            source: [
                {value: 1, text: 'status 1'},
                {value: 2, text: 'status 2'},
                {value: 3, text: 'status 3'}
            ]
            /*
            //uncomment these lines to send data on server
            ,pk: 1
            ,url: '/post'
            */
        });
    });
    </script>

  </body>

</html>