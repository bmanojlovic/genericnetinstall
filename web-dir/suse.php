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

item --gap --                ------------- Return to main menu ------------
item --key b  back           Back to main menu
item --gap --                ----------------------------------------------
item --key h  hypervisor     Boot Suse HyperVisor

item          sles11sp2      Install SUSE Enterprise 11 SP 2 
item          sles11sp1      Install SUSE Enterprise 11 SP 1
item          sles11sp1-32   Install SUSE Enterprise 11 SP 1 32bit
item          sles11         Install SUSE Enterprise 11
item          sles11-32      Install SUSE Enterprise 11 32bit
item          sles10sp3      Install SUSE Enterprise 10 SP 3
item          sles10sp3-32   Install SUSE Enterprise 10 SP 3 32bit
item          sles10sp2      Install SUSE Enterprise 10 SP 2
item          sles10sp2-32   Install SUSE Enterprise 10 SP 3 32bit
item          opensuse1230   Install openSUSE 12.3
item          opensuse1220   Install openSUSE 12.2
item          opensuse1210   Install openSUSE 12.1
item          opensuse1120   Install openSUSE 11.2
item          sles11sp1-pp64 Install SUSE Enterprise 11 SP 1 POWER
item --gap --                -------------- Open Build Service -------------
item          obsworker      OBS Worker Installer
item          obsserver      OBS Server Installer
item --gap --                -------------------- END ----------------------

choose --timeout ${menu-timeout} --default ${menu-default} selected || goto back
set menu-timeout 0
goto ${selected}

:back
imgfree
chain ${serverpath}/boot/boot.php

:genericsuse
kernel  ${base-url}/boot/${susearch}/loader/linux install=${base-url}
initrd  ${base-url}/boot/${susearch}/loader/initrd
boot

:hypervisor
echo "Just Kidding for now"
sleep 4
goto back

:sles11sp2
set susearch x86_64
set base-url ${serverpath}/SLES11SP2-x64
goto genericsuse


:sles11sp1
set susearch x86_64
set base-url ${serverpath}/SLES11SP1-x64
goto genericsuse

:sles11sp1-32
set susearch i386
set base-url ${serverpath}/SLES11SP1-x86
goto genericsuse

:sles11
set susearch x86_64
set base-url ${serverpath}/SLES11-x64
goto genericsuse

:sles11-32
set susearch i386
set base-url ${serverpath}/SLES11-x86
goto genericsuse

:sles10sp3-32
set susearch i386
set base-url ${serverpath}/SLES10SP3-x86
goto genericsuse

:sles10sp3
set susearch x86_64
set base-url ${serverpath}/SLES10SP3-x64
goto genericsuse

:sles10sp2
set susearch x86_64
set base-url ${serverpath}/SLES10SP2-x64
goto genericsuse

:sles10sp2-32
set susearch i386
set base-url ${serverpath}/SLES10SP2-x86
goto genericsuse

:opensuse1230
set susearch x86_64
set base-url ${serverpath}/OS12.3-x64
goto genericsuse

:opensuse1220
set susearch x86_64
set base-url ${serverpath}/OS12.2-x64
goto genericsuse

:opensuse1210
set susearch x86_64
set base-url ${serverpath}/OS12.1-x64
goto genericsuse

:opensuse1120
set susearch x86_64
set base-url ${serverpath}/OS11.2-x64
goto genericsuse

:sles11sp1-pp64
set susearch pp64
set base-url ${serverpath}/SLES11SP1-pp64-DVD1
goto genericsuse

:obsworker
set base-url ${serverpath}/OBS-W-2.3.1/
kernel ${base-url}initrd-netboot-suse-12.1.x86_64-2.1.1.kernel pxe=1 kiwiservertype=http kiwiserver=${serverip} kiwidebug=1
initrd ${base-url}initrd-netboot-suse-12.1.x86_64-2.1.1.gz
boot

:obsserver
