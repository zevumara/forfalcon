jQuery(document).ready(function ($) {

    $("#newsletterForm").submit(function(event) {

      event.preventDefault();

      $('#newsletterForm').hide();
      $('#newsletterMessage').fadeIn(100);     

      var data = {
        nickname: $("#inputName").val(),
        email: $("#inputEmail").val(),
      };
      
      $.ajax({
        data: data,
        type: "POST",
        url: 'https://hitaitaro.com/index/suscribe/',
      });

    });

    $("#dungeonPack1Form").submit(function(event) {

      $("#getLink").attr("disabled", true);
      $("#getLink").hide();
      $("#sendingEmail").fadeIn(200);

    });

});