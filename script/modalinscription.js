$(document).ready(function () {
    $("#next2").hide();
    $("#inscrire1").hide();
    $("#inscrire2").hide();
    $("#inscription2").hide();
    $("#inscription3").hide();
    $("#inscription4").hide();
    $("#next1").click(function () {
      $("#inscription1").hide();
      $("#next1").hide();
      $("#inscription2").show();
      $("#inscrire1").show();
    });
    $("#inscrire1").click(function () {
      $("#inscription1").hide();
      $("#next1").hide();
      $("#inscription2").hide();
      $("#inscrire1").hide();
      $("#next2").show();
      $("#inscription3").show();
    });
  });