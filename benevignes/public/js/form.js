
let eyeOpen =  document.querySelectorAll(".ion-eye") ,
    eyeClose = document.querySelectorAll(".ion-eye-disabled");

Object.keys(eyeOpen).map( (key) => {

    eyeOpen[key].addEventListener("click" , function(){
        
        if( this.getAttribute("class") == "ion-eye" ){
            
            document.querySelector("#"+this.getAttribute("data-id-input")).setAttribute("type","text") ;        
            this.setAttribute("class","ion-eye-disabled");
        } else {
            
            document.querySelector("#"+this.getAttribute("data-id-input")).setAttribute("type","password") ;        
            this.setAttribute("class","ion-eye");
        }
    } ) ;
} ) ;

$("form").slideDown( 750 ) ;

$("input").on("input" , function(e) {

    switch( this.id ) {

        case "name" :

            if(checkIdentity( $(this).val() )) 
                $("#notif-name").text( "Votre nom ne peut contenir des chiffres !"); 
            else 
                $("#notif-name").text("") ;
            break;

        case "first_name":

            if(checkIdentity( $(this).val() )) 
                $("#notif-first_name").text( "Votre prénom ne peut contenir des chiffres !"); 
            else 
                $("#notif-first_name").text("") ;
            break;

        case "email": 

            break;
        
        case "password":

            var bannedPass = passBan( $(this).val() ) ;
            // alert(bannedPass);

            switch( checkPass( $(this).val() , bannedPass ) ){

                case "not":
                $("#notif-password").css("background","rgba(255,255,255,.7)") ; 
                    $("#notif-password").html("<img class='strong-pass' src='../images/register/level_pass/glass-with-wine.png' />") ;
                    break ;
                case "faible":
                    $("#notif-password").css("background","rgba(255,255,255,.7)") ;
                    $("#notif-password").html("<img class='strong-pass' src='../images/register/level_pass/glass-with-wine.png' />") ;
                    break;
                
                case "medium":
                    $("#notif-password").css("background","rgba(255,255,255,.7)") ;
                    $("#notif-password").html("<img class='strong-pass' src='../images/register/level_pass/glass-of-wine.png' />") ;
                    break;

                case "strong":
                    $("#notif-password").css("background","rgba(255,255,255,.7)") ;
                    $("#notif-password").html("<img class='strong-pass' src='../images/register/level_pass/wine-bottle.png' />") ;
                    break;
            }
        
            break;
        
        default:
        
            break;
    }


    if( bannedPass ) {
        $("#notif-password").css("background","#FF2825") ;
        $("#notif-password").html( ( "Vous avez entrez une suite de caractéres qui reduissent la force de votre mot de passe" ) ) ;
    } else {

        $("#notif-password").html( $("notif-password").html() ) ;
    }

} ) ;

function checkPass( val , dble ) {

    let size = val.length ; dble = (dble) ? 2 : 1 ; 

    if( size > (2 * dble) && size < (6 * dble) )
        return "faible";
    else if(size >= (6 * dble ) && size <= (10 * dble ) )
        return "medium";
    else if( size > (10 * dble ) )
        return "strong";
    else if( size < 2 )
        return "not";
}

function passBan(val) {

    return ( /.*azerty|abc|123|secret|screed|mystere|admin.*/i.test(val) ) ; 
}

function checkIdentity( val ) {
    
    return /.*\d.*/.test( val ) ;
}

$("#email").on("blur" , function(){

    if( !checkEmail($(this).val() ) && $(this).val().length > 0 )

        $("#notif-email").text("Votre email est invalide !");
    else 
        $("#notif-email").text("");

} ) ;

function checkEmail( val ) {

    return (/.+@.+\..+/.test(val) && val.length > 9 && val.length < 320 );
}

