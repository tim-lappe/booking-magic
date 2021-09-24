
let FtpDeploy = require("ftp-deploy");
let ftpDeploy = new FtpDeploy();

let config = {
    user: "timlappe",
    password: "Masyllom2+",
    host: "tim-lappe.de",
    port: 22,
    localRoot: __dirname + "/dist",
    remoteRoot: "/var/www/vhosts/tim-lappe.de/test1.tim-lappe.de/wp-content/plugins/tl-booking-calendar/assets/js/dist",
    include: ["*", "**/*"],
    deleteRemote: true,
    forcePasv: true,
    sftp: true
};

ftpDeploy
.deploy(config)
    .then(res => console.log("finished:", res))
    .catch(err => console.log(err));