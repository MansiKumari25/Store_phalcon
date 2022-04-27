$(document).ready(function(){

   $("#table").on("click", "#edit", function(e){
       e.preventDefault();

      $(this).parent().parent().parent().find("input").prop("disabled", false);
      $(this).parent().parent().parent().find("textarea").prop("disabled", false);
     $(this).parent().children("#update").addClass("d-inline");
     $(this).addClass("d-none");
   })
  
  });