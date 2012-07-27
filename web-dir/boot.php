<?php
Header('Content-type: text/plain');
echo "#!ipxe\n";
$IP =  $_SERVER['SERVER_ADDR'];
$S  = 'http://' . $IP . ':' . $_SERVER['SERVER_PORT'];
$D  = ucfirst(basename(substr(__FILE__,0,-4)));
echo "set serverpath $S\n";
echo "set serverip $IP\n";
echo "set distrotype $D\n";
?>

# Ensure we have menu-default set to something
isset ${menu-default} || set menu-default shell

set menu-timeout 5000

:startmenu
menu iPXE boot menu for Zajednicki Informacioni Sistem

item --gap --           ------------------- OS images ----------------
item --key s suse       SUSE based distributions (openSUSE and SLE)
item --key r redhat     RedHat based distributions (RedHat and Centos)
item --key d debian     Debian based distributions (Debian and Ubuntu)
item --gap --           ----------- Old based PXE linux menu ---------
item --key p pxelinux   PXElinux menu based system
item --gap --           --------------- Advanced Menu ----------------
item --key c settings   Configure settings
item shell              Start iPXE shell
item reboot             Reboot this machine
item --key x exit       Exit iPXE and continue BIOS boot
item --gap --           -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto cancel
set menu-timeout 0
goto ${selected}

:cancel
echo  You canceled the menu dropping you to shell

:shell
echo Type exit to get the back to the menu
shell
set menu-timeout 0
goto startmenu

:reboot
reboot

:exit
exit

:settings
config
goto startmenu

:suse
chain ${serverpath}/boot/suse.php

:redhat
chain ${serverpath}/boot/redhat.php

:debian
chain ${serverpath}/boot/debian.php

:pxelinux
imgfree
set 210:string ${serverpath}/boot/
set 209:string ${serverpath}/boot/pxelinux.php?MAC=${net0/mac}&ip=${ip}
set filename ${210:string}pxelinux.0
chain ${filename} ||
echo Netboot failed
shell
