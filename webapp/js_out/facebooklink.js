
var facebooklink="";

if (app.device.android) {
    facebooklink= "fb://page/61555540812192";

}
else {
    facebooklink= "fb://profile/61555540812192";

}
$("#facebook-link").click(function() {
    window.open(facebooklink, "_system");   
});    

//https://www.facebook.com/profile.php?id=61555540812192