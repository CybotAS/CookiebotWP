function copyDebugInfo() {
    const t = document.getElementById( 'cookiebot-debug-info' )
    t.select()
    t.setSelectionRange( 0, 99999 )
    document.execCommand( 'copy' )
}