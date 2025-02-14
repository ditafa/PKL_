$(document).ready(function(){
    $("#menuBagian").click(function(){
        $("#submenuBagian").slideToggle();
    });

    $("#menuLaporan").click(function(){
        $("#submenuLaporan").slideToggle();
    });

    $("#toggleMenu").click(function(){
        let sidebar = $(".sidebar");
        if (sidebar.css("left") === "0px") {
            sidebar.css("left", "-250px");
        } else {
            sidebar.css("left", "0px");
        }
    });

    // Memastikan elemen welcome tetap tampil
    $(".welcome").show();
});