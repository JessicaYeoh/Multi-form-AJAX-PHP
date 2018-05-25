<?php
session_start();
include 'db.php';
$conn = connect();
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Zootopia</title>

        <!-- Font awesome version 4.7 -->
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


        <style rel="stylesheet">
            #ad_form_section2,
            #ad_form_section3{
            display: none;
            }
        </style>
        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">


        <script src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

        <!-- ? -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>

        <!-- jQuery form validation (username & pw) -->
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>


        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <script type="text/javascript">
        $(document).ready(function(){

              $("#ad_form_section1").validate({
                rules:{
                      ad_title:{
                        required: true
                      },
                      description:{
                        required: true
                      }
                },
                messages:{
                      ad_title: {
                        required: "please enter an ad title"
                      },
                      description: {
                        required: "please enter a description"
                      }
                }
              });

              $("#ad_form_section2").validate({
                rules:{
                      location:{
                        required: true
                      },
                      pet_ad:{
                        required: true
                      },
                      booking_type:{
                        required: true
                      },
                      price:{
                        required: true
                      }
                },
                messages:{
                      location: {
                        required: "please enter a location"
                      },
                      pet_ad:{
                        required: "please select a pet"
                      },
                      booking_type:{
                        required: "please select booking type"
                      },
                      price:{
                        required: "please enter a price"
                      }
                }
              });


              $("#ad_form_section3").validate({
                  rules:{
                        multiple_files:{
                          required: true
                        }
                  },
                  messages:{
                        multiple_files: {
                          required: "please upload images"
                        }
                  },
                      submitHandler: function(form) {
                        var petID = $( "#pet_ad option:selected" ).val();
                        var addAdUrl = "post_ad_process.php?petID=" + petID;

                        var data = $('#ad_form_section1').serialize() + '&' + $('#ad_form_section2').serialize() + '&' + $('#ad_form_section3').serialize();


                             $(form).ajaxSubmit({
                                url:addAdUrl,
                                type:"post",
                                data: data,
                                datatype: 'json',
                                success: function(result){
                                    if(result.petAd == false){
                                      alert("Pet ad already exists!");
                                    }else{
                                      alert("Ad posted!");
                                      $('#image_table').hide();
                                    }
                                },
                                error: function(error) {
                                  alert("Error");
                                }
                            });

                      }
                });



                $("#ad_section2").click(function(){
                    if ($('#ad_form_section1').valid()) {

                        $("#ad_form_section1").hide();
                        $("#ad_form_section2").show();

                    }
                });

                $("#back_section1").click(function(){

                    if ($('#ad_form_section2').valid()) {

                        $("#ad_form_section1").show();
                        $("#ad_form_section2").hide();

                    }
                });

                $("#ad_section3").click(function(){

                    if ($('#ad_form_section2').valid()) {

                        $("#ad_form_section3").show();
                        $("#ad_form_section2").hide();

                    }
                });

                $("#back_section2").click(function(){

                    if ($('#ad_form_section3').valid()) {

                        $("#ad_form_section2").show();
                        $("#ad_form_section3").hide();

                    }
                });
              // });
              });



              $(document).ready(function(){
                   load_image_data();
                   function load_image_data()
                   {
                     var petID = $( "#pet_ad option:selected" ).val();

                    $.ajax({
                     url:"fetch.php?petID=" + petID,
                     method:"POST",
                     success:function(data)
                     {
                      $('#image_table').html(data);
                     }
                    });
                   }

                   $('#multiple_files').change(function(){
                    var error_images = '';
                    var form_data = new FormData();
                    var files = $('#multiple_files')[0].files;
                    if(files.length > 6)                   //FIX THIS
                    {

                     error_images += 'You can not select more than 6 files';
                    }
                    else
                    {
                     for(var i=0; i<files.length; i++)
                     {
                      var name = document.getElementById("multiple_files").files[i].name;
                      var ext = name.split('.').pop().toLowerCase();
                      if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
                      {
                       error_images += '<p>Invalid '+i+' File</p>';
                      }
                      var oFReader = new FileReader();
                      oFReader.readAsDataURL(document.getElementById("multiple_files").files[i]);
                      var f = document.getElementById("multiple_files").files[i];
                      var fsize = f.size||f.fileSize;
                      if(fsize > 1000000)
                      {
                       error_images += '<p>' + i + ' File Size is very big</p>';
                      }
                      else
                      {
                       form_data.append("file[]", document.getElementById('multiple_files').files[i]);
                      }

                     }
                    }
                    if(error_images == '')
                    {
                      var petID = $( "#pet_ad option:selected" ).val();

                     $.ajax({
                      url:"upload_ad_images.php?petID=" + petID,
                      method:"POST",
                      data: form_data,
                      contentType: false,
                      cache: false,
                      processData: false,
                      beforeSend:function(){
                       $('#error_multiple_files').html('<br /><label class="text-primary">Uploading...</label>');
                      },
                      success:function(data)
                      {
                       $('#error_multiple_files').html('<br /><label class="text-success">Uploaded</label>');
                       load_image_data();
                             console.log(files.length);
                      }
                     });
                    }
                    else
                    {
                     $('#multiple_files').val('');
                     $('#error_multiple_files').html("<span class='text-danger'>"+error_images+"</span>");
                     return false;
                    }
                   });

                   $(document).on('click', '.delete', function(){
                    var adImageID = $(this).attr("id");
                    var image_name = $(this).data("image_name");
                    if(confirm("Are you sure you want to remove it?"))
                    {
                     $.ajax({
                      url:"adImage_delete.php",
                      method:"POST",
                      data:{adImageID:adImageID, image_name:image_name},
                      success:function(data)
                      {
                       load_image_data();
                       alert("Image removed");
                      }
                     });
                    }
                   });

                  });

        </script>

  </head>

  <body>

      <div id="ad_form">

          <form id="ad_form_section1" method="post" enctype="multipart/form-data">

                  <div class="form-group">
                    <label for="ad_title">Ad Title</label>
                    <input type="text" class="form-control stored" id="ad_title" placeholder="e.g. German Sheperd puppy - 4 months old" name="ad_title">
                  </div>

                  <div class="form-group">
                    <label for="description">Describe what you're offering</label>
                    <textarea class="form-control stored" id="description" rows="6" placeholder="e.g. Owner supervised visits, minimum 1hr bookings, play with my german sheperd puppy in my backyard" name="description"></textarea>
                  </div>

                  <button type="button" id="ad_section2" class="btn btn-primary"> Next </button>
          </form>

          <form id="ad_form_section2" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="pet_ad">Pet</label>

                    <?php

                    // $userID = $_SESSION['userID'];

                    $showPet = "SELECT * FROM tblpet WHERE userID = 8;";
                    $stmt = $conn->prepare($showPet);
                    // $stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $no_of_pets = $stmt->rowCount();
                    ?>

                    <select id="pet_ad" class="form-control select" name="pet_ad" required onchange="checkPet();">
                        <?php
                        for($i=0;$i<$no_of_pets;$i++){
                        ?>
                      <option value="<?php echo $result[$i]['petID']; ?>"><?php echo $result[$i]['petName']; ?></option>
                        <?php
                        }
                        ?>
                    </select>

                    <div id="pet_status">

                    </div>
                </div>

                <div class="form-group">
                  <label for="location"> Location</label>
                  <input type="text" id="location_ad" class="form-control stored" placeholder="location" name="location"/>
                </div>

                <div class="form-group">
                  <label for="booking_type">What type of booking is allowed for your pet?</label>
                  <select name="booking_type" id="booking_type_ad" class="form-control select">
                    <option>Owner Supervised</option>
                    <option>Private</option>
                    <option>Owner Supervised OR Private</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="price">Price</label>
                  <input type="text" id="price" class="form-control stored" name="price" placeholder="$0.00"/>
                </div>

                <div class="form-group">
                  <div class="form-check">
                    <label class="form-check-label" for="optionsRadios">
                      <input type="radio" class="form-check-input stored" name="optionsRadios" id="optionsRadios1" value="Hourly">
                      Hourly
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label" for="optionsRadios">
                      <input type="radio" class="form-check-input stored" name="optionsRadios" id="optionsRadios2" value="Per Person">
                      Per Person
                    </label>
                  </div>
                </div>

                  <button type="button" id="back_section1" class="btn btn-primary"> Back </button>

                  <button type="button" id="ad_section3" class="btn btn-primary"> Next </button>

            </form>

            <form id="ad_form_section3" method="post" enctype="multipart/form-data">

               <div>
                 <label> Select pet pictures</label>
                <input type="file" name="multiple_files" id="multiple_files" multiple/>

                <span id="error_multiple_files"></span>
               </div>
               <br />
               <div id="image_table">

               </div>


                  <button type="button" id="back_section2" class="btn btn-primary"> Back </button>

                  <input type="submit" id="ad_button" name="ad_button" class="btn btn-primary" value="Post ad"/>

            </form>

    </div>

  </body>
