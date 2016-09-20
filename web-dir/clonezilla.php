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
item          clonezilla         Boot Clonezilla 2.4.7-8
item --gap --                    -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:genericzilla
kernel ${base-url}/live/vmlinuz boot=live config noswap nolocales edd=on nomodeset ocs_live_run="ocs-live-general" ocs_live_extra_param="" ocs_live_keymap="" ocs_live_batch="no" ocs_lang="" vga=788 nosplash noprompt fetch=${base-url}/live/filesystem.squashfs
initrd ${base-url}/live/initrd.img
boot

:clonezilla
set base-url ${serverpath}/clonezilla-2.4.7-8
goto genericzilla
