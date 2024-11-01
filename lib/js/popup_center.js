/*
 Method stolen/adapted from this conversation in StackOverflow
 http://stackoverflow.com/questions/5681415/how-to-center-a-popup-window
 */

function PopupCenter(url, title, w, h) {

    // Fixes dual-screen position
    var dualScreenLeft = ( typeof window.screenLeft !== 'undefined' )  ? window.screenLeft : screen.left;
    var dualScreenTop = ( typeof window.screenTop !== 'undefined' ) ? window.screenTop : screen.top;

    var width = window.innerWidth ?
        window.innerWidth :
        ( document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width );
    var height = window.innerHeight ?
        window.innerHeight :
        (document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height );

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }

}
