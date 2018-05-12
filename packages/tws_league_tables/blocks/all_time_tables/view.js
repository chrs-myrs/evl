waitPlugin("tablesorter", function(data) {
    $("#all-time-table").tablesorter();
    console.log("Tablesorter: "+data);
}); 

function waitPlugin(plugin, callback) {
    var script, expire;

    // Already there?
    if ($[plugin]) {
        setTimeout(function() {
            callback('already loaded');
            
        }, 0);
        return;
    }

    // Determine when to give up
    expire = new Date().getTime() + 10000; // 20 seconds

    // Start looking for the symbol to appear, yielding as
    // briefly as the browser will let us.
    setTimeout(lookForSymbol, 0);

    // Our symbol-checking function
    function lookForSymbol() {
        if ($[plugin]) {
            // There's the symbol, we're done
            callback('success');
        }
        else if (new Date().getTime() > expire) {
            // Timed out, tell the callback
            callback('timeout');
        }
        else {
            // Schedule the next check
            setTimeout(lookForSymbol, 100);
        }
    }
}