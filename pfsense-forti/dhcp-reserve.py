# -*- coding: utf-8 -*-
from xml.etree.ElementTree import parse

tree = parse('dhcplist.xml')
root = tree.getroot()

staticmap = root.findall("staticmap")

f = open("reserved-ip",'w')

ipaddr = [x.findtext("ipaddr") for x in staticmap]
mac = [x.findtext("mac") for x in staticmap]
descr = [x.findtext("descr") for x in staticmap]

for i in range(len(ipaddr)):
    data = "edit %d\n    set ip %s\n    set mac %s\n    set description \'%s\'\nnext\n" % (i+1, ipaddr[i], mac[i], descr[i])
    f.write(data)
f.close()
