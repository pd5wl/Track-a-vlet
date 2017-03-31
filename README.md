# Track-a-vlet
A GPS tracker based on a Arduino Uno

You'll need the following libraries:

https://github.com/matthijskooijman/arduino-lmic
http://arduiniana.org/libraries/tinygpsplus/

Basicly it a GPS tracker with an LoRaWan Transmitter.

I have used:

Arduino Uno (Also works on a Mega, not yet tested on an Nano)
Blox Neo-6M GPS
Dragino Lora Shield
And some wires.

#How to get it working.

#Front End / WebUI
Get an SSL website.
Set the var's in de ttnlora_gpstracker_vars.js and ttnlora_gpstracker_vars.php
Run mkdir.php, it creates an folder and file with the correct read and wwrite right for your webserver.
Change your images and icons as pleased. For icons go to google maps API to find the specs.

#TTN Console

Make an application. Use the DEVADDR, NWKSKEY & APPSKEYU in the adruino sketch.
Set the following Payload funstion from ttn_payload.txt in the console.
Goto integrations, add an HTTPS integration and give the url to your ttnlora_gpstracker.php

#Arduino
Set the DEVADDR, NWKSKEY & APPSKEYU
Set the vlet ID (any number between 1 and 255) You kan give is a name in the ttnlora_gpstracker_vars.js file.
Upload the sketch.
