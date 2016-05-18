#!/bin/bash
while read p; do
if [ ${#p} -eq 5 ]
then
q=0${p}
else
q=$p
fi
data=`/home/wmfo-admin/echonest/ENMFP_codegen/codegen.Linux-x86_64 /mnt/raid/Rivendell/${q}_001.wav 5 30`
echo $data > /home/wmfo-admin/echonest/codes/${q}
done <music.ids
