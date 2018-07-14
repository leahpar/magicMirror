
Installation
============

Raspbian 
--------

### Network

```
# Turn off anoying power saving management 
sudo echo "iwconfig wlan0 power off" >> /etc/rc.local
```


`wpa_supplicant.conf` :

```
ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
update_config=1
country=FR

network={
    ssid="TATOOINE"
    psk="topsecret"
}
```

### Buster 

(for php 7.2)

`/etc/apt/sources.list.d/buster.list` :

```
deb http://raspbian.raspberrypi.org/raspbian/ buster main contrib non-free rpi
```


`/etc/apt/preferences.d/40buster` :
```
Package: *
Pin: release a=buster
Pin-Priority: 100

Package: php*
Pin: release a=buster
Pin-Priority: 900
```

```
sudo apt update
sudo apt upgrade
sudo apt autoremove
```


PHP 7.2
-------

```
sudo apt install php-cgi php-xml php-mbstring php-curl php-intl php-soap
```

(sudo) `/etc/php/7.2/cli/php.ini`:

```
[Date]
date.timezone = Europe/Paris
```


MagicMirror
-----------

### Install

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
cd vendor
git clone https://github.com/sergiocosus/IEXTrading.git

```

```
../composer install
```


### Start

```
cd ~/magicMirror
#php -S localhost:8000 public/
php bin/console server:start 
```

```
/usr/bin/chromium-browser --incognito --start-maximized --kiosk http://localhost:8000
```

### Autostart

Autostart chromium in fullscreen / remove screensaver 

https://blog.gordonturner.com/2017/07/22/raspberry-pi-full-screen-browser-raspbian-july-2017/


```
sudo apt install unclutter x11-xserver-utils
```

- `unclutter` is used to hide the mouse cursor
- `x11-xserver-utils` installs xset, which is used to disable screen blanking


`/home/pi/.config/lxsession/LXDE-pi/autostart`:

```
# COMMENT THIS
@xscreensaver -no-splash
```
```
# ADD THIS
@/home/pi/magicMirrorStart
```


`/home/pi/magicMirrorStart`: 

```
#!/bin/bash

sleep 10
usr/bin/php ~/magicMiror/bin/console server:start

sleep 10
/usr/bin/chromium-browser --incognito --start-maximized --kiosk http://localhost:8000 &

unclutter -jitter 50 &

xset s off
xset s noblank
xset -dpms
```

Note: quit chromuim with `shift control q`


### Auto-update

git pull
