
var h2 = document.querySelectorAll(".group-by h2") ;

Object.keys( h2 ).map( (key) => {

    h2[key].addEventListener( "click" , function(){

        let subNav = null ;

        if( this.textContent == 'Connexion' )
        
            subNav = document.querySelector("#sub-nav-login") ;
         else
            subNav = document.querySelector("#sub-nav-register") ;

        subNav.style.display = ((getComputedStyle(subNav).display == 'none') ? "block" :"none" );

    } ) ;

} ) ;