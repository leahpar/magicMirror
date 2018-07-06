
Installation
============

Raspbian 
--------


### Network

```
# Turn off anoying power saving management 
sudo echo "iwconfig wlan0 power off" >> /etc/rc.local
```

wpa_supplicant.conf :
```
ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
update_config=1
country=FR

network={
    ssid="TATOOINE"
    psk="topsecret"
}
```


### Some stuff

```
sudo apt update
sudo apt upgrade

sudo apt install php-cgi git 
```

### MagicMirror

```
wget -O composer https://getcomposer.org/composer.phar
chmod +x composer
```

```
git clone https://github.com/leahpar/magicMirror.git
cd magicMirror
```

```
vi .env
```

```
../composer install
```

### Start

```
cd ~/magicMirror/public
php -S localhost:8000 
```

```
/usr/bin/chromium-browser --incognito --start-maximized --kiosk http://localhost:8000
```

screensaver

### Autostart

### Autoupdate

git pull
