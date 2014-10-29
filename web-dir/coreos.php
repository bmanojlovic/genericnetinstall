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
isset ${menu-default} || set menu-default back

set menu-timeout 5000

:startmenu
menu Install selection for ${distrotype} based distros

item --gap --                    ------------- Return to main menu ------------
item --key b  back               Back to main menu
item --gap --                    ----------------------------------------------
item          coreos-latest      CoreOS Latest Stable (online download)
item          coreos-iso         CoreOS Latest ISO sanboot
item          coreos-iso-local   CoreOS Latest ISO sanboot (Local)
item          coreos-367.1.0     CoreOS 367.1.0
item --gap --                    -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:coreos-367.1.0
set coreosver 367.1.0
goto coreos-local

:coreos-local
kernel  ${serverpath}/core-os/${coreosver}/coreos_production_pxe.vmlinuz
initrd  ${serverpath}/core-os/${coreosver}/coreos_production_pxe_image.cpio.gz
boot

:coreos-latest
kernel http://stable.release.core-os.net/amd64-usr/current/coreos_production_pxe.vmlinuz
initrd http://stable.release.core-os.net/amd64-usr/current/coreos_production_pxe_image.cpio.gz
boot

:coreos-iso
sanboot http://stable.release.core-os.net/amd64-usr/current/coreos_production_iso_image.iso

:coreos-iso-local
set coreosver 367.1.0
sanboot  ${serverpath}/core-os/${coreosver}/coreos_production_iso_image.iso
