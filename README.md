# Track-a-vlet
A GPS tracker based on a Arduino Uno

You'll need the following libraries:
<ul>
<li>https://github.com/matthijskooijman/arduino-lmic</li>
<li>http://arduiniana.org/libraries/tinygpsplus/</li>
</ul>
Basicly it a GPS tracker with an LoRaWan Transmitter.

I have used:

Arduino Uno (Also works on a Mega, not yet tested on an Nano)
Blox Neo-6M GPS
Dragino Lora Shield
And some wires.
Apache with SSL on Linux

<u>How to get it working.</u>

<b>Front End / WebUI</b>
<ul>
<li>Get an SSL website.</li>
<li>Set the var's in de ttnlora_gpstracker_vars.js and ttnlora_gpstracker_vars.php</li>
<li>Run mkdir.php, it creates an folder and file with the correct read and wwrite right for your webserver.</li>
<li>Change your images and icons as pleased. For icons go to google maps API to find the specs.</li>
</ul>
<b>TTN Console</b>
<ul>
<li>Make an application. Use the DEVADDR, NWKSKEY & APPSKEYU in the adruino sketch.</li>
<li>Set the following Payload funstion from ttn_payload.txt in the console.</li>
<li>Goto integrations, add an HTTPS integration and give the url to your ttnlora_gpstracker.php</li>
</ul>
<b>Arduino</b>
<ul>
<li>Set the DEVADDR, NWKSKEY & APPSKEYU</li>
<li>Set the vlet ID (any number between 1 and 255) You kan give is a name in the ttnlora_gpstracker_vars.js file.</li>
<li>Upload the sketch.</li>
</ul>
