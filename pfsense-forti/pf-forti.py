# -*- coding: utf-8 -*-
import sys
from os.path import exists
from xml.etree.ElementTree import parse

def option_error_print():
    print("pf-forti.py usage\n")
    print("    pf-forti.py dhcp [file.xml]       \tpfsense dhcp static ip -> fortigate reserved ip")
    print("    pf-forti.py alias [file.xml]      \tpfsense aliases -> fortigate addrgrp")
    sys.exit()

def path_error_print():
    print("Please input valid file path")

def reserved_ip(file_path):
    tree = parse(file_path)
    root = tree.getroot()
    
    staticmap = root.findall("staticmap")
    if len(staticmap) == 0:
        print("Please check that file is pfsense dhcp-server xml config")
        sys.exit()
    
    ipaddr,mac,descr=[],[],[]
    for staticmap in staticmap:
        ipaddr.append(staticmap.findtext("ipaddr"))
        mac.append(staticmap.findtext("mac"))
        descr.append(staticmap.findtext("descr"))
    
    f = open("reserved-ip",'w')

    for i in range(len(ipaddr)):
        data = "edit %d\n    set ip %s\n    set mac %s\n    set description \'%s\'\nnext\n" % (i+1, ipaddr[i], mac[i], descr[i])
        f.write(data)
    f.close()

    print("translation complete file - reserved-ip file created")

def aliases(file_path):
    tree = parse(file_path)
    root = tree.getroot()

    alias = root.findall("alias")
    if len(alias) == 0:
        print("Please check that file is pfsense aliases xml config")
        sys.exit()

    f = open("addrgrp", 'w')
    f.write("config firewall address\n")
    for alias in alias:
        ipaddr = alias.findtext("address").split()
        detail = alias.findtext("detail").split('||')
        for i in range(len(ipaddr)): 
            data = "    edit \"%s\"\n        set type ipmask\n        set subnet %s\n        set comment %s\n    next\n" % ( ipaddr[i], ipaddr[i], detail[i])
            f.write(data)
    f.write("end\n\n")

    alias = root.findall("alias")
    f.write("config firewall addrgrp\n")
    for alias in alias: 
        name = alias.findtext("name")
        ipaddr = '" "'.join(alias.findtext("address").split())
        for i in range(len(name)):
            data = "    edit \"%s\"\n        set member \"%s\"\n    next\n" % (name, ipaddr)
        f.write(data)
    f.write("end\n")

    print("translation complete - addrgrp file created")

if __name__ == "__main__":
    if len(sys.argv) != 3 or sys.argv[1] not in ['dhcp', 'alias']:
        option_error_print()

    if sys.argv[1] == 'dhcp': 
        if exists(sys.argv[2]):
            reserved_ip(sys.argv[2])
        else:
            path_error_print()
            
    elif sys.argv[1] == 'alias':
        if exists(sys.argv[2]):
            aliases(sys.argv[2])
        else:
            path_error_print()
