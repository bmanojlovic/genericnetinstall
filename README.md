What is this?
=============

This code represents simplest possible web based PXE boot environment
based on http://ipxe.org/ code 

F.A.Q.
------

### What part of it is web? pxe is NOT web!?

True, but only partialy as great project called iPXE has done
great job of making pxe environment very scriptable AND supports http protocol.

### Which distros are supported?

All linux distributions that can reuse pxe net boot logic and understand
http as protocol for downloading files after initial boot of kernel

### How does it work with isc dhcpd?

You have to set up few things in configuration of dhcpd as suggested bellow
where server ip address is 10.10.10.10:

    # bellow is used for diferentiation between legacy and efi boot
    option client-architecture code 93 = unsigned integer 16;

    option domain-name "mygreatdomain.com";
    option domain-name-servers 10.10.10.1, 10.10.10.2;
    option routers 10.10.10.254;
    option ntp-servers 10.10.10.10;
    option log-servers 10.10.10.10;
    option space ipxe;
    option ipxe-encap-opts code 175 = encapsulate ipxe;
    option ipxe.priority code 1 = signed integer 8;
    option ipxe.keep-san code 8 = unsigned integer 8;
    option ipxe.skip-san-boot code 9 = unsigned integer 8;
    option ipxe.syslogs code 85 = string;
    option ipxe.cert code 91 = string;
    option ipxe.privkey code 92 = string;
    option ipxe.crosscert code 93 = string;
    option ipxe.no-pxedhcp code 176 = unsigned integer 8;
    option ipxe.bus-id code 177 = string;
    option ipxe.bios-drive code 189 = unsigned integer 8;
    option ipxe.username code 190 = string;
    option ipxe.password code 191 = string;
    option ipxe.reverse-username code 192 = string;
    option ipxe.reverse-password code 193 = string;
    option ipxe.version code 235 = string;
    option iscsi-initiator-iqn code 203 = string;
    # Feature indicators
    option ipxe.pxeext code 16 = unsigned integer 8;
    option ipxe.iscsi code 17 = unsigned integer 8;
    option ipxe.aoe code 18 = unsigned integer 8;
    option ipxe.http code 19 = unsigned integer 8;
    option ipxe.https code 20 = unsigned integer 8;
    option ipxe.tftp code 21 = unsigned integer 8;
    option ipxe.ftp code 22 = unsigned integer 8;
    option ipxe.dns code 23 = unsigned integer 8;
    option ipxe.bzimage code 24 = unsigned integer 8;
    option ipxe.multiboot code 25 = unsigned integer 8;
    option ipxe.slam code 26 = unsigned integer 8;
    option ipxe.srp code 27 = unsigned integer 8;
    option ipxe.nbi code 32 = unsigned integer 8;
    option ipxe.pxe code 33 = unsigned integer 8;
    option ipxe.elf code 34 = unsigned integer 8;
    option ipxe.comboot code 35 = unsigned integer 8;
    option ipxe.efi code 36 = unsigned integer 8;
    option ipxe.fcoe code 37 = unsigned integer 8;

    allow booting;
    next-server 10.10.10.10;

    if exists user-class and option user-class = "iPXE" {
      filename "http://10.10.10.10/boot/boot.php";
    } else {
      if option client-architecture = 00:07 {
        #UEFI client
        filename "snponly.efi";
      } else if option client-architecture = 00:00 {
        filename "undionly.kpxe";
      }
    }

    subnet 10.10.10.0 netmask 255.255.255.0 {
      pool {
        range dynamic-bootp 10.10.10.20 10.10.10.125;
        default-lease-time 14400;
        server-name "netinstall";
        max-lease-time 172800;
      }
    }


### How to set web / tftp?

To make it work trough both of tftp and http protocol some things need
to be done:
first you should bind mount /srv/tftproot to web root under /boot name
so "filename" setting would match same line from dhcpd.conf file
second things is to forbid direct access to /boot/upload directory
for security reasons


### Next steps?

Loop mount iso images to web root so it can be seen from client:

    mount -o loop /srv/isos/SLES-11-SP2-DVD-x86_64-GM-DVD1.iso /srv/www/htdocs/SLES-11-SP2-DVD1-x86_64

edit suse.php and look for path to web directory to match it like here

    :sles11sp2
    set susearch x86_64
    set base-url ${serverpath}/SLES-11-SP2-DVD1-x86_64
    goto genericsuse


